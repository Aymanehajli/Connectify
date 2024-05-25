@include('components.master')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<center>
    <div class="container">
        <div class="profile-header">
            <div class="profile-avatar">
                <img src="{{ asset('storage/' . $user->image) }}" alt="Avatar" width="100">
            </div>
            <div class="profile-info">
                <h1>{{ $user->name }}</h1>
                <p>{{ $user->email }}</p>
                <div id="acceptBtnContainer"></div>
                <div class="row">
                    <div id="friendship-buttons"></div>
                    <form id="sendMessageForm">
                        @csrf
                        <input type="hidden" name="from_id" value="{{ auth()->id() }}">
                        <input id="profileIdInput" type="hidden" name="to_id" value="{{ $user->id }}">
                        <input type="hidden" name="body" value="Hello, I want to connect with you!"> <!-- Sample message body -->
                        <button type="button" id="sendMessageBtn" data-profile-id="{{ $user->id }}">Message</button>
                    </form>
                    @if (!$user->isBlockedBy(auth()->user()) )
    <form action="{{ route('block', $user->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">Block</button>
    </form>

@elseif ($auth1->hasBlocked($user))
    <form action="{{ route('unblock', $user->id) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Unblock</button>
    </form>
@endif
                
                </div>
            </div>
        </div>
    </div>
</center>
@if (auth()->id() == $user->id)
    @include('components.createpost')
@endif

<br><br>

<div class="container w-45 mx-auto">
    <h3>Publications :</h3>
    <br>
    <div class="row">
        @foreach ($user->publications as $publication)
            <div class="row">
                <x-publication :canUpdate="auth()->user()->id === $publication->user_id" :publication="$publication" />
            </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        checkFriendship();
    });

    function addFriend() {
        axios.post("{{ route('friend-request.send', $user->id) }}")
            .then(function (response) {
                alert(response.data.message);
                checkFriendship();
            })
            .catch(function (error) {
                console.error(error.response.data);
            });
    }

    function removeRequest() {
        axios.post("{{ route('remove.request', $user->id) }}")
            .then(function (response) {
                alert(response.data.message);
                checkFriendship();
            })
            .catch(function (error) {
                console.error(error.response.data);
            });
    }
    
    function acceptFriend() {
        axios.post("{{ route('friend-request.accept', $user->id) }}")
            .then(function (response) {
                alert(response.data.message);
                checkFriendship();
            })
            .catch(function (error) {
                console.error(error.response.data);
            });
    }

    function checkFriendship() {
        axios.get("{{ route('check.friendship', $user->id) }}")
            .then(function (response) {
                var buttonsHtml = '';
                if (response.data.friends) {
                    buttonsHtml += '<button disabled>Friends</button>';
                } else if (response.data.friendRequestReceived) {
                    buttonsHtml += '<button onclick="acceptFriend()">Accept Friend Request</button>';
                } else if (response.data.friendRequestSent) {
                    buttonsHtml += '<button onclick="removeRequest()">Remove Request</button>';
                } else {
                    buttonsHtml += '<button onclick="addFriend()">Send Request</button>';
                }
                $('#friendship-buttons').html(buttonsHtml);
            })
            .catch(function (error) {
                console.error(error.response.data);
            });
    }

   
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sendMessageBtn = document.getElementById('sendMessageBtn');
        const profileIdInput = document.getElementById('profileIdInput');

        sendMessageBtn.addEventListener('click', function () {
            const formData = new FormData(document.getElementById('sendMessageForm'));
            formData.append('to_id', profileIdInput.value);

            fetch("{{ route('chat.send') }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Message sent successfully!');
                } else {
                    alert('Error sending message. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error sending message:', error);
                alert('Error sending message. Please try again.');
            });
        });
    });
</script>
