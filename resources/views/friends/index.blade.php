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
        .friends-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .friend-card {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .friend-card:last-child {
            border-bottom: none;
        }
        .friend-card img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
        }
        .friend-card h5 {
            margin: 0;
        }
        .friend-card p {
            margin: 0;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="friends-container">
        <h2>Your Friends</h2>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
