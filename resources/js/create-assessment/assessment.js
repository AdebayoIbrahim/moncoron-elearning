import * as bootstrap from "bootstrap";
import axios from "axios";
import { convertBlobtofile, optionValue } from "../utils";
const editormodal = new bootstrap.Modal(
    document.querySelector("#editore_modal_overlay")
);

// get_lesson_idand_corse_id
const urlString = window.location.href;
const regex = /\/lesson\/(\d+)\/create-assessments/;
const match = urlString.match(regex);
const lessonId = match ? match[1] : null;

const regexcourse = /\/courses\/(\d+)\/lesson/;
const matchcourse = urlString.match(regexcourse);
const courseId = matchcourse ? matchcourse[1] : null;

const closeBtn = document.getElementById("close_modal");
const editor = document.querySelector('[aria-details="content_placeholder"]');
const doneBtn = document.getElementById("editor_done");
document.getElementById("add-question").addEventListener("click", function () {
    const questionCount = document.getElementsByClassName("question").length;
    addQuestion(questionCount);
});

let ParentProviderwrapper;

// open-modal-for-each-input-btn
function addEvenlistenerstoEditors() {
    const editormodalbtns = document.querySelectorAll(".custom-editor");

    editormodalbtns.forEach((action) => {
        action.addEventListener("click", (e) => {
            // get the-cureently-clikced-with-id-and manipulate the dom
            const nodesel = e?.currentTarget;
            const parentapp = nodesel.querySelector(".editor-content");
            ParentProviderwrapper = parentapp;

            // if (ParentProviderwrapper.childNod)
            const checknode = ParentProviderwrapper.childNodes;
            if (checknode !== null && typeof checknode === "object") {
                // spread across the nodes
                let newnode = [...checknode].filter((nodetypes) => {
                    return nodetypes.nodeType === Node.ELEMENT_NODE;
                });

                // then-map-through-thecleared-array
                for (let i = 0; i < newnode.length; i++) {
                    editor?.appendChild(newnode[i]);
                }
            }

            editormodal.show();
        });
    });
}

addEvenlistenerstoEditors();
const CloseModal = () => {
    editormodal.hide();
};

// don-button-implemetatio
// let valueText;
doneBtn.addEventListener("click", () => {
    const inputVal = editor?.getElementsByTagName("p")[0];
    const audio = editor?.getElementsByTagName("audio")[0];
    const video = editor?.getElementsByTagName("video")[0];
    const image = editor?.getElementsByTagName("img")[0];
    let media = [];

    if (inputVal) {
        ParentProviderwrapper.appendChild(inputVal);
    } else if (!inputVal) {
        let ptag = document.createElement("p");
        ptag.textContent = editor.textContent.trim();
        ParentProviderwrapper.appendChild(ptag);
    }

    if (audio || video || image) {
        media.push(audio) || null;
        media.push(video) || null;
        media.push(image) || null;

        let newmedia = media.filter((filtered) => {
            return (
                typeof filtered !== "undefined" && typeof filtered === "object"
            );
        });
        // append-the-processed-filtered-list
        newmedia.map((media) => {
            ParentProviderwrapper.appendChild(media);
        });
    }

    // clean-everythingin-node-upon-done
    for (const nodes of editor.childNodes) {
        nodes.remove();
        CloseModal();
    }
});

closeBtn.onclick = function () {
    CloseModal();
};

