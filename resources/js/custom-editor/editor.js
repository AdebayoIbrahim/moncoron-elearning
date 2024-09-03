// resources/js/custom-editor/editor.js
import { convertBlobtofile } from "../utils";
document.addEventListener("DOMContentLoaded", function () {
    const boldBtn = document.querySelector("#bold-btn");
    const editor = document.querySelector("#custom-editor");
    const submitBtn = document.querySelector(
        '[aria-details = "submit-button"]'
    );
    // Toolbar buttons
    const italicBtn = document.querySelector("#italic-btn");
    const underlineBtn = document.querySelector("#underline-btn");
    const h1Btn = document.querySelector("#h1-btn");
    const h2Btn = document.querySelector("#h2-btn");
    const ulBtn = document.querySelector("#ul-btn");
    const olBtn = document.querySelector("#ol-btn");
    const blockquoteBtn = document.querySelector("#blockquote-btn");
    const linkBtn = document.querySelector("#link-btn");
    const undoBtn = document.querySelector("#undo-btn");
    const redoBtn = document.querySelector("#redo-btn");
    const textColorPicker = document.querySelector("#text-color-picker");
    const bgColorPicker = document.querySelector("#bg-color-picker");
    const imageIcon = document.querySelector("#image-icon");
    const videoIcon = document.querySelector("#video-icon");
    const imageUpload = document.querySelector("#image-upload");
    const videoUpload = document.querySelector("#video-upload");
    const tableBtn = document.querySelector("#table-btn");
    const videoUrlBtn = document.querySelector("#video-url-btn");
    const audioinput = document.getElementById("audio-upload");
    const audioUpload = document.querySelector("#audio-icon");
    // Basic formatting
    boldBtn.addEventListener("click", () => {
        document.execCommand("bold");
        // window.alert("Check-eorked")
        // console.log("boleeee")
    });

    italicBtn.addEventListener("click", () => {
        document.execCommand("italic");
    });

    underlineBtn.addEventListener("click", () => {
        document.execCommand("underline");
    });

    // Headings
    h1Btn.addEventListener("click", () => {
        document.execCommand("formatBlock", false, "H1");
    });

    h2Btn.addEventListener("click", () => {
        document.execCommand("formatBlock", false, "H2");
    });

    // Lists
    ulBtn.addEventListener("click", () => {
        document.execCommand("insertUnorderedList");
    });

    olBtn.addEventListener("click", () => {
        document.execCommand("insertOrderedList");
    });

    // Blockquote
    blockquoteBtn.addEventListener("click", () => {
        document.execCommand("formatBlock", false, "BLOCKQUOTE");
    });

    // Link
    linkBtn.addEventListener("click", () => {
        const url = prompt("Enter the URL");
        if (url) {
            document.execCommand("createLink", false, url);
        }
    });

    // Undo/Redo
    undoBtn.addEventListener("click", () => {
        document.execCommand("undo");
    });

    redoBtn.addEventListener("click", () => {
        document.execCommand("redo");
    });

    // Text Color
    textColorPicker.addEventListener("input", () => {
        document.execCommand("foreColor", false, textColorPicker.value);
    });

    // Background Color
    bgColorPicker.addEventListener("input", () => {
        document.execCommand("hiliteColor", false, bgColorPicker.value);
    });

    // Trigger image upload input on icon click
    imageIcon.addEventListener("click", () => {
        imageUpload.click();
    });

    // Trigger video upload input on icon click-pass
    videoIcon.addEventListener("click", () => {
        videoUpload.click();
    });
    audioUpload.addEventListener("click", () => {
        audioinput.click();
    });

    // hybrid-upload
    function handleUpload(type, upload) {
        let format;
        console.log(`out${type}`);
        if (upload) {
            console.log(`in${upload}`);
            // create-mock-image or audio
            // blob_url
            const url = URL.createObjectURL(upload);
            switch (type) {
                case "image":
                    format = document.createElement(`img`);
                    format.setAttribute("src", url);
                    format.setAttribute("alt", upload?.name);
                    format.classList.add("pop_upload_file");
                    break;
                case "video":
                    format = document.createElement(`video`);
                    format.setAttribute("src", url);
                    format.setAttribute("controls", true);
                    format.setAttribute("autoplay", false);
                    format.classList.add("pop_upload_file");
                    break;
                case "audio":
                    format = document.createElement(`audio`);
                    format.setAttribute("src", url);
                    format.setAttribute("controls", true);
                    break;
                default:
                    throw new Error("unknown file type");
            }
        }
        editor.appendChild(format);
    }

    // image-upload

    const updarr = [imageUpload, videoUpload, audioinput];
    updarr.forEach((upd, index) => {
        upd.addEventListener("change", (e) => {
            const src = e.target.files[0];
            handleUpload(
                `${index === 0 ? "image" : index === 1 ? "video" : "audio"}`,
                src
            );
        });
    });

    // Embed Video URL
    videoUrlBtn.addEventListener("click", () => {
        const url = prompt("Enter the video URL");
        if (url) {
            const video = document.createElement("iframe");
            video.src = url;
            video.width = "560";
            video.height = "315";
            video.frameBorder = "0";
            video.allow =
                "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
            video.allowFullscreen = true;
            editor.appendChild(video);
        }
    });

    // Insert Table
    tableBtn.addEventListener("click", () => {
        const rows = prompt("Enter number of rows");
        const cols = prompt("Enter number of columns");
        if (rows && cols) {
            const table = document.createElement("table");
            table.style.width = "100%";
            table.style.borderCollapse = "collapse";
            for (let i = 0; i < rows; i++) {
                const tr = document.createElement("tr");
                for (let j = 0; j < cols; j++) {
                    const td = document.createElement("td");
                    td.style.border = "1px solid #000000";
                    td.style.padding = "5px";
                    tr.appendChild(td);
                }
                table.appendChild(tr);
            }
            editor.appendChild(table);
        }
    });

    const savePointurl = "/editor/save";

    const csrftoken = document.querySelector("input[name=_token]")?.value;
    submitBtn.addEventListener("click", async () => {
        //    select-next-pelement-in-editor
        let valueText;

        if (editor.getElementsByTagName("p")) {
            valueText = editor?.getElementsByTagName("p")[0].textContent;
        } else {
            valueText = document.querySelector("#editor-content").value;
        }

        console.log(valueText);

        // get-images-audio-or-videofile
        const imageupd = editor
            ?.getElementsByTagName("img")[0]
            .getAttribute("src");

        const videupd = editor
            ?.getElementsByTagName("video")[0]
            .getAttribute("src");

        const audioup = editor
            ?.getElementsByTagName("audio")[0]
            .getAttribute("src");

        const formdataoptions = {
            content: valueText,
            image: await convertBlobtofile(imageupd, "image", valueText),
            audio: await convertBlobtofile(audioup, "audio", valueText),
            video: await convertBlobtofile(videupd, "video", valueText),
        };

        // loop-through-fields-and-append-key-value-pair
        function formData() {
            const forms = new FormData();
            for (const [key, val] of Object.entries(formdataoptions)) {
                forms.append(key, val);
            }
            return forms;
        }

        const requsetSend = async () => {
            try {
                const response = await fetch(savePointurl, {
                    method: "POST",
                    headers: {
                        "X-CSRF-Token": csrftoken,
                        Accept: "application/json",
                    },
                    body: formData(),
                });
                console.log(response);
                console.log(response);
            } catch (err) {
                console.log(err);
                window.alert("Failed To Save with unknown error");
            }
        };

        requsetSend();
    });
});

// submitform
