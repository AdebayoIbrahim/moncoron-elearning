import { formatTime } from "../utils";
document.addEventListener("DOMContentLoaded", function () {
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

        const csrftoken = document.querySelector("input[name=_token]")?.value;
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

        messages.forEach((message) => {
            const messageElement = document.createElement("div");
            messageElement.classList.add("message-container");

            const messageContent = document.createElement("div");
            messageContent.classList.add("message");
            messageContent.innerHTML = `<div>${message.message}</div>`;
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
        chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll to the bottom
    }

    fetchMessages();
    // setInterval(fetchMessages, 5000); // Poll every 5 seconds
});
