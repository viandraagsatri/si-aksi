<?php

include '../koneksi.php';

$kategori = $_GET['kategori'] ?? '';

$mapKategori = [
    'hardware' => 1,
    'software' => 2,
    'cybersecurity' => 3,
    'ai' => 4
];

if (!isset($mapKategori[$kategori])) {
    die("Kategori tidak ditemukan.");
}

$category_id = $mapKategori[$kategori];

$query = mysqli_query($koneksi, "
    SELECT id, category_id, difficulty, question, option_a, option_b, correct_answer, explanation, reference_source
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

    if (count($candidates) === 0) {
        return null;
    }

    return $candidates[array_rand($candidates)];
}

$answerPattern = rand(0, 1) === 0
    ? ['A', 'A', 'A', 'B', 'B']
    : ['B', 'B', 'B', 'A', 'A'];

$difficultyPattern = ['easy', 'medium', 'hard'];
$extraDifficulty = ['easy', 'medium', 'hard'];
shuffle($extraDifficulty);
$difficultyPattern[] = $extraDifficulty[0];
$difficultyPattern[] = $extraDifficulty[1];

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

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Fakta atau Hoaks</title>

    <link rel="stylesheet" href="../public/css/quiz.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<div class="quiz-wrapper">

    <div class="quiz-card">

        <div class="quiz-header">

            <div class="quiz-title">
                Quiz Fakta atau Hoaks
            </div>

            <div class="quiz-step">
                <span id="current-number">1</span>/5
            </div>

        </div>

        <div class="progress-bar">
            <div class="progress" id="progress"></div>
        </div>

        <div id="quiz-container"></div>

    </div>

</div>

<script>

const questions = <?= json_encode($questions) ?>;

let currentQuestion = 0;
let score = 0;

function formatDifficulty(level){
    if(level === 'easy') return 'Easy';
    if(level === 'medium') return 'Medium';
    if(level === 'hard') return 'Hard';
    return level;
}

function renderQuestion(){

    if (questions.length === 0) {
        document.getElementById('quiz-container').innerHTML = `
            <div class="question-box">
                <div class="question">
                    Belum ada soal untuk kategori ini.
                </div>
            </div>
        `;
        return;
    }

    const q = questions[currentQuestion];

    document.getElementById('current-number').innerText =
        currentQuestion + 1;

    document.getElementById('progress').style.width =
        ((currentQuestion + 1) / questions.length * 100) + '%';

    document.getElementById('quiz-container').innerHTML = `

        <div class="question-box">

            <div class="question-top">

                <div class="question-badge">
                    Question ${currentQuestion + 1} • ${formatDifficulty(q.difficulty)}
                </div>

            </div>

            <div class="question">
                ${q.question}
            </div>

            <div class="options-group">

                <button class="option-btn"
                    onclick="checkAnswer(this,'A')">
                    ${q.option_a}
                </button>

                <button class="option-btn"
                    onclick="checkAnswer(this,'B')">
                    ${q.option_b}
                </button>

            </div>

            <div id="result"></div>

        </div>

    `;
}

function checkAnswer(button, selected){

    const q = questions[currentQuestion];

    const buttons =
        document.querySelectorAll('.option-btn');

    buttons.forEach(btn => {
        btn.disabled = true;
    });

    let resultHTML = '';
    let jawabanBenar = q.correct_answer === 'A' ? q.option_a : q.option_b;

    if(selected === q.correct_answer){

        score++;

        button.classList.add('correct');

        resultHTML = `
            <div class="result-box">

                <b>Jawaban Benar! 🎉</b>

                <br><br>

                ${q.explanation}

                <br><br>

                <small>
                    Sumber:
                    ${q.reference_source}
                </small>

            </div>
        `;

    }else{

        button.classList.add('wrong');

        resultHTML = `
            <div class="result-box">

                <b>Jawaban Salah!</b>

                <br><br>

                ${q.explanation}

                <br><br>

                <b>
                    Jawaban benar:
                    ${jawabanBenar}
                </b>

                <br><br>

                <small>
                    Sumber:
                    ${q.reference_source}
                </small>

            </div>
        `;
    }

    if(currentQuestion < questions.length - 1){

        resultHTML += `
            <button class="next-btn"
                onclick="nextQuestion()">
                Soal Berikutnya
            </button>
        `;

    }else{

        setTimeout(() => {
            showFinalScore();
        }, 1500);
    }

    document.getElementById('result').innerHTML =
        resultHTML;
}

function nextQuestion(){

    currentQuestion++;

    renderQuestion();
}

function showFinalScore(){

    let message = '';

    if(score == questions.length){

        message = 'Perfect Score 🔥';

    }else if(score >= 3){

        message = 'Bagus Banget 🎉';

    }else{

        message = 'Coba Lagi 💪';
    }

    document.getElementById('quiz-container').innerHTML = `

        <div class="final-box">

            <div class="final-emoji">
                🏆
            </div>

            <div class="final-title">
                Quiz Selesai
            </div>

            <div class="final-score">
                ${score} / ${questions.length}
            </div>

            <div class="final-message">
                ${message}
            </div>

            <button class="next-btn"
                onclick="location.reload()">
                Main Lagi
            </button>

        </div>

    `;
}

renderQuestion();

</script>

</body>
</html>
