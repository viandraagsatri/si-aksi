<?php
require_once '../config/database.php';
require_once '../models/Category.php';

class CategoryController {
    private $categoryModel;
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->categoryModel = new Category($this->db);
    }

    public function handlePost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['target'])) {
            if ($_POST['target'] == 'category') {
                $name = $_POST['name'];
                $slug = strtolower(str_replace(' ', '-', $name));

                if (isset($_POST['id']) && !empty($_POST['id'])) {
                    $this->categoryModel->update($_POST['id'], $name, $slug);
                } else {
                    $this->categoryModel->create($name, $slug);
                }
                header("Location: ../views/admin/kategori.php");
            }
            
            if ($_POST['target'] == 'question') {
                $category_id = $_POST['category_id'];
                $difficulty = $_POST['difficulty'];
                $question = $_POST['question'];
                $option_a = $_POST['option_a'];
                $option_b = $_POST['option_b'];
                $correct_answer = $_POST['correct_answer'];
                $explanation = $_POST['explanation'];

                if (isset($_POST['id']) && !empty($_POST['id'])) {
                    $query = "UPDATE questions SET category_id=?, difficulty=?, question=?, option_a=?, option_b=?, correct_answer=?, explanation=? WHERE id=?";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([$category_id, $difficulty, $question, $option_a, $option_b, $correct_answer, $explanation, $_POST['id']]);
                } else {
                    $query = "INSERT INTO questions (category_id, difficulty, question, option_a, option_b, correct_answer, explanation) VALUES (?,?,?,?,?,?,?)";
                    $stmt = $this->db->prepare($query);
                    $stmt->execute([$category_id, $difficulty, $question, $option_a, $option_b, $correct_answer, $explanation]);
                }
                header("Location: ../views/admin/soal.php");
            }
        }

        if (isset($_GET['delete'])) {
            if ($_GET['type'] == 'category') {
                $this->categoryModel->delete($_GET['delete']);
                header("Location: ../views/admin/kategori.php");
            }
            if ($_GET['type'] == 'question') {
                $stmt = $this->db->prepare("DELETE FROM questions WHERE id = ?");
                $stmt->execute([$_GET['delete']]);
                header("Location: ../views/admin/soal.php");
            }
        }
    }
}

$catCtrl = new CategoryController();
$catCtrl->handlePost();
?>