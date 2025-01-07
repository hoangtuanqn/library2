<?php
include_once '../includes/auth.php';
redirectIfNotLoggedIn();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Books - User</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .search-section {
            padding: 60px 0;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
        }

        .search-section h1 {
            font-size: 2.5rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .search-section .form-control {
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 1rem;
        }

        .search-section .btn {
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 1rem;
        }

        .search-results {
            padding: 60px 0;
        }

        .search-results h2 {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 40px;
            text-align: center;
        }

        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .card-img-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 15px 15px 0 0;
        }

        .card-img-wrapper img {
            transition: transform 0.3s ease;
        }

        .card-img-wrapper:hover img {
            transform: scale(1.1);
        }

        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card-img-wrapper:hover .card-overlay {
            opacity: 1;
        }

        .card-overlay .btn {
            font-size: 1rem;
            padding: 10px 20px;
            border-radius: 25px;
        }

        .card-body {
            padding: 20px;
            text-align: center;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 1rem;
            color: #666;
        }

        .badge {
            font-size: 0.9rem;
            padding: 5px 10px;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include_once '../includes/header.php'; ?>

    <!-- Search Section -->
    <section class="search-section">
        <div class="container">
            <h1 class="text-center">Search Books</h1>
            <form method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" class="form-control" value="<?= ($_GET['query'] ?? ""); ?>" placeholder="Search by title, author, or genre" name="query">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Search Results Section -->
    <section class="search-results">
        <div class="container">
            <h2>Search Results</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                include_once '../includes/db.php';
                include_once '../includes/functions.php';
                $query = isset($_GET['query']) ? $_GET['query'] : '';
                $books = searchBooks($conn, $query);
                foreach ($books as $book):
                ?>
                    <div class="col">
                        <div class="card h-100 shadow-lg">
                            <div class="card-img-wrapper">
                                <img src="../assets/images/<?php echo $book['cover_image']; ?>" class="card-img-top" alt="<?php echo $book['title']; ?>">
                                <div class="card-overlay">
                                    <a href="book_detail.php?id=<?php echo $book['id']; ?>" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $book['title']; ?></h5>
                                <p class="card-text text-muted">By <?php echo $book['author']; ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">Available</span>
                                    <small class="text-muted"><?php echo $book['published_year']; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include_once '../includes/footer.php'; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>