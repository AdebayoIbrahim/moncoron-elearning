import axios from "axios";
const currenturl = window.location.href;
const player = new Plyr("audio", {});
const originUrl = window.location.origin;
window.player = player;
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
// dawa-view-js-end
