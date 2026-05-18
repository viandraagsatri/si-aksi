<?php
require_once '../../config/auth_check.php';
checkLogin(); checkAdmin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | SI-AKSI</title>
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/dashboard-admin.css">
</head>
<body>
    <nav class="navbar">
        <a href="dashboard.php" class="nav-brand">SI-AKSI Admin Panel</a>
        <div class="nav-auth"><a href="../../controllers/process.php?action=logout" class="btn-nav">Logout</a></div>
    </nav>
    <div class="dashboard-container">
        <h2>Selamat Datang di Panel Kontrol Admin</h2>
        <p>Silakan pilih menu manajemen kontrol di bawah ini:</p>
        <br>
        <div class="stats-grid">
            <div class="stat-card" onclick="window.location.href='users.php'" style="cursor:pointer;">
                <h3>Verifikasi & Manajemen User</h3>
                <p>👤 Kelola</p>
            </div>
            <div class="stat-card" onclick="window.location.href='kategori.php'" style="cursor:pointer;">
                <h3>CRUD Kategori Kuis</h3>
                <p>📁 Kelola</p>
            </div>
            <div class="stat-card" onclick="window.location.href='soal.php'" style="cursor:pointer;">
                <h3>CRUD Soal Kuis</h3>
                <p>📝 Kelola</p>
            </div>
            <div class="stat-card" onclick="window.location.href='hasil-user.php'" style="cursor:pointer;">
                <h3>Monitoring Hasil User</h3>
                <p>📊 Lihat</p>
            </div>
        </div>
    </div>
</body>
</html>