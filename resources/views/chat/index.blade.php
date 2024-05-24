<div class="container">
        <h1>Chat</h1>

        <div id="chatMessages">
            @foreach ($messages as $message)
                <div>
                    <strong>{{ $message->from_id }}</strong>: {{ $message->body }}
                </div>
            @endforeach
        </div>

        <form action="{{ route('chat.send') }}" method="post">
            @csrf
            <input type="hidden" name="from_id" value="{{ auth()->id() }}">
            <input type="hidden" name="to_id" value="3"> <!-- Set the recipient's ID -->
            <textarea name="body" rows="3" placeholder="Type your message"></textarea>
            <button type="submit">Send</button>
        </form>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const chatMessages = document.getElementById('chatMessages');

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        form.reset();
                        appendMessage(data.message);
                    }
                })
                .catch(error => console.error('Error sending message:', error));
            });

            function appendMessage(message) {
                const div = document.createElement('div');
                div.innerHTML = `<strong>${message.from_id}</strong>: ${message.body}`;
                chatMessages.appendChild(div);
            }
        });
    </script>