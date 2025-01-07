<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';
redirectIfNotAdmin();

if (!isset($_GET['id'])) {
    header('Location: manage_users.php');
    exit();
}

$user_id = $_GET['id'];

// Cập nhật trạng thái thành 'locked'
$sql = "UPDATE users SET status = 'locked' WHERE id = $user_id";
if ($conn->query($sql) === TRUE) {
    header('Location: manage_users.php?success=User locked successfully');
} else {
    header('Location: manage_users.php?error=Error locking user');
}
exit();
