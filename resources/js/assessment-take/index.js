const questions = document.querySelectorAll(".area-question-data");
questions[0].style.display = "block";

let currentQuestionIndex = parseInt(
    document.querySelector(".question_current_index")?.id
);

function showQuestion(index) {
    questions.forEach((question, i) => {
        question.style.display = i === index ? "block" : "none";
    });
}

const btnprogress = document
    .querySelectorAll("#box_navigate_cbt")
    .forEach((btn) => {
        btn.addEventListener("click", (e) => {
            const currid = parseInt(btn.innerText);
            currentQuestionIndex = currid;
            showQuestion(currentQuestionIndex);
        });
    });

const next = document.querySelector("#next_cbt");
const prev = document.querySelector("#prev_cbt");

next.onclick = function () {
    currentQuestionIndex++;
    currentQuestionIndex >= questions.length && (currentQuestionIndex = 0);
    showQuestion(currentQuestionIndex);
};

prev.onclick = function () {
    currentQuestionIndex--;
    currentQuestionIndex < 0 && (currentQuestionIndex = questions.length - 1);
    showQuestion(currentQuestionIndex);
};

// Show the first question initially
showQuestion(currentQuestionIndex);
