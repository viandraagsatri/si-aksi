<?php
require_once '../../config/auth_check.php';
require_once '../../config/database.php';

checkLogin();
checkAdmin();

$database = new Database();
$db = $database->getConnection();

$editCategory = null;

if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $editCategory = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $nama_kategori = trim($_POST['name']);

    if (!empty($nama_kategori)) {
        $stmt = $db->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->execute([$nama_kategori]);

        echo "<script>alert('Kategori berhasil ditambahkan!'); window.location.href='kategori.php';</script>";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_category'])) {
    $id = $_POST['id'] ?? '';
    $nama_kategori = trim($_POST['name']);

    if (!empty($id) && !empty($nama_kategori)) {
        $stmt = $db->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->execute([$nama_kategori, $id]);

        echo "<script>alert('Kategori berhasil diperbarui!'); window.location.href='kategori.php';</script>";
        exit();
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$_GET['id']]);

    echo "<script>alert('Kategori berhasil dihapus!'); window.location.href='kategori.php';</script>";
    exit();
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
</head>
<body class="admin-dashboard">

    <div class="dashboard-container">
        <a href="dashboard.php" class="back-btn">
            ← Kembali ke Dashboard
        </a>

        <h2>CRUD Kategori Kuis</h2>
        
        <div class="table-container">
            <form action="kategori.php" method="POST">
                <?php if ($editCategory): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editCategory['id']); ?>">
                <?php endif; ?>

                <label for="name">Nama Kategori:</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="<?php echo htmlspecialchars($editCategory['name'] ?? ''); ?>"
                    required
                >

                <?php if ($editCategory): ?>
                    <button type="submit" name="update_category" class="btn-submit-custom">
                        Update Kategori
                    </button>

                    <a href="kategori.php" class="btn-action-delete">
                        Batal
                    </a>
                <?php else: ?>
                    <button type="submit" name="add_category" class="btn-submit-custom">
                        Tambah Kategori
                    </button>
                <?php endif; ?>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kategori</th>
                        <th>Slug URL (Otomatis)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="4">Belum ada kategori.</td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach($categories as $cat): 
                        $slug_otomatis = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $cat['name'])));
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cat['id']); ?></td>

                            <td><?php echo htmlspecialchars($cat['name']); ?></td>

                            <td>quiz.php?kategori=<?php echo htmlspecialchars($slug_otomatis); ?></td>

                            <td>
                                <div class="table-action">
                                    <a
                                        href="kategori.php?action=edit&id=<?php echo $cat['id']; ?>"
                                        class="btn-action-edit"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="kategori.php?action=delete&id=<?php echo $cat['id']; ?>"
                                        class="btn-action-delete"
                                        onclick="return confirm('Yakin ingin menghapus kategori ini?');"
                                    >
                                        Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
