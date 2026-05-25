<?php
require_once '../../config/auth_check.php';
require_once '../../config/database.php';
require_once '../../models/Category.php';
checkLogin(); checkAdmin();

$database = new Database();
$db = $database->getConnection();
$catModel = new Category($db);
$categories = $catModel->getAll();

$questions = $db->query("SELECT q.*, c.name as cat_name FROM questions q JOIN categories c ON q.category_id = c.id ORDER BY q.id DESC")->fetchAll(PDO::FETCH_ASSOC);

$editId = $_GET['edit_id'] ?? '';
$eq = ['category_id'=>'', 'difficulty'=>'', 'question'=>'', 'option_a'=>'', 'option_b'=>'', 'correct_answer'=>'', 'explanation'=>''];
if($editId) {
    $stmt = $db->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->execute([$editId]);
    $eq = $stmt->fetch(PDO::FETCH_ASSOC) ?: $eq;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>CRUD Soal Kuis | SI-AKSI</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <body class="admin-dashboard">
    <div class="dashboard-container" style="max-width:1000px;">
        <h2>CRUD Soal Kuis</h2>
        <a href="dashboard.php">← Kembali ke Dashboard</a>
        <br><br>

        <form action="../../controllers/CategoryController.php" method="POST" style="background:white; padding:20px; border-radius:10px; display:flex; flex-direction:column; gap:10px;">
            <input type="hidden" name="target" value="question">
            <input type="hidden" name="id" value="<?= $editId; ?>">
            
            <label>Kategori:</label>
            <select name="category_id" required style="padding:5px;">
                <?php foreach($categories as $c): ?>
                    <option value="<?= $c['id']; ?>"
                        <?= $c['id'] == $eq['category_id'] ? 'selected' : ''; ?>>
                        <?= $c['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Kesulitan (easy/medium/hard):</label>
            <input type="text" name="difficulty" value="<?= $eq['difficulty']; ?>" required style="padding:5px;">

            <label>Pertanyaan Soal:</label>
            <textarea name="question" required style="padding:5px; height:60px;"><?= $eq['question']; ?></textarea>

            <label>Pilihan A:</label>
            <input type="text" name="option_a" value="<?= $eq['option_a']; ?>" required style="padding:5px;">

            <label>Pilihan B:</label>
            <input type="text" name="option_b" value="<?= $eq['option_b']; ?>" required style="padding:5px;">

            <label>Jawaban Benar (A / B):</label>
            <input type="text" name="correct_answer" value="<?= $eq['correct_answer']; ?>" required style="padding:5px; max-width:50px;">

            <label>Penjelasan Materi (Opsional):</label>
            <textarea name="explanation" style="padding:5px;"><?= $eq['explanation']; ?></textarea>

            <button type="submit" style="background-color:var(--text-dark); color:white; padding:10px; border:none; border-radius:5px; cursor:pointer;">Simpan Soal</button>
        </form>

        <br>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Soal</th>
                        <th>A/B</th>
                        <th>Kunci</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($questions as $q): ?>
                    <tr>
                        <td><b><?= htmlspecialchars($q['cat_name']); ?></b></td>
                        <td><?= htmlspecialchars(substr($q['question'], 0, 50)); ?>...</td>
                        <td>A: <?= htmlspecialchars($q['option_a']); ?><br>B: <?= htmlspecialchars($q['option_b']); ?></td>
                        <td><b><?= $q['correct_answer']; ?></b></td>
                        <td>
                            <a href="soal.php?edit_id=<?= $q['id']; ?>">Edit</a> | 
                            <a href="../../controllers/CategoryController.php?delete=<?= $q['id']; ?>&type=question" onclick="return confirm('Hapus soal ini?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
