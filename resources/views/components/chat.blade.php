<style>
    .chat-widget {
        width: 300px;
        height: 400px;
        display: flex;
        color: #333;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        flex-direction: column;
        overflow: hidden;
        position: fixed;
        bottom: 20px;
        right: 20px;
        transition: all 0.5s ease;
        margin-left: auto;
        z-index: 999;
        
        /* background-color: #fff;
        opacity: 0;
        display: none; */
    }

    .chat-header {
        padding: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 18px;
        font-weight: 600;

        /* background-color: #007bff; */
        /* color: white; */
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
        /* height: 100%; */
        padding: 10px;
        overflow: hidden;
        flex: 1;
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
        /* background-color: #f1f1f1; */
        text-align: left;
        width: 70%;
        clear: both;

        background-color: {{ $theme['chat_bot_bg_color'] }}; 
        color: {{ $theme['chat_bot_text_color'] }}; 
        font-family: {{ $theme['chat_message_font_family'] }}; 
        font-size: {{ $theme['chat_message_font_size'] }}px
    }

    .chat-messages .visitor {
        /* background-color: #007bff; */
        /* color: white; */
        text-align: right;
        float: right;
        width: 70%;
        clear: both;

        background-color: {{ $theme['chat_user_bg_color'] }}; 
        color: {{ $theme['chat_user_text_color'] }}; 
        font-family: {{ $theme['chat_message_font_family'] }}; 
        font-size: {{ $theme['chat_message_font_size'] }}px
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
        /* background-color: #007bff; */
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
        padding: 15px;
        box-sizing: border-box;
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

@props(['theme'])

<?php 
    // echo "<pre>";
    // print_r($theme);
    // echo "</pre>";
?>

<div class="chat-widget" style="background-color: {{ $theme['chat_body_bg_color'] }}; display:none;">
    <div 
        class="chat-header" 
        style="background-color: {{ $theme['chat_header_bg_color'] }}; color: {{ $theme['chat_header_text_color'] }}; font-family: {{ $theme['chat_header_font_family'] }}; font-size: {{ $theme['chat_header_font_size'] }}px"
    >
        <span>Chat with us</span>
        <button class="close-btn">×</button>
    </div>
    <div class="chat-body">
        <div class="chat-messages">
            <!-- <div class="message userBot">Hello! How can I help you today?</div> -->

            <div class="loader" style="display: none;"></div>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <input type="text" class="chat-input" placeholder="Type a message..." style="font-family: {{ $theme['chat_message_font_family'] }}; font-size: {{ $theme['chat_message_font_size'] }}px">
            <button class="send-btn" style="background-color: {{ $theme['chat_button_bg_color'] }};">
                <img src="{{ $theme->getFirstMediaUrl('chat_button_image') }}" alt="Chat Button Image" style="width: 100%;">
            </button>
        </div>
    </div>
</div>

<!-- Add toggle button -->
<!-- <div class="chat-toggle-btn">💬</div> -->
<div class="chat-toggle-btn" style="background-color: {{ $theme['chat_toggle_bg_color'] }}">
    <img src="{{ $theme->getFirstMediaUrl('chat_toggle_image') }}" alt="Chat Button Image" style="width: 100%;">
</div>

<script>
    (function () {
        const chatWidget = document.querySelector('.chat-widget');
        const chatToggleBtn = document.querySelector('.chat-toggle-btn');
        const closeBtn = document.querySelector('.close-btn');
        const chatMessages = document.querySelector('.chat-messages');
        const sendBtn = document.querySelector('.send-btn');
        const chatInput = document.querySelector('.chat-input');
        const loader = document.querySelector('.loader');

        // show/hide widget
        function toggleWidget(open = true) {
            if (open) {
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
        }

        // append message to chat
        function appendMessage(text, role = 'visitor') {
            const msgDiv = document.createElement('div');
            msgDiv.classList.add('message', role === 'user' ? 'visitor' : 'userBot');
            msgDiv.textContent = text;
            chatMessages.appendChild(msgDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // send message
        function postConversation() {
            const message = chatInput.value.trim();
            if (!message) return;

            appendMessage(message, 'user');
            chatInput.value = '';
            loader.style.display = 'block';

            fetch("{{ config('app.url') }}/api/openai/conversation", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-Token-Header": "{{ $token ?? '' }}"
                },
                body: JSON.stringify({ message })
            })
            .then(res => res.json())
            .then(data => {
                // const reply = data.reply || 'No response.';
                // appendMessage(reply, 'assistant');

                getConversation();
            })
            .catch(error => {
                console.error("Chat error:", error);
                appendMessage("Oops! Something went wrong.", 'assistant');
            })
            .finally(() => {
                loader.style.display = 'none';
            });
        }

        // fetch messages
        async function getConversation() {
            loader.style.display = 'block';

            try {
                const res = await fetch("{{ config('app.url') }}/api/openai/conversation", {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Token-Header": "{{ $token ?? '' }}"
                    }
                });

                if (!res.ok) {
                    throw new Error(`HTTP error! Status: ${res.status}`);
                }

                const data = await res.json();

                if (data && Array.isArray(data?.data?.data)) {
                    chatMessages.innerHTML = ''; // clear old messages

                    data.data.data.reverse().forEach(msg => {
                        const role = msg.role === 'user' ? 'user' : 'assistant';
                        const textObj = msg.content?.find(c => c.text);
                        const text = textObj?.text;

                        if (text) {
                            appendMessage(text, role);
                        }
                    });
                } else {
                    appendMessage("Unexpected data format from server.", "assistant");
                    console.warn("Unexpected API structure:", data);
                }
            } catch (error) {
                console.error("Chat error:", error);
                appendMessage("Failed to load chat history.", "assistant");
            } finally {
                loader.style.display = 'none';
            }
        }

        getConversation();

        // listeners
        sendBtn?.addEventListener('click', postConversation);
        chatInput?.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                postConversation();
            }
        });

        chatToggleBtn?.addEventListener('click', () => toggleWidget(true));
        closeBtn?.addEventListener('click', () => toggleWidget(false));
    })();
</script>