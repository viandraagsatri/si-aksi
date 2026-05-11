<?php 
require_once '../config/auth_check.php';
checkLogin(); 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SI-AKSI</title>
    <link rel="stylesheet" href="../public/css/global.css">
    <link rel="stylesheet" href="../public/css/dashboard.css">
</head>
<body>
    <nav class="navbar">
        <a href="dashboard-user.php" class="nav-brand">SI-AKSI</a>
        <div class="nav-auth">
            <span>Halo, <?php echo $_SESSION['fullname']; ?></span>
            <a href="../controllers/process.php?action=logout" class="btn-nav-login">Logout</a>
        </div>
    </nav>
    </body>
</html>