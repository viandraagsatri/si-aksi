<?php
require_once '../../config/auth_check.php';
require_once '../../config/database.php';
require_once '../../models/Admin.php';
checkLogin(); checkAdmin();

$database = new Database();
$db = $database->getConnection();
$adminModel = new Admin($db);
$results = $adminModel->getUserResults();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Hasil Kuis | SI-AKSI</title>
    <link rel="stylesheet" href="../../public/css/global.css">
    <link rel="stylesheet" href="../../public/css/dashboard-admin.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Monitoring Hasil Skor Kuis Pengguna</h2>
        <a href="dashboard.php">← Kembali ke Dashboard</a>
        <br><br>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Kategori Kuis</th>
                        <th>Nilai Skor</th>
                        <th>Tanggal Pengisian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($results)): ?>
                        <tr><td colspan="5" style="text-align:center;">Belum ada pengguna yang menyelesaikan kuis.</td></tr>
                    <?php else: ?>
                        <?php foreach($results as $r): ?>
                        <tr>
                            <td><?= htmlspecialchars($r['fullname']); ?></td>
                            <td><?= htmlspecialchars($r['email']); ?></td>
                            <td><span class="status-badge status-active" style="background:#e8f5e9; color:#2e7d32;"><?= htmlspecialchars($r['category_name']); ?></span></td>
                            <td><b><?= $r['score']; ?></b> benar dari <?= $r['total_questions']; ?> soal</td>
                            <td><?= $r['created_at']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>