<?php
require_once '../../config/auth_check.php';
checkLogin();
checkAdmin();

require_once '../../controllers/AdminController.php';

$database = new Database();
$db = $database->getConnection();

$checkColumn = $db->query("SHOW COLUMNS FROM questions LIKE 'difficulty'");
if ($checkColumn->rowCount() == 0) {
    $db->query("ALTER TABLE questions ADD COLUMN difficulty VARCHAR(20) DEFAULT 'easy' AFTER category_id");
}

if (!empty($_GET['search'])) {
    $all_soal = $adminCtrl->searchQuestions($_GET['search']);
} else {
    $all_soal = $adminCtrl->getQuestions();
}

$categoryQuery = $db->query("SELECT * FROM categories ORDER BY id ASC");
$categories = $categoryQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kuis SI-AKSI</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body class="bg-light">

    <div class="dashboard-container">
        <div class="page-header">
            <h2>Kelola Soal Kuis SI-AKSI</h2>
            <a href="dashboard.php" class="back-btn">
                ← Kembali ke Dashboard
            </a>
        </div>
        
        <button type="button" class="btn-submit-custom" onclick="openAddModal()">
            + Tambah Soal Baru
        </button>

        <form method="GET" class="search-form">
            <input
                type="text"
                name="search"
                placeholder="Cari soal, kategori, atau kesulitan..."
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                class="search-input"
            >

            <button type="submit" class="btn-submit-custom">
                Cari
            </button>
        </form>

        <div class="table-container">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Kesulitan</th>
                            <th>Pertanyaan</th>
                            <th>Jawaban Benar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if(empty($all_soal)): ?>
                            <tr>
                                <td colspan="6">Belum ada data soal di database.</td>
                            </tr>
                        <?php endif; ?>
                        
                        <?php $no = 1; foreach($all_soal as $s) : ?>
                            <tr>
                                <td><?= $no++; ?></td>

                                <td>
                                    <?= htmlspecialchars($s['category_name'] ?? 'General'); ?>
                                </td>

                                <td>
                                    <?= ucfirst(htmlspecialchars($s['difficulty'] ?? 'easy')); ?>
                                </td>

                                <td class="question-column">
                                    <?= htmlspecialchars($s['question']); ?>
                                </td>

                                <td>
                                    <?php if(($s['correct_answer'] ?? '') === 'A'): ?>
                                        A. <?= htmlspecialchars($s['option_a'] ?? 'Fakta'); ?>
                                    <?php else: ?>
                                        B. <?= htmlspecialchars($s['option_b'] ?? 'Hoaks'); ?>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="table-action">
                                        <button
                                            type="button"
                                            class="btn-action-edit edit-question-btn"
                                            data-id="<?= htmlspecialchars($s['id'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-category-id="<?= htmlspecialchars($s['category_id'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-difficulty="<?= htmlspecialchars($s['difficulty'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-question="<?= htmlspecialchars($s['question'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-correct-answer="<?= htmlspecialchars($s['correct_answer'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-explanation="<?= htmlspecialchars($s['explanation'], ENT_QUOTES, 'UTF-8'); ?>"
                                            data-reference-source="<?= htmlspecialchars($s['reference_source'], ENT_QUOTES, 'UTF-8'); ?>"
                                        >
                                            Edit
                                        </button>

                                        <a
                                            href="../../controllers/AdminController.php?action=delete_question&id=<?= $s['id']; ?>"
                                            class="btn-action-delete"
                                            onclick="return confirm('Yakin ingin menghapus soal ini?');"
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
    </div>

    <div id="modalTambahSoal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h3>Tambah Soal Baru</h3>
                <span class="close-modal" onclick="closeAddModal()">&times;</span>
            </div>

            <form action="../../controllers/AdminController.php" method="POST">
                <input type="hidden" name="action" value="add_question">

                <div class="input-group">
                    <label>Kategori</label>
                    <select name="category_id" required>
                        <option value="">Pilih kategori</option>

                        <?php foreach($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['id']); ?>">
                                <?= htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="input-group">
                    <label>Tingkat Kesulitan</label>
                    <select name="difficulty" required>
                        <option value="">Pilih tingkat kesulitan</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Pertanyaan</label>
                    <textarea name="question" rows="3" required></textarea>
                </div>

                <div class="input-group">
                    <label>Jawaban Benar</label>
                    <select name="correct_answer" required>
                        <option value="">Pilih jawaban benar</option>
                        <option value="A">Fakta</option>
                        <option value="B">Hoaks</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Penjelasan Edukasi</label>
                    <textarea name="explanation" rows="3" required></textarea>
                </div>

                <div class="input-group">
                    <label>Sumber Referensi</label>
                    <input type="text" name="reference_source" required>
                </div>

                <button type="submit" class="btn-submit-custom">
                    Simpan Soal
                </button>
            </form>
        </div>
    </div>

    <div id="modalEditSoal" class="custom-modal">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h3>Edit Soal</h3>
                <span class="close-modal" onclick="closeEditModal()">&times;</span>
            </div>

            <form action="../../controllers/AdminController.php" method="POST">
                <input type="hidden" name="action" value="update_question">
                <input type="hidden" name="id" id="edit_id">

                <div class="input-group">
                    <label>Kategori</label>
                    <select name="category_id" id="edit_category_id" required>
                        <option value="">Pilih kategori</option>

                        <?php foreach($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['id']); ?>">
                                <?= htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="input-group">
                    <label>Tingkat Kesulitan</label>
                    <select name="difficulty" id="edit_difficulty" required>
                        <option value="">Pilih tingkat kesulitan</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Pertanyaan</label>
                    <textarea name="question" id="edit_question" rows="3" required></textarea>
                </div>

                <div class="input-group">
                    <label>Jawaban Benar</label>
                    <select name="correct_answer" id="edit_correct_answer" required>
                        <option value="">Pilih jawaban benar</option>
                        <option value="A">Fakta</option>
                        <option value="B">Hoaks</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Penjelasan Edukasi</label>
                    <textarea name="explanation" id="edit_explanation" rows="3" required></textarea>
                </div>

                <div class="input-group">
                    <label>Sumber Referensi</label>
                    <input type="text" name="reference_source" id="edit_reference_source" required>
                </div>

                <button type="submit" class="btn-submit-custom">
                    Update Soal
                </button>
            </form>
        </div>
    </div>

    <script src="../../public/js/script.js"></script>
</body>
</html>