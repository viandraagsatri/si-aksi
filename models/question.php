<?php
class Question {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllQuestions() {
        $query = "SELECT q.*, c.name as category_name 
                  FROM questions q 
                  LEFT JOIN categories c ON q.category_id = c.id 
                  ORDER BY q.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tambah Soal Baru (Struktur kolom sesuai file asli quiz.php)
    public function addQuestion($category_id, $difficulty, $question, $option_a, $option_b, $correct_answer, $explanation, $reference_source) {
        $query = "INSERT INTO questions (category_id, difficulty, question, option_a, option_b, correct_answer, explanation, reference_source) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$category_id, $difficulty, $question, $option_a, $option_b, $correct_answer, $explanation, $reference_source]);
    }

    public function deleteQuestion($id) {
        $query = "DELETE FROM questions WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>