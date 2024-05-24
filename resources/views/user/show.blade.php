@include('components.master')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<center>


<div class="container">
        <div class="profile-header">
            
            <div class="profile-avatar">
            <img src="{{asset('storage/'.$user->image)}}" alt="Avatar" width="100">
            </div>
            <div class="profile-info">
                <h1>{{ $user->name }}</h1>
                <p>{{ $user->email }}</p>
                <div id="acceptBtnContainer"></div>
               <div class="row">
                  <div  id="friendship-buttons"></div> 

                  <form id="sendMessageForm">
    @csrf
    <input type="hidden" name="from_id" value="{{ auth()->id() }}">
    <input id="profileIdInput" type="hidden" name="to_id" value="{{ $user->id }}">
    <input type="hidden" name="body" value="Hello, I want to connect with you!"> <!-- Sample message body -->
    <button type="button" id="sendMessageBtn" data-profile-id="{{ $user->id }}">Message</button>
</form>
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
  
      @foreach($user->publications as $publication)
      
      <div class="row">
  
      <x-publication :canUpdate="auth()->user()->id === $publication->user_id"  :publication="$publication" />
     
     </div>
      @endforeach



</div>

</div>



</div>



<!-- Include Axios and jQuery -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        // Check friendship status and pending friend request on page load
        checkFriendship();
        checkFriendRequest();
    });

    function addFriend() {
        // Send friend request
        axios.post("{{ route('add.friend', $user->id) }}")
            .then(function (response) {
                alert(response.data.message);
                checkFriendship(); // Update friendship buttons
            })
            .catch(function (error) {
                console.error(error.response.data);
            });
    }

    function removeRequest() {
        // Remove friend request
        axios.post("{{ route('remove.request', $user->id) }}")
            .then(function (response) {
                alert(response.data.message);
                checkFriendship(); // Update friendship buttons
            })
            .catch(function (error) {
                console.error(error.response.data);
            });
    }

    function checkFriendship() {
        // Check friendship status and update buttons
        axios.get("{{ route('check.friendship', $user->id) }}")
            .then(function (response) {
                var buttonsHtml = '';
                if (response.data.friendRequestSent) {
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

    function checkFriendRequest() {
        // Check for pending friend request and show "Accept" button
        axios.get("{{ route('check.friend.request', $user->id) }}")
        .then(function (response) {
                if (response.data.pendingRequest) {
                    var acceptBtnHtml = '<button onclick="acceptFriend()">Accept</button>';
                    $('#acceptBtnContainer').html(acceptBtnHtml);
                }
            })
            .catch(function (error) {
                console.error(error.response.data);
            });
    }

    function acceptFriend() {
        // Accept friend request
        axios.post("{{ route('accept.friend', $user->id) }}")
            .then(function (response) {
                alert(response.data.message); // Show success message
                $('#friendship-buttons').html(''); // Clear friendship buttons after accepting request
                $('#acceptBtnContainer').html(''); // Clear accept button after accepting request
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
            formData.append('to_id', profileIdInput.value); // Override 'to_id' with profile ID

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