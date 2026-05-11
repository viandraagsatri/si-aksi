<?php
require_once 'AuthController.php';

$auth = new AuthController();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'login') {
        $loginSuccess = $auth->login();
        if ($loginSuccess) {
            if ($_SESSION['role'] == 'admin') {
                header("Location: ../views/dashboard-admin.php");
            } else {
                header("Location: ../views/dashboard-user.php");
            }
            exit();
        } else {
            header("Location: ../views/login.php?error=1");
            exit();
        }
    } elseif ($_GET['action'] == 'register') {
        $auth->register();
    } elseif ($_GET['action'] == 'approve') {
        $auth->approve();  
    } elseif ($_GET['action'] == 'logout') {
        $auth->logout();
        header("Location: ../views/login.php");
        exit();
    }
}
?>