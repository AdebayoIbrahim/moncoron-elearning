import * as bootstrap from "bootstrap";
import axios from "axios";
const satsusmodal = new bootstrap.Modal(
    document.querySelector("#modal_result")
);
satsusmodal.show();
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

// submit-button-click-save-ans-score-assessment
const submitCbtBtn = document.querySelector("#submit_cbt");
submitCbtBtn.addEventListener("click", () => {
    // initialize-answrs
    const answers = [];

    questions.forEach((questionarea, index) => {
        // quesion-id-is-1++-although-not-ideal-will-get-attributes-later
        const questionId = index + 1;

        //get options-selected
        const optionselect =
            questionarea?.querySelector(
                `input[name="Question${questionId}"]:checked`
            ) || null;

        answers.push({
            question_id: questionId,
            selected_option: optionselect?.getAttribute("data-id") || null,
        });
    });

    console.log(answers);
});
