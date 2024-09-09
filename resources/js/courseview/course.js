import * as bootstrap from "bootstrap";
import { convertBlobtofile } from "../utils";
import axios from "axios";

// Codes-for-course-view-BeUnloadEvent.php
const editorAdd = new bootstrap.Modal(
    document.querySelector("#editore_modal_overlay_lesson")
);

const btnShow = document.querySelector("#addLesson");

btnShow.addEventListener("click", () => {
    editorAdd.show();
});

const closeLesson = document.querySelector("#close_modal_lesson");

closeLesson.addEventListener("click", () => {
    editorAdd.hide();
});

// Attach event listener
const donelessonBtn = document.querySelector(
    "#editore_modal_overlay_lesson #all_lesson_done"
);
// done-button-fucntionality
donelessonBtn.addEventListener("click", handleClick);
const editor = document.querySelector('[aria-details="content_placeholder"]');
// Function to handle the click event
async function handleClick() {
    const lessonName = document.querySelector("#lesson_name");
    const inputVal = editor?.getElementsByTagName("p")[0];

    console.log(inputVal);
    if (lessonName.value.trim() === "") {
        window.alert("Lesson name is required");
        return;
    }
    if (inputVal.value === "") {
        window.alert("Lesson Title/Descritption is required");
        return;
    }
    const csrftoken = document.querySelector("input[name=_token]")?.value;

    if (!csrftoken) {
        console.error("CSRF token not found");
        alert("Missing Token");
        return;
    }

    const audioup = editor?.getElementsByTagName("audio")[0]?.src || "";
    const videoupd = editor?.getElementsByTagName("video")[0]?.src || "";
    const imageupd = editor?.getElementsByTagName("img")[0]?.src || "";

    const formoptions = {
        name: lessonName?.value,
        description: inputVal.innerHTML || "",
        image: await convertBlobtofile(imageupd, "image", inputVal),
        audio: await convertBlobtofile(audioup, "audio", inputVal),
        video: await convertBlobtofile(videoupd, "video", inputVal),
    };
    try {
        const response = await axios.post(
            `/admin/course/8/lessons`,
            { ...formoptions },
            {
                method: "POST",
                headers: {
                    "Content-Type": "multipart/form-data",
                    "X-CSRF-Token": csrftoken,
                },
            }
        );
        // TODO:add-logic-to-reload-after-success-respoonse
        if (response) {
            confirm("Lesson Added Successfully!");
            window.location.reload();
        }
    } catch (error) {
        console.error("Error adding lesson:", error);
        window.alert("Failed to add lesson. Check console for details.");
    }
}

// add-events-to-divs-for currenlty-clicked
const divs = document.querySelectorAll(".container_lesson_body");

// Loop through each div and add a click event listener
divs.forEach((div) => {
    div.addEventListener("click", (event) => {
        const clickedDiv = event.currentTarget;
        divs.forEach((div) => (div.style.border = ""));
        clickedDiv.style.border = "2px solid #5a48c8";
    });
});
