<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenAI Chat</title>
    <!-- Add jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>OpenAI Chat</h1>

    <!-- Button to start a new conversation -->
    <button id="startConversation">Start New Conversation</button>

    <!-- Form to send a message -->
    <form id="sendMessageForm" style="display:none;">
        @csrf
        <!-- Hidden input field to store the conversation ID -->
        <input type="hidden" id="conversationId" name="conversationId">

        <!-- Input field for user to send a message -->
        <input type="text" id="messageInput" name="message" placeholder="Send a message..." required>
        <button type="submit">Send</button>
    </form>

    <div>
        <h2>Conversation Responses:</h2>
        <ul id="conversationResponses">
            <!-- Responses will appear here -->
        </ul>
    </div>

    <script>
        // Start a new conversation
        $('#startConversation').on('click', function() {
            $.ajax({
                url: '{{ url('openai/conversation') }}', // Route for creating a conversation
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Show message input form when conversation is created
                    $('#sendMessageForm').show();
                    $('#startConversation').hide();

                    // Store conversation ID for sending messages
                    $('#conversationId').val(response.conversation_id); // Set the conversation ID to the hidden input field
                },
                error: function() {
                    alert('Failed to start conversation.');
                }
            });
        });

        // Send a message to the conversation
        $('#sendMessageForm').on('submit', function(e) {
            e.preventDefault();

            var message = $('#messageInput').val();
            var conversationId = $('#conversationId').val(); // Retrieve the conversation ID from the hidden input field

            $.ajax({
                url: '{{ url('openai/conversation') }}/' + conversationId + '/message', // Route for sending message
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    message: message
                },
                success: function(response) {
                    // Clear input field after message is sent
                    $('#messageInput').val('');

                    // Add the assistant's response to the conversation
                    var newResponse = '<li>' + response.response.content + '</li>';
                    $('#conversationResponses').append(newResponse);
                },
                error: function() {
                    alert('Failed to send message.');
                }
            });
        });
    </script>
</body>
</html>
