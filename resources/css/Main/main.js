import axios from "axios";
import * as bootstrap from "bootstrap";
import {
    getDuration,
    flushNodes,
    handleUpload,
    initializeplayers,
} from "../../js/helpers";
const csrftoken = document.querySelector("input[name=_token]")?.value;
import { convertBlobtofile } from "../../js/utils";
const currenturl = window.location.href;
const regex = /\/admin\/dawah\/(\d+)$/;
const match = currenturl.match(regex);
let slugid;
match && (slugid = match[1]);
const parentcontainer = document.querySelector(".container_view_lecturer");
// Initialize the audio player

// Optional: Get the origin URL
const originUrl = window.location.origin;

// dawah-view-js-start
const Daheeselect = document.getElementById("dahee_select");

Daheeselect?.addEventListener("click", () => {
    currenturl.endsWith("/")
        ? window.open(window.location.href.concat("2"), "_self")
        : window.open(window.location.href.concat("/2"), "_self");
});

let currentDefault = `audio`;
// intialize-players()
initializeplayers();
const spinnerFetch = document.querySelector(".loader_spinner_lecturer");
const spinnerLoad = document.querySelector(".error_loader");

// useeffect
window.onload = function () {
    fetchlecturer();
};
// fetching-function
async function fetchlecturer() {
    flushNodes(parentcontainer);
    spinnerFetch && (spinnerFetch.style.display = "block");
    try {
        const request = await axios.get(`/admin/daheeh/${slugid}`);
        const { data } = request;
        updateInterface(data);
    } catch (err) {
        console.log(err);
        if (err) {
            const message = err?.response?.data?.message;
            if (err?.response?.status == 400) {
                spinnerLoad.querySelector("h5").innerText = message;
            } else {
                window.alert("server error occured");
            }
        }
    } finally {
        spinnerFetch.style.display = "none";
        spinnerLoad.classList.remove("no_display");
    }
}
let audioLectures = [];
let videoLectures = [];
function updateInterface(datas) {
    // audio-lectures
    audioLectures = datas?.uploads.filter((lecture) =>
        lecture.uploads.some((upload) => upload.audio)
    );

    // video-lectures
    videoLectures = datas?.uploads.filter((lecture) =>
        lecture.uploads.some((upload) => upload.video)
    );

    const parensection = document.createElement("section");
    parensection.className = "lecturer_view_container";
    parensection.innerHTML = `
        <section class="lecturer_view_container">
            <section class="lecturer_bio">
                <img src = "${
                    datas.avatar_url ||
                    window.location.origin + "/images/Qari.jpeg"
                }" alt="dahee_image" style="width: 200px;height: 200px; border-radius: 50%;object-fit:cover;">
                <div id="name_lecturer">
                    <h4>${datas?.dahee_name}</h4>
                    <p style="max-width: 100ch">${
                        datas.bio ||
                        `An esteemed Islamic scholar with a deep and comprehensive understanding of traditional Islamic teachings. Well-versed in various branches of Islamic knowledge, including jurisprudence, theology, and classical Arabic, this scholar has dedicated years to studying the Qur'an, Hadith, and the works of prominent Islamic scholars throughout history. Their expertise encompasses both foundational religious principles and contemporary issues, enabling them to offer insightful guidance and interpretations that remain true to the core tenets of Islam`
                    }
                    </p>
                    <button class="btn btn-primary md">
                        <i class="fas fa-play"></i>
                        Play Radio
                    </button>
                </div>
            </section>
            <! -- div-content-area --!>

            <section class="upload_contents">
               
                <div class="lecture_switcher">
                    <div class="d-flex" style="gap: 3rem;">
                        <div class="switcher_toggle activePane">Audio</div>
                        <div class="switcher_toggle">Video</div>
                    </div>
                </div>
            </section>

            <!- -- is-uploaded-medias-- !>
              
           <div class="media_targets">
  ${
      currentDefault === "audio"
          ? `
        ${audioLectures
            ?.map((audios, index) => {
                return audios?.length < 1
                    ? `<h5>No Audio Yet!</h5>`
                    : `
        <div class="uploaded_media">
            <div class="media_audio_container">
                <div class="play_name_container">
                    <div class="play_icon_container">
                        <i class="fa fa-play play_icon play_hover" aria-hidden="true"></i>
                    </div>
                    <div style="font-size: 1.2rem" aria-details="audio-title">
                        ${audios?.lecturetitle || `Lecture ${index}`}
                    </div>
                    <div id="hidden_source" data-attribute="${
                        audios?.uploads[0]?.audio
                    }"></div>
                </div>
                <div>
                    <i class="fas fa-download play_icon small_icon" aria-hidden="true"></i>
                </div>
            </div>
        </div>`;
            })
            .join("")}
      `
          : ""
  }
