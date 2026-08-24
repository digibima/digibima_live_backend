<style>
    /* Chat Popup Container */
    .chat-popup {
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 320px;
        max-width: 90vw;
        max-height: 100vh;
        background-color: #fff;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        display: none;
        border-radius: 8px;
        overflow: hidden;
    }

    /* Chat Header */
    .chat-header {
        display: flex;
        align-items: center;
        background-color: #2ECC71;
        color: white;
        padding: 12px;
        border-radius: 8px 8px 0 0;
        position: relative;
    }

    /* Chat Header Image */
    #popimg {
        background-color: #fff;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
        margin-right: 10px;
    }

    /* Chat Header Text */
    .df-brand-name {
        font-size: 16px;
        font-weight: 500;
    }

    /* Close Button */
    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        cursor: pointer;
        color: white;
    }

    /* Chat Body */
    .chat-body {
        display: flex;
        flex-direction: column;
        padding: 15px;
        background-image: url("{{ config('constant.BASE_URL') }}/front/images/whatsapp-chatbackground.png");
        background-size: cover;
        font-size: 14px;
        color: #333;
        height: 200px;
        overflow-y: auto;
    }

    /* Bot Message Bubble */
    .df-window-msg-cont {
        background: white;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 14px;
        display: inline-block;
        max-width: 80%;
        width: fit-content;
        position: relative;
        margin: 5px 10px;
        align-self: flex-start;
    }

    /* Correct Tail for Bot Message */
    .df-window-msg-cont:before {
        content: '';
        position: absolute;
        top: 50%;
        /* Center it vertically */
        left: -6px;
        /* Attach it properly to the left */
        transform: translateY(-50%);
        /* Center align it */
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 6px 8px 6px 0;
        border-color: transparent white transparent transparent;
    }

    /* User Message Bubble */
    .user-msg {
        background: #DCF8C6;
        /* WhatsApp-style green bubble */
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 14px;
        display: inline-block;
        max-width: 80%;
        width: fit-content;
        position: relative;
        margin: 5px 10px;
        align-self: flex-end;
    }

    /* Correct Tail for User Message */
    .user-msg:after {
        content: '';
        position: absolute;
        top: 50%;
        /* Align vertically in the middle */
        right: -6px;
        /* Attach perfectly to the right */
        transform: translateY(-50%);
        /* Center the tail vertically */
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 6px 0 6px 8px;
        border-color: transparent transparent transparent #DCF8C6;
    }

    .user-msg:before {
        display: none;

    }


    /* Chat Form */
    .chat-form {
        padding: 10px;
        border-top: 1px solid #ddd;
        display: flex;
        gap: 5px;
        background: white;
    }

    .chat-form input {
        flex-grow: 1;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 20px;
        font-size: 14px;
    }

    .chat-form button {
        background-color: #2ECC71;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 18px;
    }

    /* Chat Toggle Button */
    #color-button {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: green;
        color: white;
        border-radius: 25px;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 5px;
        z-index: 1000;
    }
</style>

<!-- Chat Button -->
<button id="color-button" onClick="togglePopup()">
    <i class="bi bi-whatsapp"></i><span id="contantspan"> Chat with us</span>
</button>

<!-- Chat Popup -->
<div class="chat-popup" id="myPopup">
    <div class="chat-header">
        <img src="{{ config('constant.BASE_URL') }}front/images/DigiBimaprofilepic.png" id="popimg" alt="Profile">
        <div>
            <div class="df-brand-name">DigiBima</div>
        </div>
        <span class="close" onClick="togglePopup()">&times;</span>
    </div>

    <!-- Chat Messages -->
    <div class="chat-body" id="chatBody">
        <div class="df-window-msg-cont">
            <p>Hi, how can I help you?</p>
        </div>
    </div>

    <!-- Chat Input Form -->
    <div class="chat-form">
        <input type="text" id="chatInput" placeholder="Type a message..." />
        <button onClick="sendMessage()"><i class="bi bi-send"></i></button>
    </div>
</div>

<script>
    function togglePopup() {
        var popup = document.getElementById("myPopup");
        var contantspan = document.getElementById("contantspan");
        popup.style.display = popup.style.display === "none" || popup.style.display === "" ? "block" : "none";
        contantspan.style.display = contantspan.style.display === "block" || contantspan.style.display === "" ? "none" : "block";
    }

    function sendMessage() {
        var input = document.getElementById("chatInput");
        var message = input.value.trim();
        var chatBody = document.getElementById("chatBody");

        if (message) {
            // Step 1: Display user's message in chat
            // var userMessage = document.createElement("div");
            // userMessage.classList.add("df-window-msg-cont", "user-msg");
            // userMessage.innerHTML = "<p>" + message + "</p>";
            // chatBody.appendChild(userMessage);
            // chatBody.scrollTop = chatBody.scrollHeight;

            // Step 2: Redirect user to WhatsApp
            var phoneNumber = "919119173733"; // Ensure this is the correct format
            var encodedMessage = encodeURIComponent(message);
            window.open(`https://wa.me/${phoneNumber}?text=${encodedMessage}`, "_blank");

            // Step 3: Clear input field after sending
            input.value = "";

            // Step 4: Simulate a bot response after 1.5 seconds
            // setTimeout(() => {
            //     var botResponse = document.createElement("div");
            //     botResponse.classList.add("df-window-msg-cont", "bot-msg");
            //     botResponse.innerHTML = "<p>I'll get back to you shortly!</p>";
            //     chatBody.appendChild(botResponse);
            //     chatBody.scrollTop = chatBody.scrollHeight;
            // }, 1500);
        }
    }
</script>