<?php
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
        
        <button type="button" class="btn-submit-custom" onclick="openModal()">
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
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark text-center">
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
                            <tr><td colspan="6" class="text-center text-muted">Belum ada data soal di database.</td></tr>
                        <?php endif; ?>
                        
                        <?php $no = 1; foreach($all_soal as $s) : ?>
                        <tr>
                            <td class="text-center"><?= $no++; ?></td>
                            <td class="text-center"><span class="badge bg-secondary"><?= htmlspecialchars($s['category_name'] ?? 'General'); ?></span></td>
                            <td class="text-center">
                                <span class="badge bg-dark"><?= ucfirst(htmlspecialchars($s['difficulty'] ?? 'easy')); ?></span>
                            </td>
                            <td class="question-column">
                                <?= htmlspecialchars($s['question']); ?>
                            </td>
                            <td class="text-center">
                                <?php if(($s['correct_answer'] ?? '') === 'A'): ?>
                                    <span class="badge bg-success p-2">A. <?= htmlspecialchars($s['option_a'] ?? 'Fakta'); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger p-2">B. <?= htmlspecialchars($s['option_b'] ?? 'Hoaks'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="../../controllers/AdminController.php?action=delete_question&id=<?= $s['id']; ?>" 
                                    class="btn-submit-custom" 
                                    onclick="return confirm('Yakin ingin menghapus soal ini?');">Hapus</a>
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
                <span class="close-modal" onclick="closeModal()">&times;</span>
            </div>

            <form action="../../controllers/AdminController.php" method="POST">
                <input type="hidden" name="action" value="add_question">
                <div class="input-group">
                    <label>Kategori</label>
                    <select name="category_id" required>
                        <option value="1">Hardware</option>
                        <option value="2">Software</option>
                        <option value="3">Cybersecurity</option>
                        <option value="4">AI</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Tingkat Kesulitan</label>
                    <select name="difficulty" required>
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
                    <label>Opsi A</label>
                    <input type="text" name="option_a" value="Fakta" required>
                </div>

                <div class="input-group">
                    <label>Opsi B</label>
                    <input type="text" name="option_b" value="Hoaks" required>
                </div>

                <div class="input-group">
                    <label>Jawaban Benar</label>
                    <select name="correct_answer" required>
                        <option value="A">Opsi A (Fakta)</option>
                        <option value="B">Opsi B (Hoaks)</option>
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

    <script>
    function openModal() {
        document.getElementById('modalTambahSoal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modalTambahSoal').style.display = 'none';
    }

    window.onclick = function(e) {
        const modal = document.getElementById('modalTambahSoal');

        if (e.target === modal) {
            closeModal();
        }
    }
    </script>
</body>
</html>