import axios from "axios";
import * as bootstrap from "bootstrap";
import { getDuration, flushNodes, handleUpload } from "../../js/helpers";
const csrftoken = document.querySelector("input[name=_token]")?.value;
import { convertBlobtofile } from "../../js/utils";
const currenturl = window.location.href;
// Initialize the audio player
const audioElement = document.querySelector("audio");
if (audioElement) {
    const player = new Plyr(audioElement, {});
    window.player = player;
} else {
    console.warn("No audio element found.");
}

// Initialize video players
const videoElements = document.querySelectorAll("video");
if (videoElements.length > 0) {
    const players = Array.from(videoElements).map((video) => new Plyr(video));
    window.players = players;
} else {
    console.warn("No video elements found.");
}

// Optional: Get the origin URL
const originUrl = window.location.origin;

// dawah-view-js-start
const Daheeselect = document.getElementById("dahee_select");

Daheeselect?.addEventListener("click", () => {
    currenturl.endsWith("/")
        ? window.open(window.location.href.concat("1"), "_self")
        : window.open(window.location.href.concat("/1"), "_self");
});

let currentDefault = `Audio`;
const toggleSwitcher = document.querySelectorAll(".switcher_toggle");

toggleSwitcher?.forEach((switcher) => {
    switcher.addEventListener("click", setActive);
});

function setActive(e) {
    toggleSwitcher?.forEach((nav) => nav.classList.remove("activePane"));

    e.currentTarget.classList.add("activePane");
    currentDefault = e.currentTarget.innerText.toLowerCase();
    // refetchAPIqueryparamschanges
    // fetchScoreboards();
}

// select-all-audio-divs-andmaped-them-to-trigger-audio-pla

const Audiolist = document.querySelectorAll(".media_audio_container");
const AudioOverlay = document.querySelector(".absolute_player_audio");
const closeAudiobtn = document.querySelector(".close_audio");
Audiolist?.forEach((audiobtn) => {
    audiobtn.addEventListener("click", () => {
        // make -the -audio -src -empty
        const audiotg = AudioOverlay?.getElementsByTagName("audio")[0];
        // make-src-attr-rmpyu
        audiotg.setAttribute("src", "");

        // gethecurrently-clicked-element-and-target-the-audio-src
        const audiodatacurr = audiobtn
            ?.querySelector("#hidden_source")
            ?.getAttribute("data-attribute");

        // TODO: LOCAL TEST -ONLY
        // then-repass-the-current-audio-to-it
        audiotg?.setAttribute("src", `${originUrl}/${audiodatacurr}`);
        // then-trigger-display
        AudioOverlay.classList.add("audio-box-show");
        audiotg.play();
    });
});
closeAudiobtn?.addEventListener("click", function () {
    AudioOverlay?.classList.remove("audio-box-show");
});

// VIDEO-Related
const media_video = document.querySelectorAll(".media_video_conainer");
document.addEventListener("DOMContentLoaded", () => {
    media_video?.forEach((vid) => {
        const vidEl = vid?.getElementsByTagName("video")[0];
        getDuration(vidEl).then((duration) => {
            // apeend-to-corresponding-text
            const textduration = vid?.querySelector("#video_length");
            textduration.innerText = duration;
        });
    });
});

// dawa-view-js-end

// ---------------------DAHEE/ADMIN-DAWAHVIEW-------------
const uploaddawahBtn = document.querySelector("#upload_button");
// load-modal-up
const updModal = new bootstrap.Modal(document.querySelector("#upload_lecture"));
updModal.show();
const uploadClose = document
    .getElementById("upload_close")
    ?.addEventListener("click", () => updModal.hide());
uploaddawahBtn?.addEventListener("click", () => {});

// upload-container
const selecbutton = document.querySelector("#upload_media_type");
const textupload = document.querySelector(".text_helper_upload");
const uploadBtn = document.querySelector(".upload_input");
const uploaded_container = document.querySelector(".uploaded_file");
selecbutton?.addEventListener("change", () => {
    switch (selecbutton.value) {
        case "audio":
            textupload.textContent = "Upload Audio";
            uploadBtn.setAttribute("accept", "audio/*");
            break;
        case "video":
            textupload.textContent = "Upload Video";
            uploadBtn.setAttribute("accept", "video/*");
            break;
    }
});

uploadBtn?.addEventListener("click", (event) => {
    const select = document.querySelector("#upload_media_type");
    if (select?.value != "audio" && select?.value != "video") {
        window.alert(`Please choose a media type`);
        event.preventDefault(); // Prevent the file dialog from opening
        return;
    }
});

uploadBtn?.addEventListener("change", (e) => {
    // flush-container
    flushNodes(uploaded_container);
    let src = e.target.files[0];
    handleUpload(
        `${selecbutton.value === "audio" ? "audio" : "video"}`,
        src,
        uploaded_container
    );
});
const doneButton = document.getElementById("upload_done");

doneButton?.addEventListener("click", async () => {
    const lecturename = document.querySelector("#media_uload_name")?.value;
    const audioFile = uploaded_container
        ?.querySelector("audio")
        ?.getAttribute("src");
    const videoFile = uploaded_container
        ?.querySelector("video")
        ?.getAttribute("src");

    const lecture = {
        lecturetitle: lecturename,
        video: await convertBlobtofile(videoFile, "video", lecturename),
        audio: await convertBlobtofile(audioFile, "audio", lecturename),
    };
    try {
        const request = await axios.post(
            `/admin/dawah/upload/`,
            { ...lecture },
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                    "X-CSRF-Token": csrftoken,
                    Accept: "application/json",
                },
            }
        );

        console.log(request);
    } catch (err) {
        window.alert("error uploading.");
    }
});
// prepare-file-upload
// ---------------------DAHEE/ADMIN-DAWAHVIEENDS-------------
