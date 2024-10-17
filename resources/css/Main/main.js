import axios from "axios";
const currenturl = window.location.href;
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
    toggleSwitcher.forEach((nav) => nav.classList.remove("active"));

    e.currentTarget.classList.add("active");
    currentDefault = e.currentTarget.innerText.toLowerCase();
    // refetchAPIqueryparamschanges
    // fetchScoreboards();
}

const player = new Plyr("audio", {});
// Expose player so it can be used from the console
window.player = player;

// dawa-view-js-end
