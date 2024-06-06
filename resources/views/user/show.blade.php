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
        
        @if (auth()->id() != $user->id)
          @if (!$isBlocked)
            <div id="acceptBtnContainer"></div>
            <div class="row">
              <div id="friendship-buttons"></div>
              <form id="sendMessageForm">
                @csrf
                <input type="hidden" name="from_id" value="{{ auth()->id() }}">
                <input id="profileIdInput" type="hidden" name="to_id" value="{{ $user->id }}">
                <input type="hidden" name="body" value="Hello, I want to connect with you!">
                <button type="button" id="sendMessageBtn" data-profile-id="{{ $user->id }}">Message</button>
              </form>
              @if (!$user->isBlockedBy(auth()->user()))
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
          @endif
        @endif
      </div>
    </div>
  </div>
</center>

<br><br>

@if ($isBlocked)
  <div class="container">
    <div class="alert alert-danger mt-5">
      <h4>You have been blocked by this user. You cannot access this page.</h4>
    </div>
  </div>
@else
<div class="container-fluid d-flex flex-column justify-content-center align-items-center">
  @if (auth()->id() == $user->id)
    @include('components.createpost')
  @endif

  <div class="row w-100 mt-5">
    <!-- Publications Section -->
    <div class="col-md-8">
      <div class="card shadow-sm border">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3>Publications</h3>
        </div>
        <div class="card-body">
          @foreach($user->publications as $publication)
            <x-publication :canUpdate="auth()->user()->id === $publication->user_id" :publication="$publication" class="mb-3" />
          @endforeach
        </div>
      </div>
    </div>

    <!-- Friends and Friend Suggestions Section -->
    <div class="col-md-4">
      <div class="sticky-top">
        <!-- Friends Section -->
        <div class="card shadow-sm border mb-3">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Friends</h3>
            <a href="/friends" class="text-muted">See All</a>
          </div>
          <div class="card-body">
            <ul class="list-group">
              @foreach($friends as $friend)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center">
                    <img src="{{ asset('storage/' . $friend->image) }}" alt="Avatar" width="40" class="rounded-circle mr-2">
                    <span>{{ $friend->name }}</span>
                  </div>
                  <a href="/chat" class="btn btn-sm btn-outline-primary">Message</a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>

        <!-- Friend Suggestions Section -->
        <div class="card shadow-sm border">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Friend Suggestions</h3>
          </div>
          <div class="card-body">
            @foreach($friendSuggestions as $suggestedUser)
              <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex align-items-center">
                  <img src="{{ asset('storage/' . $suggestedUser->image) }}" alt="Avatar" width="40" class="rounded-circle mr-2">
                  <span>{{ $suggestedUser->name }}</span>
                </div>
                <form action="{{ route('friend-request.send', $suggestedUser->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-primary">Add Friend</button>
                </form>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min"></script>

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
        axios.post("{{ route('friend-request.accept', $user->id) }}", {
            _token: '{{ csrf_token() }}'
        })
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

<style>
  .sticky-top {
    position: -webkit-sticky;
    position: sticky;
    top: 20px; /* Adjust this value to control the sticky position */
  }
</style>
