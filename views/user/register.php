<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: user/dashboard.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | SI-AKSI</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <nav class="navbar">
        <a href="../login.php" class="nav-brand">SI-AKSI</a>
        <div class="nav-auth">
            <a href="../about.php" class="btn-nav">About SI-AKSI</a>
            <a href="../login.php" class="btn-nav">Login</a>
            <a href="register.php" class="btn-nav">Daftar</a>
        </div>
    </nav>

    <div class="register-container">
        <div class="register-card">
            <h2>Buat Akun Baru</h2>
            <p class="subtitle">Bergabunglah bersama komunitas literasi teknologi kami.</p>

            <form action="../../controllers/process.php?action=register" method="POST">
                <div class="input-group">
                    <label for="fullname">Nama Lengkap</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" required>
                </div>
                <div class="input-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Konfirmasi Kata Sandi</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi kata sandi" required>
                </div>
                <button type="submit" class="btn-submit">Daftar Sekarang</button>
            </form>
            
            <p class="register-link">Sudah punya akun? <a href="../login.php">Login di sini</a></p>
        </div>
    </div>
</body>
</html>
