<?php
require_once '../../config/auth_check.php';
require_once '../../config/database.php';
require_once '../../models/Admin.php';
checkLogin(); checkAdmin();

$database = new Database();
$db = $database->getConnection();
$adminModel = new Admin($db);
$users = $adminModel->getAllUsers();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi User | SI-AKSI</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body class="admin-dashboard">
    <div class="dashboard-container">
        <h2>Manajemen & Verifikasi Pengguna</h2>
        <a href="dashboard.php" class="back-btn">
            ← Kembali ke Dashboard
        </a>
        <br><br>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Status Akun</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['fullname']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="status-badge <?= $user['is_verified'] ? 'status-active' : 'status-pending'; ?>">
                                <?= $user['is_verified'] ? 'Aktif' : 'Pending'; ?>
                            </span>
                        </td>
                        <td>
                            <?php if(!$user['is_verified']): ?>
                                <a href="../../controllers/AdminController.php?action=approve&id=<?= $user['id']; ?>" class="btn-approve">Approve Akun</a>
                            <?php else: ?>
                                <span>✓ Terverifikasi</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
