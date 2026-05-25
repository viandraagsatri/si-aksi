<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About SI-AKSI</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <body class="about-page">
    <nav class="navbar">
        <a href="../index.php" class="nav-brand">SI-AKSI</a>
        
        <ul class="nav-links">
            <li><a href="about.php" class="btn-nav">About SI-AKSI</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <a href="dashboard-admin.php" class="btn-nav">Dashboard</a>
                    <?php else: ?>
                        <a href="dashboard-user.php" class="btn-nav">Dashboard</a>
                    <?php endif; ?>
                </li>
            <?php endif; ?>
        </ul>

        <div class="nav-auth">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="../controllers/process.php?action=logout" class="btn-nav">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn-nav">Login</a>
                <a href="register.php" class="btn-nav">Daftar</a>
            <?php endif; ?>
        </div>
    </nav>

    <header class="about-hero">
        <div class="hero-text">
            <h1>Mengenal SI-AKSI</h1>
            <p>Sistem Informasi Edukasi & Literasi Teknologi Interaktif</p>
        </div>
    </header>

    <main class="content-wrapper">
        <section class="description-section">
            <div class="alert-box">
                <strong>Visi Kami:</strong> Meningkatkan kemampuan berpikir kritis masyarakat dalam menyaring informasi digital, khususnya dalam membedakan antara fakta dan hoaks di bidang teknologi informasi.
            </div>
            
            <div class="main-card">
                <h2>Apa itu SI-AKSI?</h2>
                <p>
                    SI-AKSI adalah platform edukasi berbasis web yang dirancang untuk menjawab tantangan rendahnya literasi digital di era informasi yang pesat. Kami menyajikan kuis interaktif dengan konsep <strong>Fakta dan Hoaks</strong> untuk menguji sekaligus menambah wawasan Anda mengenai perkembangan teknologi modern.
                </p>
                <p>
                    Melalui pendekatan <em>gamifikasi</em>, belajar literasi digital tidak lagi membosankan seperti membaca teks panjang yang monoton. Di SI-AKSI, Anda akan mendapatkan umpan balik langsung berupa penjelasan teknis untuk setiap jawaban kuis yang Anda kerjakan.
                </p>
            </div>
        </section>

        <section class="categories-section">
            <h2 class="section-title">4 Fokus Literasi Teknologi</h2>
            <div class="category-grid">
                <div class="category-card">
                    <i class="fas fa-microchip"></i>
                    <h3>Hardware</h3>
                    <p>Memahami komponen fisik komputer agar tidak terjebak informasi keliru mengenai perangkat keras.</p>
                </div>
                <div class="category-card">
                    <i class="fas fa-code"></i>
                    <h3>Software & Internet</h3>
                    <p>Eksplorasi dunia perangkat lunak dan jaringan internet yang aman dan produktif.</p>
                </div>
                <div class="category-card">
                    <i class="fas fa-user-shield"></i>
                    <h3>Cyber Security</h3>
                    <p>Meningkatkan kesadaran akan ancaman digital seperti phishing dan perlindungan data pribadi.</p>
                </div>
                <div class="category-card">
                    <i class="fas fa-robot"></i>
                    <h3>Artificial Intelligence</h3>
                    <p>Mengenal teknologi kecerdasan buatan dan bagaimana menyikapinya secara bijak.</p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
