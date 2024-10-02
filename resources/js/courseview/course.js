import * as bootstrap from "bootstrap";
import { convertBlobtofile } from "../utils";
import axios from "axios";
const urlString = window.location.href;
const regexcourse = /\/courses\/(\d+)/;
const matchcourse = urlString.match(regexcourse);
const courseId = matchcourse ? matchcourse[1] : null;
// Codes-for-course-view-BeUnloadEvent.php
const editorAdd = new bootstrap.Modal(
    document.querySelector("#editore_modal_overlay_lesson")
);

const btnShow = document.querySelector("#addLesson");

btnShow?.addEventListener("click", () => {
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
            `/admin/course/${courseId}/lessons`,
            { ...formoptions },
            {
                method: "POST",
                headers: {
                    "Content-Type": "multipart/form-data",
                    "X-CSRF-Token": csrftoken,
                    Accept: "application/json",
                },
            }
        );
        // TODO:add-logic-to-reload-after-success-respoonse
        if (response.ok) {
            confirm("Lesson Added Successfully!");
            window.location.reload();
        } else {
            window.alert("Failed to add lesson. Check console for details.");
        }
    } catch (error) {
        console.error("Error adding lesson:", error);
    }
}

const divs = document.querySelectorAll(".container_lesson_body");
// Loop through each div and add a click event listener
divs.forEach((div, index) => {
    // algorithm-check-if-prev-completed
    if (index === 0) {
    } else {
        // Get the previous div
        const prevDiv = divs[index - 1];
        if (prevDiv.getAttribute("data_attribute") === "current") {
            // do-nothing
        } else {
            div.style.background = "orange";
        }
    }

    div.addEventListener("click", (event) => {
        const clickedDiv = event.currentTarget;
        divs.forEach((div) => (div.style.border = ""));
        clickedDiv.style.border = "2px solid #5a48c8";
    });
});
const nextbtn = document?.querySelector("#next_btn_lesson");
nextbtn?.addEventListener("click", () => {
    const refreshdivs = document.querySelectorAll(".container_lesson_body");
    const selectedDiv = Array.from(refreshdivs).find((div) => {
        return div.style.border === "2px solid rgb(90, 72, 200)";
    });
    if (!selectedDiv) {
        window.alert("No Lesson Selected");
    } else {
        // another-approach
        let lessonid = selectedDiv
            ?.querySelector(".target_holder")
            .textContent.trim();

        if (lessonid.trim() !== "") {
            // append-location-to-theurl
            window.open(
                window.location.href.concat(`/lesson/${Number(lessonid)}`),
                "_self"
            );
        }
    }
});
