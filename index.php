<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI-AKSI | Literasi Teknologi Interaktif</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>

    <nav class="landing-navbar">
        <a href="index.php" class="landing-brand">SI-AKSI</a>

        <div class="landing-nav-links">
            <a href="views/about.php">About</a>
            <a href="views/login.php">Login</a>
            <a href="views/user/register.php" class="landing-nav-btn">Daftar</a>
        </div>
    </nav>

    <main class="landing-page">
        <section class="landing-hero landing-hero-simple">
            <div class="landing-content">
                <span class="landing-badge">Literasi Teknologi Interaktif</span>

                <h1>
                    Kenali Fakta dan Hoaks Teknologi dengan Lebih Kritis
                </h1>

                <p>
                    SI-AKSI membantu pengguna meningkatkan literasi teknologi melalui kuis interaktif
                    berbasis Fakta atau Hoaks, lengkap dengan penjelasan dan sumber referensi.
                </p>

                <div class="landing-actions">
                    <a href="views/user/register.php" class="landing-primary-btn">
                        Mulai Sekarang
                    </a>
                </div>
            </div>
        </section>

        <section class="landing-features landing-features-simple">
            <div class="landing-feature-card">
                <h3>4 Kategori Teknologi</h3>
                <p>Materi kuis mencakup Hardware, Software & Internet, Cyber Security, dan Artificial Intelligence.</p>
            </div>

            <div class="landing-feature-card">
                <h3>Fakta atau Hoaks</h3>
                <p>Setiap soal dibuat dalam bentuk pernyataan agar pengguna belajar membedakan informasi benar dan keliru.</p>
            </div>

            <div class="landing-feature-card">
                <h3>Penjelasan dan Sumber</h3>
                <p>Setiap jawaban dilengkapi penjelasan dan referensi agar pengguna tidak hanya menebak.</p>
            </div>
        </section>
    </main>

</body>
</html>