function addQuestion(questionCount) {
    var container = document.getElementById("questions-container");
    var newQuestion = document.createElement("div");
    newQuestion.className = "question mt-4";
    newQuestion.innerHTML = `
        <div class="form-group">
            <label for="question_${questionCount}">Question ${
        questionCount + 1
    }</label>
            <div class="custom-editor" id="custom-editor-${questionCount}">
                <div aria-details="content_container" class="editor-content" id="editor-content-${questionCount}"></div>
            </div>
        </div>
        <div class="form-group options_group">
            <label for="options">Options</label>
            ${["A", "B", "C", "D", "E"]
                .map(
                    (option) => `
                <div class="option">
                    <label for="option_${option.toLowerCase()}_${questionCount}">${option}:</label>
                    <div class="custom-editor" id="custom-editor-${questionCount}-${option.toLowerCase()}">
                        <div class="editor-content" id="editor-content-${questionCount}-${option.toLowerCase()}"></div>
                    </div>
                </div>
            `
                )
                .join("")}
        </div>

        <div class="form-group">
            <label for="correct_option_${questionCount}">Correct Option</label>
            <select name="questions[${questionCount}][correct_option]" class="form-control correct_option" id="correct_option_${questionCount}" required>
                ${["A", "B", "C", "D", "E"]
                    .map(
                        (option) => `
                    <option value="${option}">${option}</option>
                `
                    )
                    .join("")}
            </select>
        </div>
        <div class="form-group">
            <label for="value_${questionCount}">Question Value</label>
            <input type="number" name="questions[${questionCount}][value]" class="form-control question_value" id="value_${questionCount}" required>
        </div>
        <button type="button" class="btn btn-danger btn-sm remove-question">Remove Question</button>
    `;
    container.appendChild(newQuestion);
    addEvenlistenerstoEditors();
}

document.addEventListener("click", function (event) {
    if (event.target.classList.contains("remove-question")) {
        event.target.closest(".question").remove();
    }
});

const submitBtn = document.getElementById("create_assessment");

// call-function-
// console.log(optionValue("p"));
submitBtn.addEventListener("click", async () => {
    const csrftoken = document.querySelector("input[name=_token]")?.value;
    const timelimit = document.querySelector("#time_limit").value;

    const questionsData = [];
    let questionId = 1;
    let optionid = 1;

    // Get all question elements
    const questionsSet = document.querySelectorAll(".question");

    for (const quest of questionsSet) {
        const questionText =
            quest.querySelector('[aria-details="content_container"] p')
                ?.textContent || "";
        const audioPath =
            quest.querySelector('[aria-details="content_container"] audio')
                ?.src || "";
        const videoPath =
            quest.querySelector('[aria-details="content_container"] video')
                ?.src || "";
        const imagePath =
            quest.querySelector('[aria-details="content_container"] img')
                ?.src || "";

        const points = quest.querySelector(".question_value")?.value;

        const correctOption = quest.querySelector(".correct_option").value;
        const correctIndex = optionValue(correctOption);

        const optionsData = [];

        const optionsLayer = quest.querySelectorAll(".options_group");

        for (const opt of optionsLayer) {
            const optionsLoop = opt.querySelectorAll(".editor-content");

            for (const optItem of optionsLoop) {
                const optionText =
                    optItem.querySelector("p")?.textContent || "";
                const optionAudio = optItem.querySelector("audio")?.src || "";
                const optionVideo = optItem.querySelector("video")?.src || "";
                const optionImage = optItem.querySelector("img")?.src || "";
                const optindex = Array.from(optionsLoop).indexOf(optItem);
                const is_correct = optindex === correctIndex;

                optionsData.push({
                    id: optionid++,
                    option_text: optionText,
                    media: {
                        image_path: await convertBlobtofile(
                            optionImage,
                            "image",
                            optionText
                        ),
                        audio_path: await convertBlobtofile(
                            optionAudio,
                            "audio",
                            optionText
                        ),
                        video_path: await convertBlobtofile(
                            optionVideo,
                            "video",
                            optionText
                        ),
                    },
                    is_correct,
                });
            }
        }

        questionsData.push({
            id: questionId++,
            question_text: questionText,
            points,
            media: {
                image_path: await convertBlobtofile(imagePath, "image"),
                audio_path: await convertBlobtofile(audioPath, "audio"),
                video_path: await convertBlobtofile(videoPath, "video"),
            },
            options: optionsData,
        });
    }

    const payload = {
        general_time_limit: timelimit,
        questions: questionsData,
    };

    console.log("Form Data:", payload);
    //    send-post-requst-to-db
    try {
        const response = await axios.post(
            `/admin/courses/${courseId}/lesson/${lessonId}/create-assessment`,
            { ...payload },
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                    "X-CSRF-Token": csrftoken,
                    Accept: "application/json",
                },
            }
        );
        // const data = await response.data;
        response && window.alert("Submission Successfull");
    } catch (err) {
        window.alert("Error Check Console for details", err.status);
    }
});
