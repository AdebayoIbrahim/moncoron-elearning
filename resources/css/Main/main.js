import axios from "axios";
import { getDuration } from "../../js/helpers";
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
console.log("Origin URL:", originUrl);

// dawah-view-js-start
const Daheeselect = document.getElementById("dahee_select");

Daheeselect?.addEventListener("click", () => {
    currenturl.endsWith("/")
        ? window.open(window.location.href.concat("1"), "_self")
        : window.open(window.location.href.concat("/1"), "_self");
});

let currentDefault = `Audio`;
const toggleSwitcher = document.querySelectorAll(".switcher_toggle");

toggleSwitcher.forEach((switcher) => {
    switcher.addEventListener("click", setActive);
});

function setActive(e) {
    toggleSwitcher.forEach((nav) => nav.classList.remove("activePane"));

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
closeAudiobtn.onclick = function () {
    AudioOverlay?.classList.remove("audio-box-show");
};

// VIDEO-Related
const media_video = document.querySelectorAll(".media_video_conainer");
document.addEventListener("DOMContentLoaded", () => {
    media_video?.forEach((vid) => {
        const vidEl = vid?.getElementsByTagName("video")[0];
        getDuration(vidEl).then((duration) => {
            console.log(duration);
            // apeend-to-corresponding-text
            const textduration = vid?.querySelector("#video_length");
            textduration.innerText = duration;
        });
    });
});

// dawa-view-js-end
