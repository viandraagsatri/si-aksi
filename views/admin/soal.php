<?php
require_once '../../controllers/AdminController.php';

$database = new Database();
$db = $database->getConnection();

$checkColumn = $db->query("SHOW COLUMNS FROM questions LIKE 'difficulty'");
if ($checkColumn->rowCount() == 0) {
    $db->query("ALTER TABLE questions ADD COLUMN difficulty VARCHAR(20) DEFAULT 'easy' AFTER category_id");
}

$all_soal = $adminCtrl->getQuestions();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kuis SI-AKSI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kelola Soal Kuis SI-AKSI</h2>
        <div>
            <a href="users.php" class="btn btn-outline-secondary btn-sm">Kelola User</a>
            <a href="hasil.php" class="btn btn-outline-info btn-sm">Lihat Hasil Kuis</a>
        </div>
    </div>
    
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahSoal">
        + Tambah Soal Baru
    </button>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th width="12%">Kategori</th>
                            <th width="10%">Kesulitan</th>
                            <th width="40%">Pertanyaan</th>
                            <th width="18%">Jawaban Benar</th>
                            <th width="15%">Aksi</th>
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
                            <td><?= htmlspecialchars($s['question']); ?></td>
                            <td class="text-center">
                                <?php if(($s['correct_answer'] ?? '') === 'A'): ?>
                                    <span class="badge bg-success p-2">A. <?= htmlspecialchars($s['option_a'] ?? 'Fakta'); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-danger p-2">B. <?= htmlspecialchars($s['option_b'] ?? 'Hoaks'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="../../controllers/AdminController.php?action=delete_question&id=<?= $s['id']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Yakin ingin menghapus soal ini?');">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahSoal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Form Tambah Soal Kuis</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="../../controllers/AdminController.php" method="POST">
        <input type="hidden" name="action" value="add_question">

        <div class="modal-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <select class="form-select" name="category_id" required>
                        <option value="1">Hardware</option>
                        <option value="2">Software</option>
                        <option value="3">Cybersecurity</option>
                        <option value="4">AI</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tingkat Kesulitan</label>
                    <select class="form-select" name="difficulty" required>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Pertanyaan Kuis</label>
                <textarea class="form-control" name="question" rows="2" placeholder="Tulis pertanyaan di sini..." required></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Teks Opsi A</label>
                    <input type="text" class="form-control" name="option_a" value="Fakta" placeholder="Contoh: Fakta" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teks Opsi B</label>
                    <input type="text" class="form-control" name="option_b" value="Hoaks" placeholder="Contoh: Hoaks" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Pilihan Kunci Jawaban yang Benar</label>
                <select class="form-select" name="correct_answer" required>
                    <option value="A">Opsi A (Fakta)</option>
                    <option value="B">Opsi B (Hoaks)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Penjelasan Edukasi</label>
                <textarea class="form-control" name="explanation" rows="3" placeholder="Tulis alasan pembahasannya..." required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Sumber Referensi / Rujukan</label>
                <input type="text" class="form-control" name="reference_source" placeholder="Contoh: Kominfo / TurnBackHoax" required>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan Soal</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>