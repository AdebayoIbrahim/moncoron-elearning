import axios from "axios";

let currentDefault = `global`;
const toggleSwitcher = document.querySelectorAll(".switcher_toggle");

toggleSwitcher.forEach((switcher) => {
    switcher.addEventListener("click", setActive);
});

function setActive(e) {
    toggleSwitcher.forEach((nav) => nav.classList.remove("current"));

    e.currentTarget.classList.add("current");
    currentDefault = e.currentTarget.innerText.toLowerCase();
    console.log(currentDefault);
}

// fething-scorebaord-useeffect
window.onload = async function () {
    try {
        const response = await axios.get(`/scoreboard?type=${currentDefault}`);
        console.log(response?.data);
    } catch (err) {
        console.error(err);
        window.alert("Error Getting Scoreboard!");
    }
};
