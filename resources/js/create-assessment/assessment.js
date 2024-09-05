import * as bootstrap from "bootstrap";
const editormodal = new bootstrap.Modal(
    document.querySelector("#editore_modal_overlay")
);

const editormodalbtns = document.querySelectorAll(".custom-editor");
const closeBtn = document.getElementById("close_modal");

const editor = document.querySelector('[aria-details="content_placeholder"]');
const doneBtn = document.getElementById("editor_done");

function initializeEditor(id) {
    // Initialize any specific editor logic if necessary
    console.log("Initializing editor:", id);
    // For example, you could add specific toolbar actions here
}
let ParentProviderwrapper;

// open-modal-for-each-input-btn
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
                <div contenteditable="true" class="editor-content" id="editor-content-${questionCount}"></div>
                <input type="hidden" name="questions[${questionCount}][question]" id="hidden-editor-content-${questionCount}">
            </div>
        </div>
        <div class="form-group">
            <label for="options">Options</label>
            ${["A", "B", "C", "D", "E"]
                .map(
                    (option) => `
                <div class="option">
                    <label for="option_${option.toLowerCase()}_${questionCount}">${option}:</label>
                    <div class="custom-editor" id="custom-editor-${questionCount}-${option.toLowerCase()}">
                        <div contenteditable="true" class="editor-content" id="editor-content-${questionCount}-${option.toLowerCase()}"></div>
                        <input type="hidden" name="questions[${questionCount}][options][${option}]" id="hidden-editor-content-${questionCount}-${option.toLowerCase()}">
                    </div>
                </div>
            `
                )
                .join("")}
        </div>
        <div class="form-group">
            <label for="correct_option_${questionCount}">Correct Option</label>
            <select name="questions[${questionCount}][correct_option]" class="form-control" id="correct_option_${questionCount}" required>
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
            <input type="number" name="questions[${questionCount}][value]" class="form-control" id="value_${questionCount}" required>
        </div>
        <button type="button" class="btn btn-danger btn-sm remove-question">Remove Question</button>
    `;
    container.appendChild(newQuestion);

    // Initialize editors for the new question and options
    initializeEditor(`editor-content-${questionCount}`);
    ["A", "B", "C", "D", "E"].forEach((option) => {
        initializeEditor(
            `editor-content-${questionCount}-${option.toLowerCase()}`
        );
    });
}

// document.getElementById("add-question").addEventListener("click", function () {
//     var questionCount = document.getElementsByClassName("question").length;
//     addQuestion(questionCount);
// });

// document.addEventListener("click", function (event) {
//     if (event.target.classList.contains("remove-question")) {
//         event.target.closest(".question").remove();
//     }
// });

// document.querySelector("form").addEventListener("submit", function () {
//     document.querySelectorAll(".custom-editor").forEach(function (editor) {
//         var editorContent = editor.querySelector(".editor-content").innerHTML;
//         editor.querySelector('input[type="hidden"]').value = editorContent;
//     });
// });

// initializeEditor("editor-content-0");
// ["A", "B", "C", "D", "E"].forEach((option) => {
//     initializeEditor(`editor-content-0-${option.toLowerCase()}`);
// });
