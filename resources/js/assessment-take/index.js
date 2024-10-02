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
    btn.addEventListener("click", () => {
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

const iconmodal = document.getElementById("dolittle_icon");

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
            // console.log(response);
            updateModals(response?.data);
            modal.show();
        }
    } catch (err) {
        window.alert("An error occoured!,", err.status);
        console.log(err);
    }
}

let passicon = `<dotlottie-player src="https://lottie.host/69a64540-0934-4244-8840-29b3bc08d921/a95uBnXlyg.json" background="transparent" speed="1" style="width: 150px; height: 150px;" autoplay></dotlottie-player>`;

let failicon = `<dotlottie-player src="https://lottie.host/439c9c30-4286-4a5b-a033-cdf8855f4216/GpO6NLRhtH.json" background="transparent" speed="1" style="width: 150px; height: 150px;" autoplay></dotlottie-player>`;

const resultmodalText = document.getElementById("result_modal_text");
const footerCont = document.getElementById("footer_button");
const buttonfail = `<button type="button" class="btn btn-primary">Retake Assessment</button>`;

const buttonpass = `<button type="button" class="btn btn-primary">Next Lesson</button>`;

function updateModals(response) {
    if (response.statustext === "passed") {
        iconmodal.innerHTML = passicon;
        resultmodalText.innerHTML = response?.message;
        footerCont.innerHTML = buttonpass;
    } else if (response.statustext === "failed") {
        iconmodal.innerHTML = failicon;
        resultmodalText.innerHTML = response?.message;
        footerCont.innerHTML = buttonfail;
    }
}

function startTimer(duration, display) {
    let timer = duration;

    const interval = setInterval(() => {
        const minutes = Math.floor(timer / 60);
        const seconds = timer % 60;

        const displayMinutes = minutes < 10 ? "0" + minutes : minutes;
        const displaySeconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = `${displayMinutes}:${displaySeconds}`;
        if (--timer < 0) {
            clearInterval(interval);
            // auto-submit
            processSubmission();
        }
    }, 1000);
}

// timer-functionality
document.addEventListener("DOMContentLoaded", function () {
    const timerElement = document.getElementById("question_timer");
    let timeLimit = parseInt(timerElement.getAttribute("data-time-limit"));
    startTimer(timeLimit, timerElement);
});

// mock-loader
const loader = document.getElementById("loadingAnimation");
setTimeout(() => {
    loader.classList.add("invisible_loader");
}, 4000);
