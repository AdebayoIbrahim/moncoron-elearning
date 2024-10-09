import html2pdf from "html2pdf.js";
// download-certificate-button
const buttonSavePdf = document.getElementById("attendance_download");

buttonSavePdf.addEventListener("click", () => {
    var element = document.querySelector(".attendace_area");
    html2pdf(element, {
        margin: 1, // Default margin
        filename: "attendance.pdf",
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
