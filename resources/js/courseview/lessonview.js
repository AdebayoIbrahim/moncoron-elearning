import axios from "axios";
import { convertBlobtofile, formatTime } from "../utils";
document.addEventListener("DOMContentLoaded", function () {
    const csrftoken = document.querySelector("input[name=_token]")?.value;

    const urlString = window.location.href;
    const regex = /\/lesson\/(\d+)$/;
    const match = urlString.match(regex);
    const lessonId = match ? match[1] : null;

    const regexcourse = /\/courses\/(\d+)\/lesson/;
    const matchcourse = urlString.match(regexcourse);
    const courseId = matchcourse ? matchcourse[1] : null;

    const chatMessages = document.getElementById("chat-box");
    const chatInput = document.getElementById("chat-input");
    const sendButton = document.getElementById("send-message");
    const fileCorellation =
        document.getElementById("file_correlation")?.innerText || lessonId;

    function fetchMessages() {
        fetch(`/admin/courses/${courseId}/lesson/${lessonId}/messages`)
            .then((response) => response.json())
            .then((messages) => {
                renderMessages(messages);
            })
            .catch(function () {
                window.alert("Error Getting Messages Reload");
            });
    }

    sendButton.addEventListener("click", function () {
        const message = chatInput.value;
        if (message.trim() === "") return;

        fetch(`/admin/courses/${courseId}/lesson/${lessonId}/message`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrftoken,
            },
            body: JSON.stringify({ message }),
        })
            .then((response) => response.json())
            .then((data) => {
                chatInput.value = "";
                fetchMessages();
            })
            .catch((err) => {
                window.alert(`Error: Failed to Send Message`);
            });
    });

    function renderMessages(messages) {
        chatMessages.innerHTML = "";
        const currentUserId = document.querySelector("#curruserid").value;

        // for-recepient-ui-test// let currentUserId = 1;

        messages.forEach((message) => {
            const messageElement = document.createElement("div");
            messageElement.classList.add("message-container");

            const messageContent = document.createElement("div");
            messageContent.classList.add("message");

            if (message?.message) {
                messageContent.innerHTML = `<div>${message?.message}</div>`;
            } else if (message?.audio) {
                let aud = document.createElement("audio");
                aud.setAttribute("controls", "true");
                aud.setAttribute("src", `/storage/${message?.audio}`);
                aud.classList.add("audio_chat_style");
                messageContent.prepend(aud);
            }

            const subcontent = document.createElement("div");
            subcontent.textContent = `${formatTime(message?.created_at)}`;
            subcontent.classList.add("bottom_text_cont");
            if (message.user_id == currentUserId) {
                messageContent.classList.add("my-message");
                subcontent.innerHTML = `${formatTime(
                    message?.created_at
                )}<span style = "margin-left: 3px"><i class="fa fa-check" aria-hidden="true"></i>
                </span>`;
            } else {
                messageContent.classList.add("other-message");
                const initials = message.user.name;
                messageContent.setAttribute("data-initials", initials);
            }

            messageElement.appendChild(messageContent);
            messageContent.appendChild(subcontent);
            chatMessages.appendChild(messageElement);
        });
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    fetchMessages();
    // setInterval(fetchMessages, 5000); // Poll every 5 seconds

    // functions-to-trigger-user-audio/mic
    let audioRecorder;
    let audioChunks = [];
    let stop = false;

    document.getElementById("audio_btn_record").onclick = async () => {
        const audiosignal = document.querySelector(".audio-signal");
        const cancelrecord = document.querySelector("#cancel_record");
        const stopbtn = document.querySelector("#stop_record");
        const record_text = document.getElementById("record_text");
        const record_wave = document.getElementById("record_wave");

        // audioelement placeholder
        let audioEl;

        // cancle-trigeers-cancel-all
        cancelrecord.onclick = function () {
            audioRecorder.stop();
            stop = false;
            audiosignal.style.display = "none";
            // clean_ups
            if (audiosignal.classList.contains("after_rec_style")) {
                audiosignal.classList.remove("after_rec_style");
                audiosignal.removeChild(audioEl);
                record_text.style.display = "block";
                record_wave.style.display = "block";
                document.getElementById("audio_data").value = "";
            }
        };

        if (audioRecorder && audioRecorder.state === "recording") {
            audioRecorder.stop();
        } else {
            // call-audio-signal
            audiosignal.style.display = "flex";
            const stream = await navigator.mediaDevices.getUserMedia({
                audio: true,
            });
            // stope_record

            stopbtn.onclick = function () {
                stop = true;
                console.log("trigger");
                audioRecorder.stop();
            };
            audioRecorder = new MediaRecorder(stream);
            // then-push-toaudio-chunks-as-aprts
            audioRecorder.ondataavailable = (e) => {
                //    spread-the-previous-
                audioChunks.push(e.data);
            };

            audioRecorder.onstop = async () => {
                // chec-if-its-the-pause-btn
                if (!stop) {
                    return;
                } else {
                    audioEl = document.createElement("audio");
                    record_text.style.display = "none";
                    record_wave.style.display = "none";
                    const blobfile = new Blob(audioChunks, {
                        type: "audio/webm",
                    });
                    // clear-audio-chunks
                    audioChunks = [];

                    const audiourl = URL.createObjectURL(blobfile);

                    audioEl.setAttribute("src", audiourl);
                    audioEl.setAttribute("controls", "true");
                    audioEl.classList.add("audo_rec_file");
                    console.log(audioEl);
                    audiosignal.classList.add("after_rec_style");
                    audiosignal.prepend(audioEl);
                    // usefile-reader-toread-blob
                    // const reader = new FileReader();
                    // reader.onloadend = () => {
                    //     document.getElementById("audio_data").value =
                    //         reader.result.split(",")[1];
                    // };
                    // reader.readAsDataURL(blobfile);
                }
            };

            audioRecorder.start();
            // audioBtn.innerHTML = `<i class="fas fa-stop b" style = "color:red"></i>`;
        }
    };

    // get_record_button_func
    const recordSendbutton = document.querySelector("#send_audio_record");

    recordSendbutton.addEventListener("click", async () => {
        // query-for-hidden-audio-input
        const recordFile = document
            ?.querySelector(".audo_rec_file")
            ?.getAttribute("src");
        console.log(recordFile);

        const formobj = {
            audio: await convertBlobtofile(
                recordFile,
                "audio",
                fileCorellation
            ),
        };
        console.log(formobj);
        if (recordFile && recordFile.src !== "") {
            try {
                const request = axios.post(
                    `/admin/courses/${courseId}/lesson/${lessonId}/message`,
                    { ...formobj },
                    {
                        method: "POST",
                        headers: {
                            Accept: "application/json",
                            "X-CSRF-TOKEN": csrftoken,
                            "Content-Type": "multipart/form-data",
                        },
                    }
                );

                const response = await request?.data;

                if (response) {
                    fetchMessages();
                }
            } catch (err) {
                console.log(`Erro${err}`);
            }
        }
    });
});
