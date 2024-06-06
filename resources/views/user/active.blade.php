<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Users</title>
    <style>
        /* Your CSS styles here */
        .user-card {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .user-card img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        .user-card .user-info {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .user-card .user-info .user-name {
            font-weight: bold;
            font-size: 0.9em;
        }
        .user-card .user-info .user-status {
            font-size: 0.8em;
            color: gray;
        }
        .online {
            color: green;
        }
        .offline {
            color: red;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    @include('navbar.nav')

    <aside class="sidebar">
        <!-- Search input -->
        <input type="text" class="search-input" placeholder="Search for users..." oninput="searchUsers(this.value)">
        
        <!-- Online users list -->
        <div id="online-users">
            <!-- Online users will be directly included here -->
            @foreach($activeUsers as $user)
                <div class="user-card">
                <img src="{{ asset('storage/' . $user->image) }}" alt="User Image">
                    <div class="user-info">
                        <div class="user-name">{{ $user->name }}</div>
                        <!-- Add user status if needed -->
                        <!-- <div class="user-status">{{ $user->status }}</div> -->
                    </div>
                </div>
            @endforeach
        </div>
    </aside>
</body>
</html>
