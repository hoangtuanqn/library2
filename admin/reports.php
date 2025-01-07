<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';
redirectIfNotAdmin();

// Lấy danh sách báo cáo
$sql = "SELECT reports.id, reports.user_id, reports.book_id, reports.issue, reports.report_date, users.username, books.title 
        FROM reports 
        JOIN users ON reports.user_id = users.id 
        JOIN books ON reports.book_id = books.id 
        ORDER BY reports.report_date DESC";
$result = $conn->query($sql);
$reports = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Admin</title>
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
        <h1 class="text-center mb-4">Reports</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th> <!-- Thêm cột ID -->
                    <th>User</th>
                    <th>Book</th>
                    <th>Issue</th>
                    <th>Report Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo $report['id']; ?></td> <!-- Hiển thị ID -->
                        <td><?php echo $report['username']; ?></td>
                        <td><?php echo $report['title']; ?></td>
                        <td><?php echo $report['issue']; ?></td>
                        <td><?php echo $report['report_date']; ?></td>
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