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
    <style>
        body { background-color: #fcedec; font-family: 'Poppins', sans-serif; }
        .back-link { color: #7a4b4b; text-decoration: none; font-weight: bold; font-size: 14px; display: inline-block; margin-bottom: 20px; }
        .back-link:hover { color: #b87373; }
        .score-badge { background: #7a4b4b; color: white; padding: 4px 12px; border-radius: 12px; font-weight: bold; }
    </style>
</head>
<body class="admin-dashboard">

    <div class="dashboard-container" style="padding: 40px; max-width: 1100px; margin: 0 auto;">
        <a href="../dashboard-admin.php" class="back-link">← Kembali ke Dashboard</a>
        
        <h2 style="color: #7a4b4b; margin-top: 10px; margin-bottom: 25px;">📊 Monitoring Hasil Kuis User</h2>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Nama Pengguna</th>
                        <th width="20%">Email</th>
                        <th width="20%">Kategori Kuis</th>
                        <th width="15%">Skor (Benar/Total)</th>
                        <th width="15%">Waktu Ujian</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($results)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #bbb; padding: 20px;">
                                Belum ada riwayat pengerjaan kuis dari user.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($results as $res): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td style="font-weight: 600; color: #555;">
                                <?php echo htmlspecialchars($res['fullname'] ?? 'User Terhapus'); ?>
                            </td>
                            <td style="color: #666;"><?php echo htmlspecialchars($res['email'] ?? '-'); ?></td>
                            <td>
                                <span style="color: #7a4b4b; font-weight: 500;">
                                    <?php echo htmlspecialchars($res['category_name'] ?? 'General'); ?>
                                </span>
                            </td>
                            <td>
                                <span class="score-badge">
                                    <?php echo ($res['score'] ?? '0') . ' / ' . ($res['total_questions'] ?? '0'); ?>
                                 </span>
                            </td>
                            <td style="color: #888; font-size: 13px;">
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