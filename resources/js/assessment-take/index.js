const questionslist1 = (document.querySelectorAll(
    ".area-question-data"
)[0].style.display = "block");

const btnprogress = document
    .querySelectorAll("#box_navigate_cbt")
    .forEach((btnprogress) => {
        btnprogress.addEventListener("click", (e) => {
            document
                .querySelectorAll(".area-question-data")
                .forEach((quest) => {
                    quest.style.display = "none";
                });

            const currid = btnprogress.innerText;
            console.log(currid);
            document.querySelector(".questions-" + currid).style.display =
                "block";
        });
    });
