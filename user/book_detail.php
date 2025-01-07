<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';
redirectIfNotLoggedIn();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$book_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Lấy thông tin sách
$sql = "SELECT * FROM books WHERE id = $book_id";
$result = $conn->query($sql);
$book = $result->fetch_assoc();

if (!$book) {
    header('Location: index.php');
    exit();
}

// Kiểm tra xem người dùng đang mượn sách này không
$sql = "SELECT * FROM borrows WHERE user_id = $user_id AND book_id = $book_id AND status = 'borrowed'";
$result = $conn->query($sql);
$is_borrowing = $result->num_rows > 0;

// Kiểm tra xem người dùng đã trả sách này chưa (status = 'returned')
$sql = "SELECT * FROM borrows WHERE user_id = $user_id AND book_id = $book_id AND status = 'returned'";
$result = $conn->query($sql);
$is_returned = $result->num_rows > 0;

// Xử lý mượn sách
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['borrow_book'])) {
    if ($book['quantity'] <= 0) {
        $error = "This book is out of stock.";
    } elseif ($is_borrowing) {
        $error = "You are currently borrowing this book.";
    } else {
        $borrow_date = date('Y-m-d');
        $borrow_duration = $book['borrow_duration']; // Lấy số ngày mượn từ thông tin sách
        $return_date = date('Y-m-d', strtotime("+$borrow_duration days", strtotime($borrow_date))); // Tính ngày trả

        // Thêm vào bảng borrows
        $sql = "INSERT INTO borrows (user_id, book_id, borrow_date, return_date, status, borrow_duration) 
                VALUES ('$user_id', '$book_id', '$borrow_date', '$return_date', 'borrowed', '$borrow_duration')";
        if ($conn->query($sql) === TRUE) {
            // Giảm số lượng sách
            $new_quantity = $book['quantity'] - 1;
            $sql = "UPDATE books SET quantity = $new_quantity WHERE id = $book_id";
            if ($conn->query($sql) === TRUE) {
                $success = "Book borrowed successfully!";
                header('Location: borrow_management.php?success=Book borrowed successfully!');
                exit();
            } else {
                $error = "Error updating book quantity: " . $conn->error;
            }
        } else {
            $error = "Error borrowing book: " . $conn->error;
        }
    }
}

// Kiểm tra số lượng bình luận của người dùng trên sách này
$sql = "SELECT COUNT(*) as comment_count FROM comments WHERE book_id = $book_id AND user_id = $user_id AND parent_comment_id IS NULL";
$result = $conn->query($sql);
$comment_count = $result->fetch_assoc()['comment_count'];

// Xử lý thêm bình luận
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    if (!$is_borrowing) {
        $error = "You must borrow this book to comment.";
    } elseif ($comment_count >= 3) {
        $error = "You can only comment 3 times on this book.";
    } else {
        $comment = $_POST['comment'];
        $sql = "INSERT INTO comments (book_id, user_id, comment) VALUES ('$book_id', '$user_id', '$comment')";
        if ($conn->query($sql) === TRUE) {
            $success = "Comment added successfully!";
        } else {
            $error = "Error adding comment: " . $conn->error;
        }
    }
}

// Xử lý thêm phản hồi từ admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply'])) {
    if (!isAdmin()) {
        $error = "Only admin can reply to comments.";
    } else {
        $parent_comment_id = $_POST['parent_comment_id'];
        $reply = $_POST['reply'];
        $sql = "INSERT INTO comments (book_id, user_id, parent_comment_id, comment) VALUES ('$book_id', '$user_id', '$parent_comment_id', '$reply')";
        if ($conn->query($sql) === TRUE) {
            $success = "Reply added successfully!";
        } else {
            $error = "Error adding reply: " . $conn->error;
        }
    }
}

// Lấy tất cả bình luận và phản hồi
$sql = "SELECT c.*, u.username 
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.book_id = $book_id 
        ORDER BY c.parent_comment_id ASC, c.created_at ASC";
$result = $conn->query($sql);
$comments = $result->fetch_all(MYSQLI_ASSOC);

