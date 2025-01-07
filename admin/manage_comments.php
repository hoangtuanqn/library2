<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';
redirectIfNotAdmin();

// Xử lý xóa bình luận
if (isset($_GET['delete_id'])) {
    $comment_id = $_GET['delete_id'];
    $sql = "DELETE FROM comments WHERE id = $comment_id";
    if ($conn->query($sql) === TRUE) {
        $success = "Comment deleted successfully!";
    } else {
        $error = "Error deleting comment: " . $conn->error;
    }
}

// Lấy tất cả bình luận từ database
$sql = "SELECT comments.*, users.username, books.title 
        FROM comments 
        JOIN users ON comments.user_id = users.id 
        JOIN books ON comments.book_id = books.id 
        ORDER BY comments.created_at DESC";
$result = $conn->query($sql);
$comments = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Comments - Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <?php include_once '../includes/header.php'; ?>

    <!-- Main Content -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Manage Comments</h1>
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
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Book</th>
                    <th>Comment</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comments as $comment): ?>
                    <tr>
                        <td><?php echo $comment['id']; ?></td>
                        <td><?php echo $comment['username']; ?></td>
                        <td><?php echo $comment['title']; ?></td>
                        <td><?php echo $comment['comment']; ?></td>
                        <td><?php echo $comment['created_at']; ?></td>
                        <td>
                            <a href="?delete_id=<?php echo $comment['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <?php include_once '../includes/footer.php'; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>