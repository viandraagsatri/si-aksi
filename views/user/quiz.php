<?php
require_once '../../config/auth_check.php';
checkLogin();

include '../../koneksi.php';

$kategori = $_GET['kategori'] ?? '';

$mapKategori = [
    'hardware' => 1,
    'software' => 2,
    'cybersecurity' => 3,
    'ai' => 4
];

$namaKategori = [
    'hardware' => 'Hardware',
    'software' => 'Software & Internet',
    'cybersecurity' => 'Cyber Security',
    'ai' => 'Artificial Intelligence'
];

if (!isset($mapKategori[$kategori])) {
    die("Kategori tidak ditemukan.");
}

$category_id = $mapKategori[$kategori];
$category_name = $namaKategori[$kategori];

$query = mysqli_query($koneksi, "
    SELECT 
        id, 
        category_id, 
        difficulty, 
        question, 
        option_a, 
        option_b, 
        correct_answer, 
        explanation, 
        reference_source 
    FROM questions 
    WHERE category_id = '$category_id'
");

$allQuestions = [];

while($row = mysqli_fetch_assoc($query)){
    $allQuestions[] = $row;
}

function pickRandomQuestion(&$pool, $difficulty, $answer, $usedIds) {
    $candidates = [];

    foreach ($pool as $q) {
        if ($q['difficulty'] === $difficulty && $q['correct_answer'] === $answer && !in_array($q['id'], $usedIds)) {
            $candidates[] = $q;
        }
    }

    if (count($candidates) === 0) {
        foreach ($pool as $q) {
            if ($q['difficulty'] === $difficulty && !in_array($q['id'], $usedIds)) {
                $candidates[] = $q;
            }
        }
    }

    if (count($candidates) === 0) {
        foreach ($pool as $q) {
            if ($q['correct_answer'] === $answer && !in_array($q['id'], $usedIds)) {
                $candidates[] = $q;
            }
        }
    }

    if (count($candidates) === 0) {
        foreach ($pool as $q) {
            if (!in_array($q['id'], $usedIds)) {
                $candidates[] = $q;
            }
        }
    }

    return (count($candidates) === 0) ? null : $candidates[array_rand($candidates)];
}

$answerPattern = (rand(0, 1) === 0) ? ['A', 'A', 'A', 'B', 'B'] : ['B', 'B', 'B', 'A', 'A'];

$difficultyPattern = ['easy', 'medium', 'hard'];
$extra = ['easy', 'medium', 'hard'];
shuffle($extra);
$difficultyPattern[] = $extra[0];
$difficultyPattern[] = $extra[1];

shuffle($answerPattern);
shuffle($difficultyPattern);

$questions = [];
$usedIds = [];

for ($i = 0; $i < 5; $i++) {
    $picked = pickRandomQuestion($allQuestions, $difficultyPattern[$i], $answerPattern[$i], $usedIds);

    if ($picked !== null) {
        $questions[] = $picked;
        $usedIds[] = $picked['id'];
    }
}

shuffle($questions);

$uid = $_SESSION['user_id'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Fakta atau Hoaks</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <div class="quiz-wrapper">
        <div class="quiz-layout">
            <div class="quiz-top">
                <a href="dashboard.php" class="back-btn">
                    ← Kembali
                </a>
            </div>

            <div class="quiz-card">
                <div class="quiz-header">
                    <div class="quiz-title">Quiz Fakta atau Hoaks</div>
                    <div class="quiz-step"><span id="current-number">1</span>/5</div>
                </div>

                <div class="progress-bar">
                    <div class="progress" id="progress"></div>
                </div>

                <div id="quiz-container"></div>
            </div>
        </div>
    </div>

    <script>
    const questions = <?php echo json_encode($questions, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    const currentCategoryId = <?php echo intval($category_id); ?>;
    const currentCategoryName = <?php echo json_encode($category_name); ?>;
    const currentUserId = <?php echo intval($uid); ?>;
    </script>

    <script src="../../public/js/script.js"></script>
</body>
</html>