import axios from "axios";
import { flushNodes } from "../helpers";

let currentDefault = `global`;
const toggleSwitcher = document.querySelectorAll(".switcher_toggle");

toggleSwitcher.forEach((switcher) => {
    switcher.addEventListener("click", setActive);
});

function setActive(e) {
    toggleSwitcher.forEach((nav) => nav.classList.remove("current"));

    e.currentTarget.classList.add("current");
    currentDefault = e.currentTarget.innerText.toLowerCase();
    // refetchAPIqueryparamschanges
    fetchScoreboards();
}

const spinner = document.querySelector(".spinner_size");
const nulltext = document.querySelector("#empty_text");
const scoreboardCt = document.querySelector("#scoreboards");
// new-mapped-datas
const divParent = document.createElement(`div`);
divParent.className = "leaderboard_users";
// first-next-div
const divsubparent = document.createElement(`div`);
divsubparent.className = "leader_board_fst_flex";
// child-sub-parent
const childNumbering = document.createElement(`div`);
childNumbering.className = "number_sort";
const Avatar = document.createElement(`div`);
const usrname = document.createElement(`div`);
usrname.className = "user-name_leaderboard";

const MCPoints = document.createElement("div");
MCPoints.className = "points_user";

// fething-scorebaord-useeffect
window.onload = function () {
    fetchScoreboards();
};
async function fetchScoreboards() {
    flushNodes(scoreboardCt);
    scoreboardCt.style.display = "none";
    spinner.style.display = "block";

    try {
        const response = await axios.get(`/scoreboard?type=${currentDefault}`);
        if (response) {
            handleupdate(response?.data);
        }
    } catch (err) {
        console.error(err);
        window.alert("Error Getting Scoreboard!");
    }
}
function handleupdate(data) {
    if (data.length < 1) {
        spinner.style.display = "none";
        nulltext.style.display = "block";
    } else {
        // runa-loop-on-the-datas
        data.forEach((element, i) => {
            // thendiv-parent-apeends-too
            divParent.appendChild(divsubparent);
            // subparent-contains-3-elements
            divsubparent.appendChild(childNumbering);
            childNumbering.innerText = `${i + 1}`;
            // if-image-exist-then-mapimage
            divsubparent.appendChild(Avatar);
            Avatar.innerHTML = `<i class="fa-solid fa-user" style="font-size: 1.2rem"></i>`;
            divsubparent.appendChild(usrname);
            usrname.innerText = `${element?.user_name}`;

            divParent.appendChild(MCPoints);
            MCPoints.innerText = `${element?.points}MCP`;
            scoreboardCt.appendChild(divParent);
            // displayscoreboard
            scoreboardCt.style.display = "block";
            spinner.style.display = "none";
        });
    }
}
