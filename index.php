<?php
include_once 'includes/db.php';

// Lấy địa chỉ IP của người dùng
$ip_address = $_SERVER['REMOTE_ADDR'];

// Lấy ngày hiện tại
$visit_date = date('Y-m-d');

    // Kiểm tra xem IP này đã truy cập trong ngày chưa
    $sql = "SELECT id FROM visits WHERE ip_address = '$ip_address' AND visit_date = '$visit_date'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    // Nếu chưa truy cập, thêm bản ghi mới
    $sql = "INSERT INTO visits (ip_address, visit_date) VALUES ('$ip_address', '$visit_date')";
    $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .hero {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            padding: 100px 0;
            text-align: center;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 30px;
        }

        .hero .btn {
            font-size: 1.1rem;
            padding: 10px 30px;
            border-radius: 25px;
        }

        .featured-books,
        .recently-added {
            padding: 60px 0;
        }

        .featured-books h2,
        .recently-added h2 {
            font-size: 2.5rem;
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

        .card-img-top {
            height: 300px;
            object-fit: cover;
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

        .btn-primary {
            background-color: #2575fc;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #6a11cb;
        }

        /* Card Image Wrapper */
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

        /* Card Overlay */
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

        /* Badge */
        .badge {
            font-size: 0.9rem;
            padding: 5px 10px;
        }

        /* Gradient Background */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
        }
    </style>
</head>

<body>
    <!-- Include Header -->
    <?php include_once 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Welcome to the Library Management System</h1>
            <p>Explore our vast collection of books and manage your borrowings with ease.</p>
            <a href="/user/search.php" class="btn btn-light">Search Books</a>
        </div>
    </section>

    <!-- Featured Books Section -->
    <section class="featured-books py-5">
        <div class="container">
            <h2 class="text-center mb-5">Featured Books</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                include_once 'includes/db.php';
                include_once 'includes/functions.php';

                $featuredBooks = getBooks($conn, [], 'published_year', 'DESC', 20);
                foreach ($featuredBooks as $book):
                ?>
                    <div class="col">
                        <div class="card h-100 shadow-lg border-0">
                            <div class="card-img-wrapper position-relative overflow-hidden">
                                <img src="assets/images/<?php echo $book['cover_image']; ?>" class="card-img-top" alt="<?php echo $book['title']; ?>">
                                <div class="card-overlay position-absolute w-100 h-100 d-flex justify-content-center align-items-center">
                                    <a href="/user/book_detail.php?id=<?php echo $book['id']; ?>" class="btn btn-primary btn-lg">View Details</a>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title fw-bold"><?php echo $book['title']; ?></h5>
                                <p class="card-text text-muted">By <?php echo $book['author']; ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="badge bg-gradient-primary">Available</span>
                                    <small class="text-muted"><?php echo $book['published_year']; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Recently Added Books Section -->
    <section class="recently-added bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5">Recently Added Books</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <?php
                $recentBooks = getBooks($conn, [], 'id', 'DESC', 3);
                foreach ($recentBooks as $book):
                ?>
                    <div class="col">
                        <div class="card h-100 shadow-lg border-0">
                            <div class="card-img-wrapper position-relative overflow-hidden">
                                <img src="/assets/images/<?php echo $book['cover_image']; ?>" class="card-img-top" alt="<?php echo $book['title']; ?>">
                                <div class="card-overlay position-absolute w-100 h-100 d-flex justify-content-center align-items-center">
                                    <a href="/user/book_detail.php?id=<?php echo $book['id']; ?>" class="btn btn-primary btn-lg">View Details</a>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title fw-bold"><?php echo $book['title']; ?></h5>
                                <p class="card-text text-muted">By <?php echo $book['author']; ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="badge bg-gradient-warning">New Arrival</span>
                                    <small class="text-muted"><?php echo $book['published_year']; ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include_once 'includes/footer.php'; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>