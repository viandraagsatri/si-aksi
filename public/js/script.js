function openAddModal() {
    document.getElementById('modalTambahSoal').style.display = 'flex';
}

function closeAddModal() {
    document.getElementById('modalTambahSoal').style.display = 'none';
}

function openEditModal() {
    document.getElementById('modalEditSoal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('modalEditSoal').style.display = 'none';
}

function initEditButtons() {
    const editButtons = document.querySelectorAll('.edit-question-btn');

    editButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            document.getElementById('edit_id').value = this.dataset.id;
            document.getElementById('edit_category_id').value = this.dataset.categoryId;
            document.getElementById('edit_difficulty').value = this.dataset.difficulty;
            document.getElementById('edit_question').value = this.dataset.question;
            document.getElementById('edit_correct_answer').value = this.dataset.correctAnswer;
            document.getElementById('edit_explanation').value = this.dataset.explanation;
            document.getElementById('edit_reference_source').value = this.dataset.referenceSource;

            openEditModal();
        });
    });
}

function initModalOutsideClick() {
    window.addEventListener('click', function (e) {
        const addModal = document.getElementById('modalTambahSoal');
        const editModal = document.getElementById('modalEditSoal');

        if (addModal && e.target === addModal) closeAddModal();
        if (editModal && e.target === editModal) closeEditModal();
    });
}

let currentQuestion = 0;
let score = 0;
let resultSaved = false;

function renderQuestion() {
    if (typeof questions === 'undefined' || questions.length === 0) {
        document.getElementById('quiz-container').innerHTML =
            '<div class="question-box">' +
            '   <div class="question">Belum ada soal untuk kategori ini.</div>' +
            '</div>';

        document.getElementById('current-number').innerText = 0;
        document.getElementById('progress').style.width = '0%';
        return;
    }

    const q = questions[currentQuestion];

    document.getElementById('current-number').innerText = currentQuestion + 1;
    document.getElementById('progress').style.width = ((currentQuestion + 1) / questions.length * 100) + '%';

    let html =
        '<div class="question-box">' +
        '   <div class="question">' + q.question + '</div>' +
        '   <div class="options-group">' +
        '       <button class="option-btn" onclick="checkAnswer(this, \'A\')">' + q.option_a + '</button>' +
        '       <button class="option-btn" onclick="checkAnswer(this, \'B\')">' + q.option_b + '</button>' +
        '   </div>' +
        '   <div id="result"></div>' +
        '</div>';

    document.getElementById('quiz-container').innerHTML = html;
}

/**
 * @param { HTMLElement } button
 * @param { string } selected
 */

function checkAnswer(button, selected) {
    const q = questions[currentQuestion];
    const buttons = document.querySelectorAll('.option-btn');

    buttons.forEach(function (btn) {
        btn.disabled = true;
    });

    const isCorrect = selected === q.correct_answer;

    if (isCorrect) {
        score++;
        button.classList.add('correct');
    } else {
        button.classList.add('wrong');

        buttons.forEach(function (btn) {
            const onclickValue = btn.getAttribute('onclick');
            if (
                (q.correct_answer === 'A' && onclickValue.includes("'A'")) ||
                (q.correct_answer === 'B' && onclickValue.includes("'B'"))
            ) {
                btn.classList.add('correct');
            }
        });
    }

    const jawabanBenar = q.correct_answer === 'A' ? q.option_a : q.option_b;

    let resultHTML =
        '<div class="result-box">' +
        '   <b>' + (isCorrect ? 'Jawaban Benar!' : 'Jawaban Salah!') + '</b>' +
        '   <br><br>' +
        q.explanation +
        '   <br><br>';

    if (!isCorrect) {
        resultHTML +=
            '   <b>Jawaban benar: ' + jawabanBenar + '</b>' +
            '   <br><br>';
    }

    resultHTML +=
        '   <small>Sumber: ' + q.reference_source + '</small>' +
        '</div>';

    if (currentQuestion < questions.length - 1) {
        resultHTML +=
            '<button class="next-btn" onclick="nextQuestion()">Soal Berikutnya</button>';
    } else {
        resultHTML +=
            '<button class="next-btn" onclick="finishQuiz()">Lihat Skor</button>';
    }

    document.getElementById('result').innerHTML = resultHTML;
}

function nextQuestion() {
    currentQuestion++;
    renderQuestion();
}

function finishQuiz() {
    if (resultSaved) return;
    resultSaved = true;

    const formData = new URLSearchParams();
    formData.append('user_id', currentUserId);
    formData.append('category_id', currentCategoryId);
    formData.append('category', currentCategoryName);
    formData.append('score', score);
    formData.append('total', questions.length);

    fetch('../../controllers/save_result.php', {
        method: 'POST',
        body: formData
    });

    let message = '';
    if (score === questions.length) {
        message = 'Perfect Score 🔥';
    } else if (score >= 3) {
        message = 'Bagus Banget 🎉';
    } else {
        message = 'Coba Lagi 💪';
    }

    document.getElementById('quiz-container').innerHTML =
        '<div class="final-box">' +
        '   <div class="final-emoji">🏆</div>' +
        '   <div class="final-title">Quiz Selesai</div>' +
        '   <div class="final-score">' + score + ' / ' + questions.length + '</div>' +
        '   <div class="final-message">' + message + '</div>' +
        '   <button class="next-btn" onclick="location.reload()">Main Lagi</button>' +
        '</div>';
}

document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('.edit-question-btn')) {
        initEditButtons();
    }
    if (document.getElementById('modalTambahSoal') || document.getElementById('modalEditSoal')) {
        initModalOutsideClick();
    }

    if (document.getElementById('quiz-container')) {
        renderQuestion();
    }
});