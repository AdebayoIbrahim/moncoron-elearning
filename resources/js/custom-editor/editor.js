// resources/js/custom-editor/editor.js

document.addEventListener("DOMContentLoaded", function () {
    const boldBtn = document.querySelector("#bold-btn");
    const editor = document.querySelector("#custom-editor");

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

    // Trigger video upload input on icon click
    videoIcon.addEventListener("click", () => {
        videoUpload.click();
    });
    audioUpload.addEventListener("click", () => {
        // window.alert("audio");
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
    // imageUpload.addEventListener("change", (e) => {
    //     const src = e.target.files[0];
    //     handleUpload("image", src);
    // });

    // Image upload
    // imageUpload.addEventListener("change", function (e) {
    //     const file = e.target.files[0];
    //     if (file && file.type.startsWith('image/')) {
    //         const formData = new FormData();
    //         formData.append('file', file);

    //         fetch('{{ route('editor.upload') }}', {
    //             method: 'POST',
    //             headers: {
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //             },
    //             body: formData
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             const img = document.createElement('img');
    //             img.src = data.url;
    //             img.style.maxWidth = "100%";
    //             editor.appendChild(img);
    //         })
    //         .catch(error => {
    //             console.error('Image upload failed:', error);
    //         });
    //     } else {
    //         alert("Please select a valid image file.");
    //     }
    // });

    // Video upload
    // videoUpload.addEventListener("change", function (e) {
    //     const file = e.target.files[0];
    //     if (file && file.type.startsWith('video/')) {
    //         const formData = new FormData();
    //         formData.append('file', file);

    //         fetch('{{ route('editor.upload') }}', {
    //             method: 'POST',
    //             headers: {
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    //             },
    //             body: formData
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             const video = document.createElement('video');
    //             video.src = data.url;
    //             video.controls = true;
    //             video.style.maxWidth = "100%";
    //             editor.appendChild(video);
    //         })
    //         .catch(error => {
    //             console.error('Video upload failed:', error);
    //         });
    //     } else {
    //         alert("Please select a valid video file.");
    //     }
    // });

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
                    td.style.border = "1px solid #000";
                    td.style.padding = "5px";
                    tr.appendChild(td);
                }
                table.appendChild(tr);
            }
            editor.appendChild(table);
        }
    });

    //  const saveContentUrl = "{{ route('editor.save') }}"

    // Autosave every 30 seconds
    // setInterval(() => {
    //     const content = editor.innerHTML;
    //     fetch("/editor/save", {
    //         method: "POST",
    //         headers: {
    //             "Content-Type": "application/json",
    //         },
    //         body: JSON.stringify({ content }),
    //     })
    //         .then((response) => response.json())
    //         .then((data) => {
    //             console.log("Autosaved:", data);
    //         })
    //         .catch((error) => {
    //             console.error("Autosave error:", error);
    //         });
    // }, 30000); // 30000ms = 30 seconds
});
