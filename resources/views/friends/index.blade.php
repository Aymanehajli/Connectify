<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Interface with Friend Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Arial', sans-serif;
        }
        .container {
            display: flex;
            margin-top: 20px;
            padding: 20px;
        }
        .sidebar, .main-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .sidebar {
            width: 30%;
            margin-right: 20px;
            background-color: #e9ecef;
        }
        .main-content {
            width: 70%;
            background-color: #ffffff;
        }
        .friend-request-card, .friend-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            transition: background-color 0.3s;
        }
        .friend-request-card:hover, .friend-card:hover {
            background-color: #f1f1f1;
        }
        .friend-request-card img, .friend-card img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
        }
        .friend-request-card h5, .friend-card h5 {
            margin: 0;
            font-size: 1.1em;
        }
        .friend-card p {
            margin: 0;
            color: #6c757d;
            font-size: 0.9em;
        }
        .btn {
            padding: 5px 10px;
            font-size: 0.9em;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    @include('navbar.nav')
    <div class="container">
        <div class="sidebar">
            <h2>Friend Requests</h2>
            <div id="friend-request-list">
                @forelse ($friendRequests as $friendRequest)
                    <div class="friend-request-card" data-id="{{ $friendRequest->id }}">
                        <div>
                            <img src="{{ asset('storage/' . $friendRequest->sender->image) }}" alt="{{ $friendRequest->sender->name }}">
                            <h5>{{ $friendRequest->sender->name }}</h5>
                        </div>
                        <div>
                            <button class="btn btn-success accept-request" data-id="{{ $friendRequest->id }}">Accept</button>
                            <button class="btn btn-danger refuse-request" data-id="{{ $friendRequest->id }}">Refuse</button>
                        </div>
                    </div>
                @empty
                    <p>No friend requests.</p>
                @endforelse
            </div>
        </div>
        <div class="main-content">
            <h2>Your Friends (<span id="friend-count">{{ count($friends) }}</span>)</h2>
            @forelse ($friends as $friend)
                <div class="friend-card">
                    <img src="{{ asset('storage/' . $friend->image) }}" alt="{{ $friend->name }}">
                    <div>
                        <h5>{{ $friend->name }}</h5>
                        <p>{{ $friend->email }}</p>
                    </div>
                </div>
               @empty
                <p>You have no friends yet.</p>
            @endforelse
        </div>
    </div>
   

    <script>
        $(document).ready(function() {
            console.log("Document is ready");

            // Accept friend request
            $('.accept-request').on('click', function() {
                var button = $(this);
                var requestId = button.data('id');
                console.log("Accept button clicked, request ID:", requestId);

                $.ajax({
                    url: '/friend-request/acceptf/' + requestId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log("Request succeeded:", response);
                        button.closest('.friend-request-card').remove();
                        alert(response.success);
                    },
                    error: function(xhr) {
                        console.log("Request failed:", xhr);
                        alert('An error occurred: ' + xhr.responseJSON.error);
                    }
                });
            });

            // Refuse friend request
            $('.refuse-request').on('click', function() {
                var button = $(this);
                var requestId = button.data('id');
                console.log("Refuse button clicked, request ID:", requestId);

                $.ajax({
                    url: '/friend-request/refusef/' + requestId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log("Request succeeded:", response);
                        button.closest('.friend-request-card').remove();
                        alert(response.success);
                    },
                    error: function(xhr) {
                        console.log("Request failed:", xhr);
                        alert('An error occurred: ' + xhr.responseJSON.error);
                    }
                });
            });
        });
    </script>
</body>
</html>
