<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - User</title>
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
        <h1 class="text-center mb-4">Welcome to the Library</h1>
        <div class="row">
            <!-- Featured Books -->
            <div class="col-md-12">
                <h2>Featured Books</h2>
                <div class="row">
                    <?php
                    include_once '../includes/db.php';
                    include_once '../includes/functions.php';
                    $featuredBooks = getBooks($conn, [], 'published_year', 'DESC', 20);
                    foreach ($featuredBooks as $book):
                    ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="../assets/images/<?php echo $book['cover_image']; ?>" class="card-img-top" alt="<?php echo $book['title']; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $book['title']; ?></h5>
                                    <p class="card-text">By <?php echo $book['author']; ?></p>
                                    <a href="book_detail.php?id=<?php echo $book['id']; ?>" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once '../includes/footer.php'; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>