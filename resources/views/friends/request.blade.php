@include('navbar.nav')

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friend Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .friend-request-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .friend-request-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .friend-request-card:last-child {
            border-bottom: none;
        }
        .friend-request-card img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .friend-request-card h5 {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="friend-request-container">
        <h2>{{ __('messages.Friend_Requests') }} (<span id="friend-count">{{ count($friendRequests) }}</span>)</h2>
          <div id="friend-request-list">
            
            @forelse ($friendRequests as $friendRequest)
                <div class="friend-request-card" data-id="{{ $friendRequest->id }}">
                    <div>
                        <img src="{{ asset('storage/' . $friendRequest->sender->image) }}" alt="{{ $friendRequest->sender->name }}">
                        <h5>{{ $friendRequest->sender->name }}</h5>
                    </div>
                    <div>
                        <button class="btn btn-success accept-request" data-id="{{ $friendRequest->id }}">{{ __('messages.Accept') }}</button>
                        <button class="btn btn-danger refuse-request" data-id="{{ $friendRequest->id }}">{{ __('messages.Refuse') }}</button>
                    </div>
                </div>
             @empty
                <p>{{ __('messages.No_friend_requests') }}</p>
                
            @endforelse
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Accept friend request
            $(document).off('click', '.accept-request');
            $(document).on('click', '.accept-request', function() {
                var button = $(this);
                var requestId = button.data('id');

                $.ajax({
                    url: '/friend-request/accept/' + requestId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        button.closest('.friend-request-card').remove();
                        alert(response.success);
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.responseJSON.error);
                    }
                });
            });

            // Refuse friend request
            $('.refuse-request').on('click', function() {
                var button = $(this);
                var requestId = button.data('id');

                $.ajax({
                    url: '/friend-request/refuse/' + requestId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        button.closest('.friend-request-card').remove();
                        alert(response.success);
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.responseJSON.error);
                    }
                });
            });
        });
    </script>
</body>
</html>
