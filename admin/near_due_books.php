<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';
redirectIfNotAdmin();

// Lấy danh sách sách gần tới thời hạn (còn khoảng 5 ngày)
$current_date = date('Y-m-d');
$sql = "SELECT borrows.*, books.title, books.author, users.username 
        FROM borrows 
        JOIN books ON borrows.book_id = books.id 
        JOIN users ON borrows.user_id = users.id 
        WHERE borrows.status = 'borrowed' 
        AND DATEDIFF(borrows.return_date, '$current_date') <= 5 
        ORDER BY borrows.return_date ASC";
$result = $conn->query($sql);
$near_due_books = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Near Due Books - Admin</title>
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
        <h1 class="text-center mb-4">Near Due Books</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Borrower</th>
                    <th>Borrow Date</th>
                    <th>Return Date</th>
                    <th>Days Remaining</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($near_due_books)): ?>
                    <tr>
                        <td colspan="8" class="text-center">No books are near due.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($near_due_books as $book): ?>
                        <tr>
                            <td><?php echo $book['id']; ?></td>
                            <td><?php echo $book['title']; ?></td>
                            <td><?php echo $book['author']; ?></td>
                            <td><?php echo $book['username']; ?></td>
                            <td><?php echo $book['borrow_date']; ?></td>
                            <td><?php echo $book['return_date']; ?></td>
                            <td>
                                <?php
                                $return_date = new DateTime($book['return_date']);
                                $current_date = new DateTime();
                                $interval = $current_date->diff($return_date);
                                echo $interval->days . " days";
                                ?>
                            </td>
                            <td>
                                <a href="edit_borrow.php?id=<?php echo $book['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_borrow.php?id=<?php echo $book['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
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