<?php
require_once '../config/auth_check.php';
checkLogin();

include '../koneksi.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

$category_id = $_POST['category_id'] ?? '';
$score = $_POST['score'] ?? '';
$total = $_POST['total'] ?? '';

if ($category_id === '' || $score === '' || $total === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Data hasil kuis tidak lengkap.'
    ]);
    exit();
}

$category_id = (int) $category_id;
$score = (int) $score;
$total = (int) $total;

$query = "INSERT INTO quiz_results (user_id, category_id, score, total_questions)
          VALUES (?, ?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'Query gagal dipersiapkan.'
    ]);
    exit();
}

mysqli_stmt_bind_param($stmt, "iiii", $user_id, $category_id, $score, $total);

$success = mysqli_stmt_execute($stmt);

echo json_encode([
    'success' => $success
]);
?>
