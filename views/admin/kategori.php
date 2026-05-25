<?php
require_once '../../config/auth_check.php';
require_once '../../config/database.php';
require_once '../../models/Category.php';
checkLogin(); checkAdmin();

$database = new Database();
$db = $database->getConnection();
$catModel = new Category($db);
$categories = $catModel->getAll();

$editId = $_GET['edit_id'] ?? '';
$editName = $_GET['edit_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CRUD Kategori | SI-AKSI</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body class="admin-dashboard">
    <div class="dashboard-container">
        <h2>CRUD Kategori Kuis</h2>
        <a href="dashboard.php">← Kembali ke Dashboard</a>
        <br><br>

        <form action="../../controllers/CategoryController.php" method="POST" style="background:white; padding:20px; border-radius:10px; margin-bottom:20px;">
            <input type="hidden" name="target" value="category">
            <input type="hidden" name="id" value="<?= $editId; ?>">
            <label>Nama Kategori:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($editName); ?>" required style="padding:8px; width:250px;">
            <button type="submit" style="background-color:var(--text-dark); color:white; padding:8px 15px; border:none; border-radius:5px; cursor:pointer;">
                <?= $editId ? 'Update Kategori' : 'Tambah Kategori'; ?>
            </button>
            <?php if($editId): ?> <a href="kategori.php">Batal</a> <?php endif; ?>
        </form>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kategori</th>
                        <th>Slug URL</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categories as $c): ?>
                    <tr>
                        <td><?= $c['id']; ?></td>
                        <td><?= htmlspecialchars($c['name']); ?></td>
                        <td><?= htmlspecialchars($c['slug']); ?></td>
                        <td>
                            <a href="kategori.php?edit_id=<?= $c['id']; ?>&edit_name=<?= urlencode($c['name']); ?>">Edit</a> | 
                            <a href="../../controllers/CategoryController.php?delete=<?= $c['id']; ?>&type=category" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
