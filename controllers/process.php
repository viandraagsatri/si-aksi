<?php
session_start();

require_once 'AuthController.php';

$auth = new AuthController();

if (isset($_GET['action'])) {

    if ($_GET['action'] == 'login') {
        $auth->login();

    } elseif ($_GET['action'] == 'register') {
        $auth->register();

    } elseif ($_GET['action'] == 'approve') {
        $auth->approve();

    } elseif ($_GET['action'] == 'logout') {
        $auth->logout();

    } else {
        header("Location: ../views/login.php");
        exit();
    }

} else {
    header("Location: ../views/login.php");
    exit();
}
?>
