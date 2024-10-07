import html2pdf from "html2pdf.js";

// Confetti-js-scripts -start
const confettiDuration = 5000;
const animationEndTime = Date.now() + confettiDuration;
const confettiDefaults = {
    startVelocity: 40,
    spread: 360,
    ticks: 80,
    gravity: 0.8,
    zIndex: 1000,
    colors: [
        "#FFC107",
        "#FF5722",
        "#8BC34A",
        "#03A9F4",
        "#E91E63",
        "#9C27B0",
        "#673AB7",
    ],
};

function randomInRange(min, max) {
    return Math.random() * (max - min) + min;
}

const confettiInterval = setInterval(function () {
    const timeLeft = animationEndTime - Date.now();

    if (timeLeft <= 0) {
        clearInterval(confettiInterval);
        return;
    }

    const particleCount = 100 * (timeLeft / confettiDuration);
    confetti({
        ...confettiDefaults,
        particleCount,
        origin: {
            x: randomInRange(0.1, 0.3),
            y: Math.random() - 0.2,
        },
    });
    confetti({
        ...confettiDefaults,
        particleCount,
        origin: {
            x: randomInRange(0.7, 0.9),
            y: Math.random() - 0.2,
        },
    });
}, 200);
// Confetti-js-scripts -end

// download-certificate-button
const buttonSavePdf = document.getElementById("certificate_download");

buttonSavePdf.addEventListener("click", () => {
    var element = document.querySelector(".certificate_view");
    html2pdf(element, {
        margin: 1, // Default margin
        filename: "certificate.pdf",
        image: {
            type: "jpeg",
            quality: 0.98,
        },
        html2canvas: {
            scale: 2,
        }, // Default canvas scaling
        jsPDF: {
            unit: "in",
            format: "a2",
            orientation: "portrait",
        }, // A2 size in portrait
    });
});
