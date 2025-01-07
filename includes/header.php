<?php
include_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Library Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isLoggedIn()): ?>
                        <?php if (isAdmin()): ?>
                            <li class="nav-item"><a class="nav-link" href="/admin/dashboard.php">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="/admin/manage_books.php">Manage Books</a></li>
                            <li class="nav-item"><a class="nav-link" href="/admin/manage_users.php">Manage Users</a></li>
                            <li class="nav-item"><a class="nav-link" href="/admin/manage_borrow.php">Manage Borrow</a></li>
                            <!-- Trong file header.php, thêm liên kết vào phần menu của admin -->
                            <li class="nav-item">
                                <a class="nav-link" href="/admin/near_due_books.php">Near Due Books</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="/admin/manage_comments.php">Manage Comments</a></li>
                            <li class="nav-item"><a class="nav-link" href="/admin/reports.php">Reports</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="/user/index.php">Home</a></li>
                            <li class="nav-item"><a class="nav-link" href="/user/search.php">Search Books</a></li>
                            <li class="nav-item"><a class="nav-link" href="/user/borrow_management.php">Borrow Management</a></li>
                            <li class="nav-item"><a class="nav-link" href="/user/account.php">My Account</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="/user/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>