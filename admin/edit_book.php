<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';
redirectIfNotAdmin();

if (!isset($_GET['id'])) {
    header('Location: manage_books.php');
    exit();
}

$book_id = $_GET['id'];

// Lấy thông tin sách từ database
$sql = "SELECT * FROM books WHERE id = $book_id";
$result = $conn->query($sql);
$book = $result->fetch_assoc();

if (!$book) {
    header('Location: manage_books.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];
    $isbn = $_POST['isbn'];
    $published_year = $_POST['published_year'];
    $status = $_POST['status'];
    $quantity = $_POST['quantity'];
    $borrow_duration = $_POST['borrow_duration']; // Lấy số ngày mượn từ form

    // Xử lý upload hình ảnh bìa sách
    $cover_image = $book['cover_image']; // Giữ nguyên hình ảnh cũ nếu không upload mới
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($_FILES['cover_image']['name']);
        if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
            $cover_image = basename($_FILES['cover_image']['name']);
        }
    }

    // Cập nhật thông tin sách trong database
    $sql = "UPDATE books 
            SET title = '$title', 
                author = '$author', 
                genre = '$genre', 
                description = '$description', 
                isbn = '$isbn', 
                published_year = '$published_year', 
                status = '$status', 
                cover_image = '$cover_image', 
                quantity = '$quantity', 
                borrow_duration = '$borrow_duration' 
            WHERE id = $book_id";
    if ($conn->query($sql) === TRUE) {
        $success = "Book updated successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - Admin</title>
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
        <h1 class="text-center mb-4">Edit Book</h1>
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
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $book['title']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control" id="author" name="author" value="<?php echo $book['author']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="genre" class="form-label">Genre</label>
                <input type="text" class="form-control" id="genre" name="genre" value="<?php echo $book['genre']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $book['description']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control" id="isbn" name="isbn" value="<?php echo $book['isbn']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="published_year" class="form-label">Published Year</label>
                <input type="number" class="form-control" id="published_year" name="published_year" value="<?php echo $book['published_year']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $book['quantity']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="borrow_duration" class="form-label">Borrow Duration (Days)</label>
                <input type="number" class="form-control" id="borrow_duration" name="borrow_duration" value="<?php echo $book['borrow_duration']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="available" <?php echo $book['status'] == 'available' ? 'selected' : ''; ?>>Available</option>
                    <option value="borrowed" <?php echo $book['status'] == 'borrowed' ? 'selected' : ''; ?>>Borrowed</option>
                    <option value="reserved" <?php echo $book['status'] == 'reserved' ? 'selected' : ''; ?>>Reserved</option>
                    <option value="lost" <?php echo $book['status'] == 'lost' ? 'selected' : ''; ?>>Lost</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="cover_image" class="form-label">Cover Image</label>
                <input type="file" class="form-control" id="cover_image" name="cover_image">
                <small class="text-muted">Leave blank to keep the current image.</small>
                <?php if ($book['cover_image']): ?>
                    <div class="mt-2">
                        <img src="../assets/images/<?php echo $book['cover_image']; ?>" alt="Current Cover" style="max-width: 200px;">
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update Book</button>
        </form>
    </div>

    <!-- Footer -->
    <?php include_once '../includes/footer.php'; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>