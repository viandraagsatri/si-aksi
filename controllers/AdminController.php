<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/question.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class AdminController {
    private $adminModel;
    private $questionModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();

        $this->adminModel = new Admin($db);
        $this->questionModel = new Question($db);
    }

    private function requireAdmin() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../views/login.php");
            exit();
        }

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header("Location: ../views/user/dashboard.php");
            exit();
        }
    }

    public function handleAction() {
        if (isset($_POST['action']) && $_POST['action'] === 'add_question') {
            $this->requireAdmin();

            $category_id = $_POST['category_id'] ?? '';
            $difficulty = $_POST['difficulty'] ?? '';
            $question = trim($_POST['question'] ?? '');
            $option_a = 'Fakta';
            $option_b = 'Hoaks';
            $correct_answer = $_POST['correct_answer'] ?? '';
            $explanation = trim($_POST['explanation'] ?? '');
            $reference_source = trim($_POST['reference_source'] ?? '');

            if (
                empty($category_id) ||
                empty($difficulty) ||
                empty($question) ||
                empty($correct_answer) ||
                empty($explanation) ||
                empty($reference_source)
            ) {
                echo "<script>
                    alert('Data soal belum lengkap!');
                    window.location.href='../views/admin/soal.php';
                </script>";
                exit();
            }

            $success = $this->questionModel->addQuestion(
                $category_id,
                $difficulty,
                $question,
                $option_a,
                $option_b,
                $correct_answer,
                $explanation,
                $reference_source
            );

            if ($success) {
                echo "<script>
                    alert('Soal kuis baru berhasil disimpan!');
                    window.location.href='../views/admin/soal.php';
                </script>";
                exit();
            }

            echo "<script>
                alert('Gagal menambahkan soal!');
                window.location.href='../views/admin/soal.php';
            </script>";
            exit();
        }

        if (isset($_POST['action']) && $_POST['action'] === 'update_question') {
            $this->requireAdmin();

            $id = $_POST['id'] ?? '';
            $category_id = $_POST['category_id'] ?? '';
            $difficulty = $_POST['difficulty'] ?? '';
            $question = trim($_POST['question'] ?? '');
            $option_a = 'Fakta';
            $option_b = 'Hoaks';
            $correct_answer = $_POST['correct_answer'] ?? '';
            $explanation = trim($_POST['explanation'] ?? '');
            $reference_source = trim($_POST['reference_source'] ?? '');

            if (
                empty($id) ||
                empty($category_id) ||
                empty($difficulty) ||
                empty($question) ||
                empty($correct_answer) ||
                empty($explanation) ||
                empty($reference_source)
            ) {
                echo "<script>
                    alert('Data edit soal belum lengkap!');
                    window.location.href='../views/admin/soal.php';
                </script>";
                exit();
            }

            $success = $this->questionModel->updateQuestion(
                $id,
                $category_id,
                $difficulty,
                $question,
                $option_a,
                $option_b,
                $correct_answer,
                $explanation,
                $reference_source
            );

            if ($success) {
                echo "<script>
                    alert('Soal berhasil diperbarui!');
                    window.location.href='../views/admin/soal.php';
                </script>";
                exit();
            }

            echo "<script>
                alert('Gagal memperbarui soal!');
                window.location.href='../views/admin/soal.php';
            </script>";
            exit();
        }

        if (isset($_GET['action']) && $_GET['action'] === 'delete_question' && isset($_GET['id'])) {
            $this->requireAdmin();

            if ($this->questionModel->deleteQuestion($_GET['id'])) {
                echo "<script>
                    alert('Soal berhasil dihapus!');
                    window.location.href='../views/admin/soal.php';
                </script>";
                exit();
            }

            echo "<script>
                alert('Gagal menghapus soal!');
                window.location.href='../views/admin/soal.php';
            </script>";
            exit();
        }
        if (isset($_GET['action']) && $_GET['action'] === 'approve' && isset($_GET['id'])) {
            $this->requireAdmin();

            if ($this->adminModel->approveUser($_GET['id'])) {
                echo "<script>
                    alert('Akun berhasil diverifikasi!');
                    window.location.href='../views/admin/users.php';
                </script>";
                exit();
            }

            echo "<script>
                alert('Gagal memverifikasi akun!');
                window.location.href='../views/admin/users.php';
            </script>";
            exit();
    }

    public function searchQuestions($keyword) {
        return $this->questionModel->searchQuestions($keyword);
    }

    public function getQuestions() {
        return $this->questionModel->getAllQuestions();
    }

    public function getQuizResults() {
        return $this->adminModel->getUserResults();
    }
}

$adminCtrl = new AdminController();
$adminCtrl->handleAction();
?>
