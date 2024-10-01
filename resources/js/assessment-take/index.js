import * as bootstrap from "bootstrap";
import axios from "axios";
const csrftoken = document.querySelector("input[name=_token]")?.value;
// get_lesson_idand_corse_id
const urlString = window.location.href;
const regex = /courses\/(\d+)\/lesson\/(\d+)/;
const match = urlString.match(regex);
let { courseId, lessonId } = match
    ? { courseId: match[1], lessonId: match[2] }
    : { courseId: null, lessonId: null };
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

document.querySelectorAll("#box_navigate_cbt").forEach((btn) => {
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
    currentQuestionIndex >= questions.length && (currentQuestionIndex = 1);
    showQuestion(currentQuestionIndex);
};

prev.onclick = function () {
    currentQuestionIndex--;
    currentQuestionIndex < 0 && (currentQuestionIndex = questions.length - 1);
    showQuestion(currentQuestionIndex);
};

const modalElement = document.getElementById("modal_result");
const modal = new bootstrap.Modal(modalElement);

// submit-button-click-save-ans-score-assessment
const submitCbtBtn = document.querySelector("#submit_cbt");
submitCbtBtn.addEventListener("click", () => {
    processSubmission();
});

async function processSubmission() {
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

    const payload = {
        answers,
    };

    try {
        const response = await axios.post(
            `/courses/${courseId}/lesson/${lessonId}/submit-assessment`,
            {
                ...payload,
            },
            {
                headers: {
                    "X-CSRF-Token": csrftoken,
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            }
        );

        if (response) {
            console.log(response);
            modal.show();
        }
    } catch (err) {
        window.alert("An error occoured!,", err.status);
    }
}
