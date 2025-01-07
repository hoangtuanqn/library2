<?php
// Bắt đầu session nếu chưa được bắt đầu
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Kiểm tra xem người dùng đã đăng nhập chưa.
 * @return bool Trả về true nếu đã đăng nhập, ngược lại trả về false.
 */
if (!function_exists('isLoggedIn')) {
    function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }
}

/**
 * Kiểm tra xem người dùng có phải là admin không.
 * @return bool Trả về true nếu là admin, ngược lại trả về false.
 */
if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
}

/**
 * Chuyển hướng người dùng nếu chưa đăng nhập.
 */
if (!function_exists('redirectIfNotLoggedIn')) {
    function redirectIfNotLoggedIn()
    {
        if (!isLoggedIn()) {
            header('Location: /login.php');
            exit();
        }
    }
}

/**
 * Chuyển hướng người dùng nếu không phải là admin.
 */
if (!function_exists('redirectIfNotAdmin')) {
    function redirectIfNotAdmin()
    {
        if (!isAdmin()) {
            header('Location: /index.php');
            exit();
        }
    }
}

/**
 * Xử lý đăng nhập.
 */
// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
//     include_once 'db.php';

//     $username = $_POST['username'];
//     $password = $_POST['password'];

//     // Truy vấn để lấy thông tin người dùng
//     $sql = "SELECT * FROM users WHERE username = '$username'";
//     $result = $conn->query($sql);

//     if ($result->num_rows > 0) {
//         $user = $result->fetch_assoc();

//         // Kiểm tra trạng thái tài khoản
//         if ($user['status'] == 'locked') {
//             // Tài khoản bị khóa, hiển thị thông báo lỗi
//             $_SESSION['error'] = "Your account is locked. Please contact the administrator.";
//             header('Location: /login.php');
//             exit();
//         } elseif ($password === $user['password']) { // So sánh mật khẩu (không mã hóa)
//             // Lưu thông tin người dùng vào session
//             $_SESSION['user_id'] = $user['id'];
//             $_SESSION['role'] = $user['role'];
//             $_SESSION['username'] = $user['username'];
//             header('Location: index.php');
//             exit();
//         } else {
//             // Mật khẩu không đúng
//             $_SESSION['error'] = "Invalid password";
//             header('Location: /login.php');
//             exit();
//         }
//     } else {
//         // Người dùng không tồn tại
//         $_SESSION['error'] = "User not found";
//         header('Location: /login.php');
//         exit();
//     }
// }
