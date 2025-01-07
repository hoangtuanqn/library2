<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';
redirectIfNotLoggedIn();

if (!isset($_GET['book_id'])) {
    header('Location: index.php');
    exit();
}

$book_id = $_GET['book_id'];
$user_id = $_SESSION['user_id'];

// Lấy thông tin sách
$sql = "SELECT * FROM books WHERE id = $book_id";
$result = $conn->query($sql);
$book = $result->fetch_assoc();

if (!$book || $book['quantity'] <= 0) {
    header('Location: index.php');
    exit();
}

// Kiểm tra xem người dùng đã mượn sách này chưa
$sql = "SELECT * FROM borrows WHERE user_id = $user_id AND book_id = $book_id AND status = 'borrowed'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    header('Location: book_detail.php?id=' . $book_id . '&error=You have already borrowed this book.');
    exit();
}

// Tính toán ngày trả (Return Date) dựa trên Borrow Duration của sách
$borrow_date = date('Y-m-d');
$borrow_duration = $book['borrow_duration']; // Lấy số ngày mượn từ thông tin sách
$return_date = date('Y-m-d', strtotime("+$borrow_duration days", strtotime($borrow_date))); // Tính ngày trả

// Thêm vào bảng borrows
$sql = "INSERT INTO borrows (user_id, book_id, borrow_date, return_date, status, borrow_duration) 
        VALUES ('$user_id', '$book_id', '$borrow_date', '$return_date', 'borrowed', '$borrow_duration')";
if ($conn->query($sql) === TRUE) {
    // Giảm số lượng sách
    $new_quantity = $book['quantity'] - 1;
    $sql = "UPDATE books SET quantity = $new_quantity WHERE id = $book_id";
    $conn->query($sql);

    header('Location: borrow_management.php?success=Book borrowed successfully!');
    exit();
} else {
    header('Location: book_detail.php?id=' . $book_id . '&error=Error borrowing book.');
    exit();
}
