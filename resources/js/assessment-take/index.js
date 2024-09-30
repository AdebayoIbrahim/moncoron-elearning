// Initialize with the first question displayed
const questions = document.querySelectorAll(".area-question-data");
questions[0].style.display = "block";

// Get the current question index (assuming it's starting at 1)
let currentQuestionIndex = parseInt(
    document.querySelector(".question_current_index")?.id
);

// Show the correct question based on the index
function showQuestion(index) {
    questions.forEach((question, i) => {
        question.style.display = i === index ? "block" : "none";
    });
}

// Update the displayed question on navigation
const btnprogress = document
    .querySelectorAll("#box_navigate_cbt")
    .forEach((btn) => {
        btn.addEventListener("click", (e) => {
            const currid = parseInt(btn.innerText); // Get the question index from the button text
            currentQuestionIndex = currid; // Update current index (convert to zero-based)
            showQuestion(currentQuestionIndex); // Show the selected question
        });
    });

// Handle next and prev buttons
const next = document.querySelector("#next_cbt");
const prev = document.querySelector("#prev_cbt");

next.onclick = function () {
    if (currentQuestionIndex < questions.length - 1) {
        currentQuestionIndex++; // Move to the next question
        showQuestion(currentQuestionIndex); // Display the next question
    }
};

prev.onclick = function () {
    if (currentQuestionIndex > 0) {
        currentQuestionIndex--; // Move to the previous question
        showQuestion(currentQuestionIndex); // Display the previous question
    }
};

// Show the first question initially
showQuestion(currentQuestionIndex);
