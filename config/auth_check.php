<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$timeout = 300;

if (isset($_SESSION['last_activity'])) {
    if ((time() - $_SESSION['last_activity']) > $timeout) {
        session_unset();
        session_destroy();

        echo "<script>
            alert('Session telah habis. Silakan login kembali.');
            window.location.href='../login.php';
        </script>";
        exit();
    }
}

$_SESSION['last_activity'] = time();

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }
}

function checkAdmin() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../views/user/dashboard.php");
        exit();
    }
}
?>
