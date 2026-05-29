<?php
require_once '../../config/auth_check.php';
require_once '../../config/database.php';
require_once '../../controllers/AdminController.php';

checkLogin();
checkAdmin();

$results = $adminCtrl->getQuizResults();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Hasil User | SI-AKSI</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body class="admin-dashboard">

    <div class="dashboard-container">
        <h2>📊 Monitoring Hasil Kuis User</h2>
        
        <a href="dashboard.php" class="back-btn">
            ← Kembali ke Dashboard
        </a>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Kategori Kuis</th>
                        <th>Skor (Benar/Total)</th>
                        <th>Waktu Ujian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($results)): ?>
                        <tr>
                            <td colspan="6">
                                Belum ada riwayat pengerjaan kuis dari user.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($results as $res): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <?php echo htmlspecialchars($res['fullname'] ?? 'User Terhapus'); ?>
                            </td>
                            <td><?php echo htmlspecialchars($res['email'] ?? '-'); ?></td>
                            <td>
                                <span>
                                    <?php echo htmlspecialchars($res['category_name'] ?? 'General'); ?>
                                </span>
                            </td>
                            <td>
                                <span class="score-badge">
                                    <?php echo ($res['score'] ?? '0') . ' / ' . ($res['total_questions'] ?? '0'); ?>
                                 </span>
                            </td>
                            <td>
                                <?php echo date('d/m/Y H:i', strtotime($res['created_at'])); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>