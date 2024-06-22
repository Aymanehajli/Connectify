@include('navbar.nav')


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friend List</title>
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
        <h2>Block list :</h2>
        <div id="friend-request-list">

        @forelse ($blocked as $blockedUser)
    <div class="friend-request-card" data-id="{{ $blockedUser->id }}">
     <div>   
         <img src="{{ asset('storage/' . $blockedUser->image) }}" alt="{{ $blockedUser->name }}">
         <h5>{{ $blockedUser->name }}</h5>
     </div>
        
        <div>
              <button class="btn btn-danger unblock-user" data-id="{{ $blockedUser->id }}">Unblock</button>
        </div>
    </div>
@empty
    <p>You have no blocked users.</p>
@endforelse
</div>
</div>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

@php 
$authid = auth()->id();
@endphp


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
          
            // unblock
            $('.unblock-user').on('click', function() {
                var button = $(this);
                var requestId = button.data('id');

                $.ajax({
                    url: '/unblock/' + requestId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        button.closest('.friend-request-card').remove();
                        
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.responseJSON.error);
                    }
                });
            });
        });
    </script>