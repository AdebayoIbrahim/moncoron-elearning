import axios from "axios";

let currentDefault = `global`;
const toggleSwitcher = document.querySelectorAll(".switcher_toggle");

toggleSwitcher.forEach((switcher) => {
    switcher.addEventListener("click", setActive);
});

function setActive(e) {
    toggleSwitcher.forEach((nav) => nav.classList.remove("current"));

    e.currentTarget.classList.add("current");
}
