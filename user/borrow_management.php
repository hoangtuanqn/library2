<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';
redirectIfNotLoggedIn();

$user_id = $_SESSION['user_id'];

// Hiển thị thông báo thành công nếu có
if (isset($_GET['success'])) {
    $success = htmlspecialchars($_GET['success']);
}

// Lấy danh sách sách đã mượn, sắp xếp theo borrow_date giảm dần
$sql = "SELECT borrows.id AS borrow_id, books.id AS book_id, books.title, books.author, borrows.borrow_date, borrows.return_date, borrows.status, borrows.borrow_duration 
        FROM borrows 
        JOIN books ON borrows.book_id = books.id 
        WHERE borrows.user_id = $user_id AND borrows.status = 'borrowed'
        ORDER BY borrows.borrow_date DESC"; // Sắp xếp theo borrow_date giảm dần
$result = $conn->query($sql);
$borrowed_books = $result->fetch_all(MYSQLI_ASSOC);
$i = 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowed Books - User</title>
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
        <h1 class="text-center mb-4">Borrowed Books</h1>
        <?php if (isset($success)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                    <th>Borrow Duration (Days)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($borrowed_books)): ?>
                    <tr>
                        <td colspan="8" class="text-center">You have not borrowed any books.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($borrowed_books as $row): ?>
                        <tr>
                            <td><?= ++$i; ?></td>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['author']; ?></td>
                            <td><?php echo $row['borrow_date']; ?></td>
                            <td><?php echo $row['return_date']; ?></td>
                            <td><?php echo $row['borrow_duration']; ?></td>
                            <td>
                                <span class="status-<?php echo strtolower($row['status']); ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td>
                                <a href="book_detail.php?id=<?php echo $row['book_id']; ?>" class="btn btn-primary">View Details</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
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