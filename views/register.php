<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | SI-AKSI</title>
    <link rel="stylesheet" href="../public/css/global.css">
    <link rel="stylesheet" href="../public/css/auth.css">
</head>
<body>
    <nav class="navbar">
        <a href="login.php" class="nav-brand">SI-AKSI</a>
        <ul class="nav-links">
            <li><a href="#">About SI-AKSI</a></li>
            <li><a href="#">Meet Our Team</a></li>
        </ul>
        <div class="nav-auth">
            <a href="login.php" class="btn-nav-login">Login</a>
            <a href="register.php" class="btn-nav-register">Daftar</a>
        </div>
    </nav>

    <div class="register-container">
        <div class="register-card">
            <h2>Buat Akun Baru</h2>
            <p class="subtitle">Bergabunglah bersama komunitas literasi teknologi kami.</p>

            <form action="../controllers/process.php?action=register" method="POST">
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
            
            <p class="register-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
        </div>
    </div>
</body>
</html>