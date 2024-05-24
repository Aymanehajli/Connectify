@include('navbar.nav')
<br><br>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Interface</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
        }
        .container {
            display: flex;
            height: 100vh;
            flex-direction: row;
        }
        .sidebar {
            width: 30%;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            overflow-y: auto;
            padding: 10px;
        }
        .chat-window {
            width: 70%;
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            padding: 8px;
            background-color: #007bff;
            color: white;
            display: flex;
            align-items: center;
        }
        .chat-header img {
            border-radius: 50%;
            margin-right: 8px;
            width: 30px;
            height: 30px;
        }
        .chat-header strong {
            font-size: 1em;
        }
        .chat-history {
            flex-grow: 1;
            padding: 10px;
            overflow-y: auto;
        }
        .chat-history .message-container {
            margin-bottom: 10px;
        }
        .my-message {
            background-color: #dcf8c6;
            border-radius: 5px;
            padding: 10px;
            max-width: 60%;
            margin-left: auto;
        }
        .other-message {
            background-color: #f1f0f0;
            border-radius: 5px;
            padding: 10px;
            max-width: 60%;
            margin-right: auto;
        }
        .chat-footer {
            padding: 8px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
        .chat-footer form {
            display: flex;
            align-items: center;
        }
        .chat-footer textarea {
            flex-grow: 1;
            resize: none;
            height: 40px;
            margin-right: 8px;
        }
        .chat-footer button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .chat-footer button:hover {
            background-color: #0056b3;
        }
        .hidden {
            display: none;
        }
        .sidebar .chat-card {
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .sidebar .chat-card img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        .sidebar .chat-card .chat-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .sidebar .chat-card .chat-info .chat-name {
            font-weight: bold;
            font-size: 0.9em;
        }
        .sidebar .chat-card .chat-info .chat-last-message {
            font-size: 0.8em;
            color: gray;
        }
        .sidebar .chat-card .chat-info .chat-date {
            font-size: 0.7em;
            color: darkgray;
            text-align: right;
        }
        .search-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: 50%;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
            }
            .chat-window {
                width: 100%;
                height: 50%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <input type="text" class="search-input" placeholder="Search for users..." oninput="searchUsers(this.value)">
            <div id="user-list">
                @php
                    $conversations = [];
                @endphp
                @foreach ($messages->sortBy('created_at') as $message)
                    @php
                        $currentUser = $message->fromUser->id == auth()->id() ? $message->toUser : $message->fromUser;
                        $conversationId = $currentUser->id;
                        $conversationName = $currentUser->name;
                        $conversationImage = $currentUser->image;
                        $lastMessage = $message->body;
                        $lastMessageDate = $message->created_at->format('M d, Y h:i A');
                    @endphp
                    @php
// Get the authenticated user's ID and pass it to JavaScript
$authUserId = auth()->id();
@endphp

<script>
    const authUserId = '{{ $authUserId }}';
</script>

                    @if (!isset($conversations[$conversationId]))
                        <div class="chat-card" onclick="openChat('{{ $conversationId }}', '{{ $conversationName }}', '{{ asset('storage/' . $conversationImage) }}')">
                            <img src="{{ asset('storage/' . $conversationImage) }}" alt="User Image">
                            <div class="chat-info">
                                <div class="chat-name">{{ $conversationName }}</div>
                                <div class="chat-last-message">{{ $lastMessage }}</div>
                                <div class="chat-date">{{ $lastMessageDate }}</div>
                            </div>
                        </div>
                        @php
                            $conversations[$conversationId] = true;
                        @endphp
                    @endif
                @endforeach
            </div>
        </div>

        <div class="chat-window">
            <div class="chat-header hidden">
                <img src="https://via.placeholder.com/30" alt="User Image" id="chat-header-image">
                <strong id="chat-header-name">Conversation Name</strong>
            </div>
            <div class="chat-history">
                @foreach ($messages->sortBy('created_at') as $message)
                    <div class="message-container" data-conversation="{{ $message->fromUser->id == auth()->id() ? $message->toUser->id : $message->fromUser->id }}">
                        <div class="message-content @if ($message->fromUser->id == auth()->id()) my-message @else other-message @endif">
                            {{ $message->body }}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="chat-footer hidden">
                <form class="send-message-form">
                    @csrf
                    <input type="hidden" name="from_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="to_id" id="to_id">
                    <textarea name="body" rows="1" placeholder="Type your message here..." required></textarea>
                    <button type="button" class="sendMessageBtn">Send</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let currentConversationId = null;
        let lastMessageTimestamp = null;

        function openChat(conversationId, conversationName, conversationImage) {
            document.querySelectorAll('.chat-header, .chat-footer').forEach(el => el.classList.remove('hidden'));
            document.getElementById('chat-header-image').src = conversationImage;
            document.getElementById('chat-header-name').textContent = conversationName;
            document.getElementById('to_id').value = conversationId;
            currentConversationId = conversationId;

            document.querySelectorAll('.message-container').forEach(el => {
                el.style.display = el.getAttribute('data-conversation') === conversationId ? 'block' : 'none';
            });

            fetchMessages(conversationId);
        }

        document.querySelectorAll('.sendMessageBtn').forEach(button => {
            button.addEventListener('click', function() {
                var form = this.closest('form');
                var formData = new FormData(form);
                var messageBody = form.querySelector('textarea').value;
                var toId = form.querySelector('input[name="to_id"]').value;

                fetch("{{ route('chat.send') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        var messageContainer = document.createElement('div');
                        messageContainer.classList.add('message-container');
                        messageContainer.setAttribute('data-conversation', toId);

                        var messageContent = document.createElement('div');
                        messageContent.classList.add('message-content', 'my-message');
                        messageContent.textContent = messageBody;

                        messageContainer.appendChild(messageContent);

                        document.querySelector('.chat-history').appendChild(messageContainer);
                        form.querySelector('textarea').value = '';
                    } else {
                        alert('Failed to send message');
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('Error sending message');
                });
            });
        });

        function fetchMessages(conversationId) {
    fetch(`/chat/fetchMessages?conversationId=${conversationId}&lastTimestamp=${lastMessageTimestamp || ''}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                var chatHistory = document.querySelector('.chat-history');

                data.messages.forEach(message => {
                    var messageContainer = document.createElement('div');
                    messageContainer.classList.add('message-container');
                    messageContainer.setAttribute('data-conversation', conversationId);

                    var messageContent = document.createElement('div');
                    messageContent.classList.add('message-content');
                    messageContent.textContent = message.body;

                    // Determine message sender and add appropriate class
                    if (message.from_id == authUserId) {
                        messageContent.classList.add('my-message');
                    } else {
                        messageContent.classList.add('other-message');
                    }

                    messageContainer.appendChild(messageContent);
                    chatHistory.appendChild(messageContainer);

                    lastMessageTimestamp = message.timestamp;
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}


        function searchUsers(query) {
            const userList = document.getElementById('user-list');
            const chatCards = userList.querySelectorAll('.chat-card');

            chatCards.forEach(card => {
                const chatName = card.querySelector('.chat-name').textContent.toLowerCase();
                card.style.display = chatName.includes(query.toLowerCase()) ? 'flex' : 'none';
            });
        }

        // Polling for new messages
        function pollMessages() {
    if (currentConversationId) {
        fetch(`/chat/pollMessages?conversationId=${currentConversationId}&lastTimestamp=${lastMessageTimestamp || ''}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    var chatHistory = document.querySelector('.chat-history');

                    data.messages.forEach(message => {
                        var messageContainer = document.createElement('div');
                        messageContainer.classList.add('message-container');
                        messageContainer.setAttribute('data-conversation', currentConversationId);

                        var messageContent = document.createElement('div');
                        messageContent.classList.add('message-content');
                        messageContent.textContent = message.body;

                        // Determine message sender and add appropriate class
                        if (message.from_id == authUserId) {
                            messageContent.classList.add('my-message');
                        } else {
                            messageContent.classList.add('other-message');
                        }

                        messageContainer.appendChild(messageContent);
                        chatHistory.appendChild(messageContainer);

                        lastMessageTimestamp = message.timestamp;
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
}

        setInterval(pollMessages, 5000); // Poll every 5 seconds
    </script>
</body>
</html>