</div>

         
  
        </section>
    `;

    parentcontainer?.appendChild(parensection);
    // trigget-divs-after-renders-too-to-auto-update
    // runtrigger-divs-autoatically
    triggerDivs();
}

// ----------USING-EVENT-DELEGATIONS--------

function triggerDivs() {
    // select-all-audio-divs-andmaped-them-to-trigger-audio-play
    const parentprovider = document.querySelector(".media_targets");
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
            audiotg?.setAttribute("src", `${audiodatacurr}`);
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

    triggerbutton(parentprovider);
}

// runtrigger-divs-autoatically
triggerDivs();
// dawa-view-js-end
function triggerbutton(parent) {
    const toggleSwitcher = document.querySelectorAll(".switcher_toggle");
    toggleSwitcher?.forEach((switcher) => {
        switcher.addEventListener("click", setActive);
    });

    function setActive(e) {
        toggleSwitcher?.forEach((nav) => {
            nav.classList.remove("activePane");
            flushNodes(parent);
        });

        e.currentTarget.classList.add("activePane");
        currentDefault = e.currentTarget.innerText.toLowerCase();
        // auto-update-baed-on-vide-or-audio
        rendercurrent();
        initializeplayers();
    }

    function rendercurrent() {
        let contendata = document.createElement("section");
        if (currentDefault === "audio") {
            contendata.innerHTML = `
        ${audioLectures
            ?.map((audios, index) => {
                return audios?.length < 1
                    ? `<h5>No Audio Yet!</h5>`
                    : `
        <div class="uploaded_media">
            <div class="media_audio_container">
                <div class="play_name_container">
                    <div class="play_icon_container">
                        <i class="fa fa-play play_icon play_hover" aria-hidden="true"></i>
                    </div>
                    <div style="font-size: 1.2rem" aria-details="audio-title">
                        ${audios?.lecturetitle || `Lecture ${index}`}
                    </div>
                    <div id="hidden_source" data-attribute="${
                        audios?.uploads[0]?.audio
                    }"></div>
                </div>
                <div>
                    <i class="fas fa-download play_icon small_icon" aria-hidden="true"></i>
                </div>
            </div>
        </div>`;
            })
            .join("")}
      `;
        } else {
            contendata.innerHTML = `
        ${videoLectures
            ?.map((videos, index) => {
                return videos?.length < 1
                    ? `<h5>No Videos Yet!</h5>`
                    : `
        <div class="uploaded_media is_video">
            <div class="media_video_conainer">
                <div>
                    <video src="${
                        videos?.uploads[0]?.video
                    }" controls crossorigin playsinline></video>
                </div>
              
                <div class="video_footer">
                    <h6 class="video-name">
                        ${videos?.lecturetitle || `Lecture ${index}`}
                    </h6>
                          
                    <h6 id="video_length">
                    </h6>
                </div>
            </div>
        </div>`;
            })
            .join("")}
      `;
        }
        parent.appendChild(contendata);
    }
}
// ---------------------DAHEE/ADMIN-DAWAHVIEW-------------
const uploaddawahBtn = document.querySelector("#upload_button");
// load-modal-up
const backdropmodal = document.querySelector("#upload_lecture");
let updModal;
if (backdropmodal) {
    updModal = new bootstrap.Modal(backdropmodal);
}

document
    .getElementById("upload_close")
    ?.addEventListener("click", () => updModal.hide());
uploaddawahBtn?.addEventListener("click", () => updModal.show());

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
const loaderbtn = document.querySelector(".loader_button_done");
doneButton?.addEventListener("click", async () => {
    loaderbtn.classList.add("show_load");
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
        loaderbtn.classList.remove("show_load");
        updModal.hide();
        request && window.alert("Upload Successful!!");
    } catch (err) {
        loaderbtn.classList.remove("show_load");
        updModal.hide();
        window.alert("Error uploading:" + err?.response?.data?.message);
    }
});

// ---------------------DAHEE/ADMIN-DAWAHVIEENDS-------------
