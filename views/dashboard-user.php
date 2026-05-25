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
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="user-dashboard">
    <nav class="navbar">
        <a href="dashboard-user.php" class="nav-brand">SI-AKSI</a>
        <ul class="nav-links">
            <li><a href="about.php" class="btn-nav">About SI-AKSI</a></li>
            <li>
                <a href="#" class="btn-nav">Kategori ▾</a>
                <div class="dropdown-content">
                    <a href="quiz.php?kategori=hardware">Hardware</a>
                    <a href="quiz.php?kategori=software">Software & Internet</a>
                    <a href="quiz.php?kategori=cybersecurity">Cyber Security</a>
                    <a href="quiz.php?kategori=ai">Artificial Intelligence</a>
                </div>
            </li>
        </ul>
        <div class="nav-auth">
            <span style="font-weight: 600; color: var(--text-dark); margin-right: 15px;">Halo, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</span>
            <a href="../controllers/process.php?action=logout" class="btn-nav">Logout</a>
        </div>
    </nav>

    <main class="dashboard-container">
        <div class="welcome-banner">
            <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
            <p>Pilih kategori di bawah ini untuk memulai kuis dan tingkatkan literasi digitalmu.</p>
        </div>

        <div class="category-grid">
            <a href="quiz.php?kategori=hardware" class="category-card">
                <div class="card-icon">
                    <i class="fas fa-microchip"></i>
                </div>
                <h3>Hardware</h3>
                <p>Uji pemahamanmu tentang komponen fisik dan spesifikasi perangkat keras komputer.</p>
            </a>

            <a href="quiz.php?kategori=software" class="category-card">
                <div class="card-icon">
                    <i class="fas fa-code"></i>
                </div>
                <h3>Software & Internet</h3>
                <p>Eksplorasi pengetahuan seputar perangkat lunak, aplikasi, dan jaringan internet global.</p>
            </a>

            <a href="quiz.php?kategori=cybersecurity" class="category-card">
                <div class="card-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3>Cyber Security</h3>
                <p>Kenali berbagai ancaman digital, malware, dan bagaimana cara melindungi data pribadimu.</p>
            </a>

            <a href="quiz.php?kategori=ai" class="category-card">
                <div class="card-icon">
                    <i class="fas fa-robot"></i>
                </div>
                <h3>Artificial Intelligence</h3>
                <p>Pelajari perkembangan kecerdasan buatan, machine learning, dan dampaknya saat ini.</p>
            </a>
        </div>
    </main>
</body>
</html>
