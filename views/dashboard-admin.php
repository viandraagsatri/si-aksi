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
    <title>Admin Panel | SI-AKSI</title>
    <link rel="stylesheet" href="../public/css/global.css">
    <link rel="stylesheet" href="../public/css/dashboard.css">
</head>
<body>
    <nav class="navbar">
        <a href="dashboard-admin.php" class="nav-brand">SI-AKSI Admin</a>
        <div class="nav-auth">
            <span>Admin: <?php echo $_SESSION['fullname']; ?></span>
            <a href="../controllers/process.php?action=logout" class="btn-nav-login">Logout</a>
        </div>
    </nav>

    <div class="dashboard-container">
        <h2>Manajemen Pengguna</h2>
        
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
                        <td><?php echo $user['fullname']; ?></td>
                        <td><?php echo $user['email']; ?></td>
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