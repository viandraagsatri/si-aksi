<?php 
require_once '../config/auth_check.php';
require_once '../config/database.php';
require_once '../models/User.php';

checkLogin(); 
checkAdmin();

$database = new Database();
$db = $database->getConnection();
$userModel = new User($db);
$users = $userModel->getAllUsers();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SI-AKSI</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .nav-links-custom { display: flex; gap: 15px; align-items: center; }
        .nav-links-custom a { text-decoration: none; color: #7a4b4b; font-weight: 600; font-size: 14px; transition: all 0.3s; }
        .nav-links-custom a:hover { color: #b87373; }
        .btn-logout-custom { background: #7a4b4b; color: white !important; padding: 6px 15px; border-radius: 20px; }
        .btn-logout-custom:hover { background: #5c3838; }
    </style>
</head>
<body class="admin-dashboard">
    <nav class="navbar" style="display: flex; justify-content: space-between; align-items: center; padding: 15px 40px; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <a href="dashboard-admin.php" class="nav-brand" style="font-weight: bold; font-size: 20px; color: #7a4b4b; text-decoration: none;">SI-AKSI</a>
        
        <div class="nav-links-custom">
            <span style="color: #555; margin-right: 10px;">Admin: <strong><?php echo htmlspecialchars($_SESSION['fullname']); ?>!</strong></span>
            <a href="admin/kategori.php">📁 CRUD Kategori</a>
            <a href="admin/soal.php">📝 CRUD Soal Kuis</a>
            <a href="about.php">About SI-AKSI</a>
            <a href="../controllers/process.php?action=logout" class="btn-logout-custom">Logout</a>
        </div>
    </nav>

    <div class="dashboard-container" style="padding: 40px;">
        <h2 style="color: #7a4b4b;">Manajemen Pengguna</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Pengguna</h3>
                <p><?php echo count($users); ?></p>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo strtoupper($user['role']); ?></td>
                        <td>
                            <span class="status-badge <?php echo $user['is_verified'] ? 'status-active' : 'status-pending'; ?>">
                                <?php echo $user['is_verified'] ? 'Aktif' : 'Menunggu'; ?>
                            </span>
                        </td>
                        <td>
                            <?php if(!$user['is_verified'] && $user['role'] !== 'admin'): ?>
                                <a href="../controllers/process.php?action=approve&id=<?php echo $user['id']; ?>" class="btn-approve">Approve</a>
                            <?php else: ?>
                                <span style="color: #ccc;">No Action</span>
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