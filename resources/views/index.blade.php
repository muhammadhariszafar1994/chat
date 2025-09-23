<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Floating Chat Widget</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .chat-widget {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            height: 400px;
            background-color: #fff;
            color: #333;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: none;
            flex-direction: column;
            overflow: hidden;
            opacity: 0;
            transition: all 0.5s ease;
        }

        .chat-header {
            padding: 10px;
            background-color: #007bff;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
            font-weight: 600;
        }

        .chat-header .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        .chat-header .message-counter {
            font-size: 14px;
            font-weight: normal;
        }

        .chat-body {
            display: flex;
            flex-direction: column;
            height: 100%;
            padding: 10px;
            overflow: hidden;
        }

        .chat-messages {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            max-height: 270px;
            border-bottom: 1px solid #ccc;
        }

        .chat-messages .message {
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 8px;
            font-size: 14px;
        }

        .chat-messages .userBot {
            background-color: #f1f1f1;
            text-align: left;
        }

        .chat-messages .visitor {
            background-color: #007bff;
            color: white;
            text-align: right;
        }

        .chat-input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: calc(100% - 50px);
            font-size: 14px;
            margin-top: 10px;
            font-family: 'Poppins', sans-serif;
        }

        .send-btn {
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            margin-left: 10px;
            display: flex;
            margin-top: 12px;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
        }

        .send-btn i {
            font-size: 18px;
        }

        .chat-toggle-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .loader {
            width: 30px;
            height: 32px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 100%;
            animation: spin 3s
            linear infinite;
            display: flex;
            justify-content: center;
            margin: 0 auto;
            margin-top: 18px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 600px) {
            .chat-widget {
                width: 100%;
                height: 100%;
                border-radius: 0;
            }
            .chat-input {
                width: calc(100% - 70px);
            }
        }
    </style>
</head>
<body>

<div class="chat-toggle-btn">💬</div>

<div class="chat-widget">
    <div class="chat-header">
        <span>Chat with us</span>
        <span class="message-counter">Messages: 0</span>

        <button class="close-btn">×</button>
    </div>
    <div class="chat-body">
        <div class="chat-messages">
            <div class="message userBot">Hello! How can I help you today?</div>

            <div class="loader" style="display: none;"></div>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <input type="text" class="chat-input" placeholder="Type a message...">
            <button class="send-btn"><i class="fas fa-paper-plane"></i></button>
        </div>

    </div>
</div>

<script>
    const chatWidget = document.querySelector('.chat-widget');
    const chatToggleBtn = document.querySelector('.chat-toggle-btn');
    const closeBtn = document.querySelector('.close-btn');
    const messageCounter = document.querySelector('.message-counter');
    const chatMessages = document.querySelector('.chat-messages');
    const sendBtn = document.querySelector('.send-btn');
    const chatInput = document.querySelector('.chat-input');
    const loader = document.querySelector('.loader');

    let lastResponseId = null;
    let messageCount = 1;

    chatToggleBtn.addEventListener('click', function() {
        if (chatWidget.style.display === 'none' || chatWidget.style.display === '') {
            chatWidget.style.display = 'flex';
            setTimeout(() => {
                chatWidget.style.height = '400px';
                chatWidget.style.opacity = '1';
            }, 10);
        } else {
            chatWidget.style.height = '0';
            chatWidget.style.opacity = '0';
            setTimeout(() => {
                chatWidget.style.display = 'none';
            }, 500);
        }
    });

    closeBtn.addEventListener('click', function() {
        chatWidget.style.height = '0';
        chatWidget.style.opacity = '0';
        setTimeout(() => {
            chatWidget.style.display = 'none';
        }, 500);
    });

    // Send a message when the send button is clicked or Enter is pressed
    function sendMessage() {
        const message = chatInput.value.trim();
        if (message !== '') {
            const messageDiv = document.createElement('div');
            messageDiv.textContent = message;
            messageDiv.classList.add('message', 'visitor');
            chatMessages.appendChild(messageDiv);
            messageCount++;
            messageCounter.textContent = `Messages: ${messageCount}`;
            chatInput.value = '';
            chatMessages.scrollTop = chatMessages.scrollHeight;

            loader.style.display = 'block';

            sendMessageToServer(message)
        }
    }

    // Function to send the message to the server using AJAX (Fetch API)
    function sendMessageToServer(message) {
        fetch('http://homestead83.test/chatrill/public/api/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                message: message,
                previous_response_id: lastResponseId,
            })
        })
            .then(response => response.json())
            .then(data => {
                appendServerReply(data);
            })
            .catch(error => {
                console.error('Error:', error);
            })
            .finally(() => {
                loader.style.display = 'none';
            });
    }

    // Append the server's reply to the chat messages
    function appendServerReply(output) {
        lastResponseId = output.response_id;
        const replyDiv = document.createElement('div');
        replyDiv.textContent = output.reply;
        replyDiv.classList.add('message', 'userBot');
        replyDiv.setAttribute('data-resp_id', lastResponseId);
        chatMessages.appendChild(replyDiv);
        messageCount++;
        messageCounter.textContent = `Messages: ${messageCount}`;
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    sendBtn.addEventListener('click', sendMessage);

    // Send message on Enter key press
    chatInput.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            sendMessage();
            event.preventDefault();
        }
    });
</script>
</body>
</html>

