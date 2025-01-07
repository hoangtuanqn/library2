<?php
include_once '../includes/auth.php';
include_once '../includes/db.php';
redirectIfNotAdmin();

if (!isset($_GET['id'])) {
    header('Location: manage_users.php');
    exit();
}

$user_id = $_GET['id'];

// Cập nhật trạng thái thành 'active'
$sql = "UPDATE users SET status = 'active' WHERE id = $user_id";
if ($conn->query($sql) === TRUE) {
    header('Location: manage_users.php?success=User unlocked successfully');
} else {
    header('Location: manage_users.php?error=Error unlocking user');
}
exit();
