<?php
class Admin {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllUsers() {
        $query = "SELECT * FROM users WHERE role != 'admin' ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approveUser($id) {
        $query = "UPDATE users SET is_verified = 1 WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function getUserResults() {
        $query = "SELECT u.fullname, u.email, c.name as category_name, q.score, q.total_questions, q.created_at 
                  FROM quiz_scores q
                  JOIN users u ON q.user_id = u.id
                  JOIN categories c ON q.category_id = c.id
                  ORDER BY q.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>