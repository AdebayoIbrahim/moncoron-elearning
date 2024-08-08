<!-- resources/views/test-broadcast.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Test Broadcast</title>
    <script src="{{ mix('js/echo.js') }}" defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Echo.channel('chat')
                .listen('message.sent', (e) => {
                    console.log('Message received:', e);
                    alert(`Message from ${e.user.name}: ${e.message.content}`);
                });
        });
    </script>
</head>
<body>
    <h1>Broadcast Test</h1>
    <button id="sendMessageBtn">Send Message</button>

    <script>
        document.getElementById('sendMessageBtn').addEventListener('click', function() {
            fetch('/send-broadcast')
                .then(response => response.text())
                .then(data => console.log(data));
        });
    </script>
</body>
</html>
