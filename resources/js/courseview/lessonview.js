import axios from "axios";
import { convertBlobtofile, formatTime } from "../utils";
// document.addEventListener("DOMContentLoaded", function () {
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
        audio: await convertBlobtofile(recordFile, "audio", fileCorellation),
    };

    // type-conditional_call_onlyif thereis a recorded file
    if (recordFile && recordFile.src !== "") {
        try {
            console.log(formobj);
            await axios.post(
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

const start = document.getElementById("StartCall");
start.onclick = function () {
    startCall();
};

const joinBtn = document.getElementById("join_call");
joinBtn.onclick = function () {
    joinClass();
};

const loadingElement = document.querySelector("#loadingAnimation");
const APP_ID = import.meta.env.VITE_AGORA_APP_ID;
let localAudioTrack, localVideoTrack;
let CHANNEL_NAME = "GroupClassChart";
// let client = AgoraRTC.createClient({ mode: "live", codec: "h264" });
const mediacontainer = document.querySelector(".media_container");
const spreadmedias = document.querySelector("#media_uploaded");

function showstreamload() {
    loadingElement.classList.add("show_stream");
}

function closeLoading() {
    loadingElement.classList.remove("show_stream");
}

// Remove existing elements
function removeElements() {
    spreadmedias.style.display = "none";
}

var client = AgoraRTC.createClient({
    mode: "live",
    codec: "vp8",
});
var localTracks = {
    videoTrack: null,
    audioTrack: null,
};
var remoteUsers = {};
var options = {
    appid: import.meta.env.VITE_AGORA_APP_ID,
    channel: null,
    uid: null,
    token: null,
    role: "audience",
};

// Start the call
async function startCall() {
    showstreamload();

    try {
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

        options.token = token;
        options.uid = uid;
        options.channel = CHANNEL_NAME;
        options.role = "host";

        await join();
    } catch (error) {
        console.error("Error in startCall:", error);
    }
}

// Join the class
async function joinClass() {
    try {
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

        options.token = token;
        options.uid = uid;
        options.channel = CHANNEL_NAME;
        options.role = "audience";

        await join();
    } catch (error) {
        console.error(error);
    }
}

// Join channel and set up streams
async function join() {
    // Set client role
    if (options.role === "audience") {
        await client.setClientRole(options.role);
    } else {
        await client.setClientRole(options.role);
    }

    // Add event listeners for remote users
    client.on("user-published", handleUserPublished);
    client.on("user-unpublished", handleUserUnpublished);

    // Join the channel
    options.uid = await client.join(
        options.appid,
        options.channel,
        options.token || null,
        options.uid || null
    );

    if (options.role === "host") {
        // Create and publish local tracks
        if (!localTracks.audioTrack) {
            localTracks.audioTrack =
                await AgoraRTC.createMicrophoneAudioTrack();
        }
        if (!localTracks.videoTrack) {
            localTracks.videoTrack = await AgoraRTC.createCameraVideoTrack();
        }

        // Play the local video track
        const localPlayerContainer = document.createElement("div");
        localPlayerContainer.id = `local-player-${options.uid}`;
        localPlayerContainer.style.width = "100%";
        localPlayerContainer.style.height = "100%";
        localPlayerContainer.classList.add("local_styled_video");
        document.getElementById("local-video").append(localPlayerContainer);

        localTracks.videoTrack.play(localPlayerContainer);
        await client.publish(Object.values(localTracks));
        console.log("Local tracks published");
    } else if (options.role === "audience") {
        console.log("Joined as audience, waiting for host to publish streams.");
    }

    closeLoading();
    removeElements();
}

// Leave the channel
async function leave() {
    for (trackName in localTracks) {
        var track = localTracks[trackName];
        if (track) {
            track.stop();
            track.close();
            localTracks[trackName] = undefined;
        }
    }

    // Remove remote users and player views
    remoteUsers = {};
    document.getElementById("remote-video").innerHTML = "";

    // Leave the channel
    await client.leave();
    console.log("Client left channel");
}

// Subscribe to remote user
async function subscribe(user, mediaType) {
    const uid = user.uid;
    // Subscribe to a remote user
    await client.subscribe(user, mediaType);
    console.log("subscribe success");

    if (mediaType === "video") {
        const playerContainer = document.createElement("div");
        playerContainer.id = `remote-player-${uid}`;
        playerContainer.style.width = "100%";
        playerContainer.style.height = "100%";
        document.getElementById("remote-video").append(playerContainer);

        user.videoTrack.play(playerContainer);
    }
    if (mediaType === "audio") {
        user.audioTrack.play();
    }
}

// Handle remote user published
function handleUserPublished(user, mediaType) {
    console.log('"user-published" event for remote users is triggered.');
    const id = user.uid;
    remoteUsers[id] = user;
    subscribe(user, mediaType);
}

// Handle remote user unpublished
function handleUserUnpublished(user) {
    console.log('"user-unpublished" event for remote users is triggered.');
    const id = user.uid;
    delete remoteUsers[id];
    const remotePlayerContainer = document.getElementById(
        `remote-player-${id}`
    );
    if (remotePlayerContainer) {
        remotePlayerContainer.remove();
    }
}
