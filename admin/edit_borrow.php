<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';
redirectIfNotAdmin();

if (!isset($_GET['id'])) {
    header('Location: manage_borrow.php');
    exit();
}

$borrow_id = $_GET['id'];

// Lấy thông tin mượn sách từ database
$sql = "SELECT borrows.*, users.username, books.title, books.id AS book_id 
        FROM borrows 
        JOIN users ON borrows.user_id = users.id 
        JOIN books ON borrows.book_id = books.id 
        WHERE borrows.id = $borrow_id";
$result = $conn->query($sql);
$borrow = $result->fetch_assoc();

if (!$borrow) {
    header('Location: manage_borrow.php');
    exit();
}

// Lấy trạng thái hiện tại của mượn sách
$current_status = $borrow['status'];

// Xử lý cập nhật thông tin mượn sách
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $borrow_date = $_POST['borrow_date'];
    $return_date = $_POST['return_date'];
    $status = $_POST['status'];

    // Kiểm tra nếu trạng thái thay đổi từ "borrowed" sang "returned"
    if ($current_status == 'borrowed' && $status == 'returned') {
        // Tăng số lượng sách lên 1
        $book_id = $borrow['book_id'];
        $sql = "UPDATE books SET quantity = quantity + 1 WHERE id = $book_id";
        if ($conn->query($sql) !== TRUE) {
            $error = "Error updating book quantity: " . $conn->error;
        }
    }

    // Cập nhật thông tin mượn sách
    $sql = "UPDATE borrows 
            SET borrow_date = '$borrow_date', 
                return_date = '$return_date', 
                status = '$status' 
            WHERE id = $borrow_id";
    if ($conn->query($sql) === TRUE) {
        $success = "Borrow record updated successfully!";
    } else {
        $error = "Error updating borrow record: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Borrow - Admin</title>
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
        <h1 class="text-center mb-4">Edit Borrow Record</h1>
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
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">User</label>
                <input type="text" class="form-control" id="username" value="<?php echo $borrow['username']; ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Book</label>
                <input type="text" class="form-control" id="title" value="<?php echo $borrow['title']; ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="borrow_date" class="form-label">Borrow Date</label>
                <input type="date" class="form-control" id="borrow_date" name="borrow_date" value="<?php echo $borrow['borrow_date']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="return_date" class="form-label">Return Date</label>
                <input type="date" class="form-control" id="return_date" name="return_date" value="<?php echo $borrow['return_date']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="borrowed" <?php echo $borrow['status'] == 'borrowed' ? 'selected' : ''; ?>>Borrowed</option>
                    <option value="returned" <?php echo $borrow['status'] == 'returned' ? 'selected' : ''; ?>>Returned</option>
                    <option value="overdue" <?php echo $borrow['status'] == 'overdue' ? 'selected' : ''; ?>>Overdue</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Borrow Record</button>
        </form>
    </div>

    <!-- Footer -->
    <?php include_once '../includes/footer.php'; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>