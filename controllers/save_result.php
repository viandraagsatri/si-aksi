<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS quiz_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    fullname VARCHAR(255),
    category VARCHAR(100),
    score INT,
    total_questions INT,
    status ENUM('Pending','Valid') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$data = json_decode(file_get_contents('php://input'), true);

$user_id = $_SESSION['user_id'];
$fullname = $_SESSION['fullname'];
$category = mysqli_real_escape_string($koneksi, $data['category']);
$score = (int)$data['score'];
$total = (int)$data['total'];

$query = "INSERT INTO quiz_results (user_id, category_id, score, total_questions) VALUES (?, ?, ?, ?)";
VALUES('$user_id','$fullname','$category','$score','$total')";

$insert = mysqli_query($koneksi, $query);

echo json_encode(['success' => $insert]);
