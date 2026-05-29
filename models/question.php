<?php
class Question {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllQuestions() {
        $query = "SELECT q.*, c.name AS category_name 
                  FROM questions q 
                  LEFT JOIN categories c ON q.category_id = c.id 
                  ORDER BY q.id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addQuestion($category_id, $difficulty, $question, $option_a, $option_b, $correct_answer, $explanation, $reference_source) {
        $query = "INSERT INTO questions (
                    category_id,
                    difficulty,
                    question,
                    option_a,
                    option_b,
                    correct_answer,
                    explanation,
                    reference_source
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $category_id,
            $difficulty,
            $question,
            $option_a,
            $option_b,
            $correct_answer,
            $explanation,
            $reference_source
        ]);
    }

    public function updateQuestion($id, $category_id, $difficulty, $question, $option_a, $option_b, $correct_answer, $explanation, $reference_source) {
        $query = "UPDATE questions
                  SET category_id = ?,
                      difficulty = ?,
                      question = ?,
                      option_a = ?,
                      option_b = ?,
                      correct_answer = ?,
                      explanation = ?,
                      reference_source = ?
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $category_id,
            $difficulty,
            $question,
            $option_a,
            $option_b,
            $correct_answer,
            $explanation,
            $reference_source,
            $id
        ]);
    }

    public function searchQuestions($keyword) {
        $query = "SELECT q.*, c.name AS category_name
                  FROM questions q
                  LEFT JOIN categories c ON q.category_id = c.id
                  WHERE q.question LIKE ?
                     OR c.name LIKE ?
                     OR q.difficulty LIKE ?
                     OR q.correct_answer LIKE ?
                     OR q.reference_source LIKE ?
                  ORDER BY q.id DESC";

        $stmt = $this->conn->prepare($query);

        $search = "%$keyword%";

        $stmt->execute([
            $search,
            $search,
            $search,
            $search,
            $search
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteQuestion($id) {
        $query = "DELETE FROM questions WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        return $stmt->execute([$id]);
    }
}
?>
