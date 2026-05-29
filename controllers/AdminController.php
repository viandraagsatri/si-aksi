<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Question.php';

class AdminController {
    private $adminModel;
    private $questionModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->adminModel = new Admin($db);
        $this->questionModel = new Question($db);
    }

    public function handleAction() {
        if (isset($_POST['action']) && $_POST['action'] == 'add_question') {
            $success = $this->questionModel->addQuestion(
                $_POST['category_id'],
                $_POST['difficulty'],
                $_POST['question'],
                $_POST['option_a'],
                $_POST['option_b'],
                $_POST['correct_answer'],
                $_POST['explanation'],
                $_POST['reference_source']
            );

            if ($success) {
                echo "<script>alert('Soal kuis baru berhasil disimpan!'); window.location.href='../views/admin/soal.php';</script>";
                exit;
            }
        }

        if (isset($_GET['action']) && $_GET['action'] == 'delete_question' && isset($_GET['id'])) {
            if ($this->questionModel->deleteQuestion($_GET['id'])) {
                echo "<script>alert('Soal berhasil dihapus!'); window.location.href='../views/admin/soal.php';</script>";
                exit;
            }
        }
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