// Nhóm bình luận và phản hồi
$grouped_comments = [];
foreach ($comments as $comment) {
    if ($comment['parent_comment_id'] === NULL) {
        $grouped_comments[$comment['id']] = [
            'comment' => $comment,
            'replies' => []
        ];
    } else {
        $grouped_comments[$comment['parent_comment_id']]['replies'][] = $comment;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<body>
    <!-- Navbar -->
    <?php include_once '../includes/header.php'; ?>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <img src="../assets/images/<?php echo $book['cover_image']; ?>" class="img-fluid" alt="<?php echo $book['title']; ?>">
            </div>
            <div class="col-md-8">
                <h1><?php echo $book['title']; ?></h1>
                <p><strong>Author:</strong> <?php echo $book['author']; ?></p>
                <p><strong>Genre:</strong> <?php echo $book['genre']; ?></p>
                <p><strong>Published Year:</strong> <?php echo $book['published_year']; ?></p>
                <p><strong>Status:</strong>
                    <span class="status-<?php echo strtolower($book['status']); ?>">
                        <?php echo $book['status']; ?>
                    </span>
                </p>
                <p><strong>Borrow Duration:</strong> <?php echo $book['borrow_duration']; ?> days</p>
                <p><strong>Quantity:</strong> <?php echo $book['quantity']; ?></p>
                <p><strong>Description:</strong> <?php echo $book['description']; ?></p>

                <!-- Nút mượn sách -->
                <?php if ($is_borrowing): ?>
                    <button class="btn btn-secondary" disabled>You are currently borrowing this book.</button>
                <?php elseif ($book['quantity'] > 0): ?>
                    <form method="POST">
                        <button type="submit" name="borrow_book" class="btn btn-primary" onclick="return confirmBorrow(<?php echo $book['quantity']; ?>)">Borrow This Book</button>
                    </form>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>This book is out of stock.</button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Comment Section -->
        <div class="mt-5">
            <h2>Comments</h2>
            <?php if (isset($success)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <!-- Form thêm bình luận -->
            <?php if ($is_borrowing && $comment_count < 3): ?>
                <form method="POST" class="mb-4">
                    <div class="mb-3">
                        <label for="comment" class="form-label">Add a comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Comment</button>
                </form>
            <?php endif; ?>

            <!-- Hiển thị bình luận và phản hồi -->
            <div class="comments">
                <?php foreach ($grouped_comments as $comment_data): ?>
                    <!-- Bình luận gốc -->
                    <div class="card mb-3 <?php echo ($comment_data['comment']['username'] === 'admin') ? 'comment-admin' : 'comment-user'; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $comment_data['comment']['username']; ?></h5>
                            <p class="card-text"><?php echo $comment_data['comment']['comment']; ?></p>
                            <p class="text-muted"><small><?php echo $comment_data['comment']['created_at']; ?></small></p>

                            <!-- Form phản hồi (chỉ admin) -->
                            <?php if (isAdmin()): ?>
                                <form method="POST" class="mt-2">
                                    <input type="hidden" name="parent_comment_id" value="<?php echo $comment_data['comment']['id']; ?>">
                                    <div class="mb-3">
                                        <label for="reply" class="form-label">Reply as Admin</label>
                                        <textarea class="form-control" id="reply" name="reply" rows="2" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-warning">Submit Reply</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Phản hồi -->
                    <?php foreach ($comment_data['replies'] as $reply): ?>
                        <div class="card mb-3 ms-5 <?php echo ($reply['username'] === 'admin') ? 'reply-admin' : 'reply-user'; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $reply['username']; ?></h5>
                                <p class="card-text"><?php echo $reply['comment']; ?></p>
                                <p class="text-muted"><small><?php echo $reply['created_at']; ?></small></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once '../includes/footer.php'; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        function confirmBorrow(quantity) {
            if (quantity <= 0) {
                alert('This book is out of stock.');
                return false;
            }
            return confirm('Are you sure you want to borrow this book?');
        }
    </script>
</body>

</html>