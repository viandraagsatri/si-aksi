<?php
require_once '../config/auth_check.php';
checkLogin();
checkAdmin();
include '../koneksi.php';

$id = $_GET['id'] ?? 0;

mysqli_query($koneksi, "UPDATE quiz_results SET status='Valid' WHERE id='$id'");

header('Location: ../views/dashboard-admin.php');
exit;
