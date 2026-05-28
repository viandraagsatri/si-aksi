<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../koneksi.php';

$kategori = $_GET['kategori'] ?? '';
$mapKategori = ['hardware' => 1, 'software' => 2, 'cybersecurity' => 3, 'ai' => 4];

if (!isset($mapKategori[$kategori])) {
    die("Kategori tidak ditemukan.");
}

$category_id = $mapKategori[$kategori];
$query = mysqli_query($koneksi, "SELECT id, category_id, difficulty, question, option_a, option_b, correct_answer, explanation, reference_source FROM questions WHERE category_id = '$category_id'");

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
$uid = $_SESSION['user_id'] ?? ($_SESSION['user']['id'] ?? 0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Fakta atau Hoaks</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>

<div class="quiz-wrapper">
    <div class="quiz-card">
        <div class="quiz-header">
            <div class="quiz-title">Quiz Fakta atau Hoaks</div>
            <div class="quiz-step"><span id="current-number">1</span>/5</div>
        </div>
        <div class="progress-bar"><div class="progress" id="progress"></div></div>
        <div id="quiz-container"></div>
    </div>
</div>

<script>
const questions = <?php echo json_encode($questions); ?>;
const currentCategoryId = <?php echo intval($category_id); ?>;
const currentUserId = <?php echo intval($uid); ?>;

let currentQuestion = 0;
let score = 0;

function renderQuestion() {
    const q = questions[currentQuestion];
    document.getElementById('current-number').innerText = currentQuestion + 1;
    document.getElementById('progress').style.width = ((currentQuestion + 1) / questions.length * 100) + '%';

    let html = '<div class="question-box">' +
               '<div class="question">' + q.question + '</div>' +
               '<div class="options-group">' +
               '<button class="option-btn" onclick="checkAnswer(this, \'A\')">' + q.option_a + '</button>' +
               '<button class="option-btn" onclick="checkAnswer(this, \'B\')">' + q.option_b + '</button>' +
               '</div><div id="result"></div></div>';
    document.getElementById('quiz-container').innerHTML = html;
}

function checkAnswer(btn, selected) {
    const q = questions[currentQuestion];
    document.querySelectorAll('.option-btn').forEach(b => b.disabled = true);
    
    let isCorrect = (selected === q.correct_answer);
    if (isCorrect) score++;
    
    let resultHTML = '<div class="result-box"><b>' + (isCorrect ? 'Benar! 🎉' : 'Salah!') + '</b><br><br>' + q.explanation + '<br><br><small>Sumber: ' + q.reference_source + '</small></div>';
    
    if (currentQuestion < questions.length - 1) {
        resultHTML += '<button class="next-btn" onclick="nextQuestion()">Soal Berikutnya</button>';
    } else {
        finishQuiz();
    }
    document.getElementById('result').innerHTML = resultHTML;
}

function nextQuestion() {
    currentQuestion++;
    renderQuestion();
}

function finishQuiz() {
    const formData = new URLSearchParams();
    formData.append('user_id', currentUserId);
    formData.append('category_id', currentCategoryId);
    formData.append('score', score);
    formData.append('total', questions.length);
    
    fetch('../controllers/save_result.php', { method: 'POST', body: formData });

    let message = (score === questions.length) ? 'Perfect Score 🔥' : (score >= 3 ? 'Bagus Banget 🎉' : 'Coba Lagi 💪');

    document.getElementById('quiz-container').innerHTML = 
        '<div class="final-box">' +
        '    <div class="final-emoji">🏆</div>' +
        '    <div class="final-title">Quiz Selesai</div>' +
        '    <div class="final-score">' + score + ' / ' + questions.length + '</div>' +
        '    <div class="final-message">' + message + '</div>' +
        '    <button class="next-btn" onclick="location.reload()">Main Lagi</button>' +
        '</div>';
}

renderQuestion();
</script>
</body>
</html>