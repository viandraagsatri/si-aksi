<?php
require_once '../../config/auth_check.php';
require_once '../../config/database.php';
checkLogin();
checkAdmin();

$database = new Database();
$db = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $nama_kategori = trim($_POST['name']);
    if (!empty($nama_kategori)) {
        $stmt = $db->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$nama_kategori]);
        echo "<script>alert('Kategori berhasil ditambahkan!'); window.location.href='kategori.php';</script>";
        exit;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    echo "<script>alert('Kategori berhasil dihapus!'); window.location.href='kategori.php';</script>";
    exit;
}

$query = $db->query("SELECT * FROM categories ORDER BY id DESC");
$categories = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Kategori Kuis | SI-AKSI</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        /* Penyelarasan tema warna earth-tone */
        body { background-color: #fcedec; font-family: 'Poppins', sans-serif; }
        .back-link { color: #7a4b4b; text-decoration: none; font-weight: bold; font-size: 14px; display: inline-block; mb: 15px; }
        .back-link:hover { color: #b87373; }
        .btn-submit-custom { background-color: #7a4b4b; color: white; border: none; padding: 8px 20px; border-radius: 5px; cursor: pointer; font-weight: 600; }
        .btn-submit-custom:hover { background-color: #5c3838; }
        .action-link { text-decoration: none; color: #7a4b4b; font-weight: 600; margin: 0 5px; }
        .action-link:hover { color: #b87373; }
    </style>
</head>
<body class="admin-dashboard">

    <div class="dashboard-container" style="padding: 40px; max-width: 1100px; margin: 0 auto;">
        <a href="../dashboard-admin.php" class="back-link">← Kembali ke Dashboard</a>
        
        <h2 style="color: #7a4b4b; margin-top: 10px; margin-bottom: 25px;">CRUD Kategori Kuis</h2>
        
        <div class="table-container" style="padding: 20px; margin-bottom: 30px; background: white; border-radius: 10px;">
            <form action="kategori.php" method="POST" style="display: flex; gap: 15px; align-items: center;">
                <label for="name" style="font-weight: 600; color: #555;">Nama Kategori:</label>
                <input type="text" id="name" name="name" required style="padding: 8px; border: 1px solid #ccc; border-radius: 5px; width: 300px;">
                <button type="submit" name="add_category" class="btn-submit-custom">Tambah Kategori</button>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="40%">Nama Kategori</th>
                        <th width="30%">Slug URL (Otomatis)</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr><td colspan="4" style="text-center; color: #ccc;">Belum ada kategori.</td></tr>
                    <?php endif; ?>

                    <?php foreach($categories as $cat): 
                        $slug_otomatis = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $cat['name'])));
                    ?>
                    <tr>
                        <td><?php echo $cat['id']; ?></td>
                        <td style="font-weight: 600; color: #555;"><?php echo htmlspecialchars($cat['name']); ?></td>
                        <td style="color: #888; font-style: italic;">quiz.php?kategori=<?php echo $slug_otomatis; ?></td>
                        <td>
                            <a href="kategori.php?action=delete&id=<?php echo $cat['id']; ?>" class="action-link" onclick="return confirm('Yakin ingin menghapus kategori ini?');" style="color: #c0392b;">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>