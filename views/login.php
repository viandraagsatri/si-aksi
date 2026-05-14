<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SI-AKSI</title>
    <link rel="stylesheet" href="../public/css/global.css">
    <link rel="stylesheet" href="../public/css/auth.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="nav-brand">SI-AKSI</a>  
        <div class="nav-auth">
            <a href="about.php" class="btn-nav">About SI-AKSI</a>
            <a href="login.php" class="btn-nav">Login</a>
            <a href="register.php" class="btn-nav">Daftar</a>
        </div>
    </nav>

    <div class="login-container">
        <div class="login-card">
            <h2>Selamat Datang</h2>
            <p class="subtitle">Sistem Informasi Edukasi & Literasi Teknologi Interaktif</p>

            <div class="alert-box">
                <strong>Catatan:</strong> Akses login hanya tersedia untuk akun yang telah diverifikasi dan diaktivasi secara manual oleh Administrator.
            </div>

            <form id="form-login" action="../controllers/process.php?action=login" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email" required>
                </div>
                <div class="input-group">
                    <label for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" required>
                </div>
                <button type="submit" class="login-btn">Masuk ke SI-AKSI</button>
            </form>
            
            <p class="register-link">Belum memiliki akun? <a href="register.php">Daftar di sini</a></p>
        </div>
    </div>

    </body>
</html>