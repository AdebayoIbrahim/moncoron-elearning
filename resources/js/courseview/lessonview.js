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

    // fetching-messages-fro-db
    async function fetchMessages() {
        fetch(`/admin/courses/${courseId}/lesson/${lessonId}/messages`)
            .then((response) => response.json())
            .then((messages) => {
                renderMessages(messages);
            })
            .catch(function () {
                window.alert("Error Getting Messages Reload");
            });
    }

    // text-only-send-button
    sendButton.addEventListener("click", function () {
        const message = chatInput.value;
        if (message.trim() === "") {
            alert("No Input to send");
            return;
        }

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

    // rendering-messages-conditionaly
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

    // fetch-automatically-whenpageis-loaded
    fetchMessages();
    // setInterval(fetchMessages, 5000); // Poll every 5 seconds

    // functions-to-trigger-user-audio/mic
    // mixins-variable-utility-purpose
    let audioRecorder;
    let audioChunks = [];
    let stop = false;
    const audiosignal = document.querySelector(".audio-signal");
    const cancelrecord = document.querySelector("#cancel_record");
    const stopbtn = document.querySelector("#stop_record");
    const record_text = document.getElementById("record_text");
    const record_wave = document.getElementById("record_wave");
    // audioelement placeholder
    let audioEl;
    const recordSendbutton = document.querySelector("#send_audio_record");

    // disable-sendbtn
    recordSendbutton.disabled = true;
    // cancle-trigeers-cancel-all
    cancelrecord.onclick = function () {
        cancelRecording();
    };
    // utlity-cancel-record func
    function cancelRecording() {
        audioRecorder.stop();
        stop = false;
        audiosignal.style.display = "none";
        // clean_ups_after_canceled
        if (audiosignal.classList.contains("after_rec_style")) {
            audiosignal.classList.remove("after_rec_style");
            audiosignal.removeChild(audioEl);
            record_text.style.display = "block";
            record_wave.style.display = "block";
            document.getElementById("audio_data").value = "";
        }
    }

    // stope_record
    stopbtn.onclick = function () {
        stopButtonrecording();
    };

    // utility-stop-record-func
    function stopButtonrecording() {
        if (audioRecorder && audioRecorder.state === "recording") {
            stop = true;
            console.log("trigger");
            audioRecorder.stop();
            recordSendbutton.disabled = false;
        }
    }

    // audio-button_clicked_for-audios-only-tostart-audio
    document.getElementById("audio_btn_record").onclick = async () => {
        if (audioRecorder && audioRecorder.state === "recording") {
            audioRecorder.stop();
        } else {
            // call-audio-signal
            audiosignal.style.display = "flex";
            const stream = await navigator.mediaDevices.getUserMedia({
                audio: true,
            });

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
                }
            };

            audioRecorder.start();
        }
    };

    // get_record_button_func

    // function_to_send_audio_content
    recordSendbutton.addEventListener("click", async () => {
        // perform-stop_func_first_to_follow_theprocess

        // stopButtonrecording();
        // query-for-hidden-audio-input
        const recordFile = document
            ?.querySelector(".audo_rec_file")
            ?.getAttribute("src");
        console.log(recordFile);

        // structured_objrct_forms
        const formobj = {
            audio: await convertBlobtofile(
                recordFile,
                "audio",
                fileCorellation
            ),
        };

        // type-conditional_call_onlyif thereis a recorded file
        if (recordFile && recordFile.src !== "") {
            try {
                await axios.post(
                    `/admin/courses/${courseId}/lesson/${lessonId}/message`,
                    { ...formobj },
                    {
                        method: "POST",
                        headers: {
                            Accept: "application/json",
                            "X-CSRF-TOKEN": csrftoken,
                            // "Content-Type": "multipart/form-data",
                        },
                    }
                );
                chatInput.value = "";
                cancelRecording();
                // then-refetch-messages
                await fetchMessages();
            } catch (err) {
                console.log(`Erro${err}`);
            }
        } else {
            window.alert("No input to send");
        }
    });

    // add-assessment-button
    const addassessmentBtn = document.querySelector("#add_assessment_btn");
    addassessmentBtn.onclick = function () {
        //TODO:verify-auth0status-before-navigation

        // check-if-lesson-exist-already

        if (addassessmentBtn?.textContent === "Manage Assessment") {
            window.open(window.location.href.concat("/assessment"), "_self");
        } else {
            window.open(
                window.location.href.concat("/create-assessments"),
                "_self"
            );
        }
    };

    // video-chat-if-required-modes

    const start = document.getElementById("StartCall");
    start.onclick = function () {
        startCall();
    };

    const loadingElement = document.querySelector("#loadingAnimation");
    const APP_ID = import.meta.env.VITE_AGORA_APP_ID;

    let localAudioTrack, localVideoTrack, localStream;
    let CHANNEL_NAME = "GroupClassChart";
    let client = AgoraRTC.createClient({ mode: "rtc", codec: "h264" });
    const mediacontainer = document.querySelector(".media_container");
    const spreadmedias = document.querySelector("#media_uploaded");

    function showstreamload() {
        loadingElement.classList.add("show_stream");
    }
    function closeLoading() {
        loadingElement.classList.remove("show_stream");
    }
    // remove-existing-elements
    function removeElements() {
        spreadmedias.style.display = "none";
    }
    async function startCall() {
        showstreamload();

        try {
            // Fetch the token from your server
            const response = await fetch("/admin/video_token/generate", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrftoken,
                    Accept: "application/json",
                },
                body: JSON.stringify({ channel_name: CHANNEL_NAME }),
            });

            const { token, uid } = await response.json();
            console.log("Token: ", token);
            console.log("Uid: ", uid);

            const validToken = token && token.trim() !== "" ? token : null;
            client = AgoraRTC.createClient({ mode: "rtc", codec: "h264" });

            // Join the channel with the token (or null if no token)
            await client.join(APP_ID, CHANNEL_NAME, validToken, uid);

            console.log("User " + uid + " joined channel");

            // Create the local audio and video tracks
            localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
            localVideoTrack = await AgoraRTC.createCameraVideoTrack();
            await client.publish([localAudioTrack, localVideoTrack]);

            console.log("Local tracks published");

            // Create a container for the local video (teacher)
            const localPlayerContainer = document.createElement("div");
            localPlayerContainer.id = `local-player-${uid}`;
            localPlayerContainer.style.width = "100%";
            localPlayerContainer.style.height = "500px";
            localPlayerContainer.textContent = `Teacher (uid: ${uid})`;
            document.getElementById("local-video").append(localPlayerContainer);

            // Play the local video track
            localVideoTrack.play(localPlayerContainer);

            closeLoading();
            removeElements();

            // Handle remote users
            client.on("user-published", async (remoteUser, mediaType) => {
                await client.subscribe(remoteUser, mediaType);

                if (mediaType === "video") {
                    const remoteVideoTrack = remoteUser.videoTrack;

                    // Create a container for the remote video (student)
                    const remotePlayerContainer = document.createElement("div");
                    remotePlayerContainer.id = `remote-player-${remoteUser.uid}`;
                    remotePlayerContainer.style.width = "640px";
                    remotePlayerContainer.style.height = "480px";
                    remotePlayerContainer.textContent = `Student (uid: ${remoteUser.uid})`;
                    document
                        .getElementById("remote-video")
                        .append(remotePlayerContainer);

                    remoteVideoTrack.play(remotePlayerContainer);
                    console.log("Remote video track is playing");
                }

                if (mediaType === "audio") {
                    const remoteAudioTrack = remoteUser.audioTrack;
                    remoteAudioTrack.play();
                    console.log("Remote audio track is playing");
                }
            });

            client.on("user-unpublished", (remoteUser) => {
                // Remove the remote video player when a remote user leaves
                const remotePlayerContainer = document.getElementById(
                    `remote-player-${remoteUser.uid}`
                );
                if (remotePlayerContainer) {
                    remotePlayerContainer.remove();
                    console.log("Remote user left the channel");
                }
            });
        } catch (error) {
            console.error("Error in startCall:", error);
        }
    }

    async function joinClass() {
        try {
            const CHANNEL_NAME = "GroupClassChart";
            const APP_ID = import.meta.env.VITE_AGORA_APP_ID;

            const response = await fetch("/admin/video_token/generate", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrftoken,
                },
                body: JSON.stringify({ channel_name: CHANNEL_NAME }),
            });

            if (!response.ok) {
                throw new Error(
                    `Network response was not ok: ${response.statusText}`
                );
            }

            const { token, uid } = await response.json();
            console.log(token, uid);

            const validToken = token && token.trim() !== "" ? token : null;

            if (!token || !uid) {
                throw new Error("Failed to retrieve token or uid");
            }

            const client = AgoraRTC.createClient({
                mode: "rtc",
                codec: "h264",
            });

            await client.join(APP_ID, CHANNEL_NAME, validToken, uid);
            console.log(`Student (uid: ${uid}) joined the class.`);

            const localAudioTrack = await AgoraRTC.createMicrophoneAudioTrack();
            const localVideoTrack = await AgoraRTC.createCameraVideoTrack();

            // Create a container for the local (student) video
            const localPlayerContainer = document.createElement("div");
            localPlayerContainer.id = `local-player-${uid}`;
            localPlayerContainer.textContent = `Student (uid: ${uid})`;
            localPlayerContainer.style.width = "320px";
            localPlayerContainer.style.height = "240px";
            document.getElementById("local-video").append(localPlayerContainer);

            localVideoTrack.play(localPlayerContainer);

            client.on("user-published", async (user, mediaType) => {
                await client.subscribe(user, mediaType);

                if (mediaType === "video") {
                    const remoteVideoTrack = user.videoTrack;

                    const remotePlayerContainer = document.createElement("div");
                    remotePlayerContainer.id = `remote-player-${user.uid}`;
                    remotePlayerContainer.style.width = "640px";
                    remotePlayerContainer.style.height = "480px";
                    remotePlayerContainer.textContent = `Teacher (uid: ${user.uid})`;
                    document
                        .getElementById("remote-video")
                        .append(remotePlayerContainer);

                    remoteVideoTrack.play(remotePlayerContainer);
                }

                if (mediaType === "audio") {
                    const remoteAudioTrack = user.audioTrack;
                    remoteAudioTrack.play();
                }
            });

            client.on("user-unpublished", (user) => {
                const remotePlayerContainer = document.getElementById(
                    `remote-player-${user.uid}`
                );
                if (remotePlayerContainer) {
                    remotePlayerContainer.remove();
                }
            });

            console.log("Class joined successfully by student.");
        } catch (error) {
            console.error(error);
        }
    }
});
