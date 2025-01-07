<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';
redirectIfNotAdmin();

// Lấy tổng số lượt truy cập
$sql = "SELECT COUNT(*) as total_visits FROM visits";
$result = $conn->query($sql);
$total_visits = $result->fetch_assoc()['total_visits'];

// Lấy tổng số sách
$sql = "SELECT COUNT(*) as total_books FROM books";
$result = $conn->query($sql);
$total_books = $result->fetch_assoc()['total_books'];

// Lấy tổng số sách đang được mượn
$sql = "SELECT COUNT(*) as total_borrowed FROM borrows WHERE status = 'borrowed'";
$result = $conn->query($sql);
$total_borrowed = $result->fetch_assoc()['total_borrowed'];

// Lấy tổng số người dùng
$sql = "SELECT COUNT(*) as total_users FROM users";
$result = $conn->query($sql);
$total_users = $result->fetch_assoc()['total_users'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
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
        <h1 class="text-center mb-4">Admin Dashboard</h1>
        <div class="row">
            <!-- Total Books Card -->
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Books</h5>
                        <p class="card-text"><?php echo $total_books; ?></p>
                    </div>
                </div>
            </div>
            <!-- Borrowed Books Card -->
            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Borrowed Books</h5>
                        <p class="card-text"><?php echo $total_borrowed; ?></p>
                    </div>
                </div>
            </div>
            <!-- Total Users Card -->
            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
            <!-- Total Visits Card -->
            <div class="col-md-3 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Visits</h5>
                        <p class="card-text"><?php echo $total_visits; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Borrowed Books -->
        <div class="mt-5">
            <h2>Recent Borrowed Books</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Borrower</th>
                        <th>Borrow Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Lấy danh sách sách được mượn gần đây
                    $sql = "SELECT books.title, users.username, borrows.borrow_date, borrows.return_date, borrows.status 
                        FROM borrows 
                        JOIN books ON borrows.book_id = books.id 
                        JOIN users ON borrows.user_id = users.id 
                        ORDER BY borrows.borrow_date DESC 
                        LIMIT 5";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?php echo $row['title']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['borrow_date']; ?></td>
                            <td><?php echo $row['return_date']; ?></td>
                            <td>
                                <span class="status-<?php echo strtolower($row['status']); ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once '../includes/footer.php'; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>