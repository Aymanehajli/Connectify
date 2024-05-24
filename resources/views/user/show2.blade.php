<!-- resources/views/profile.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Profile</title>
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body>

    <div class="container">
        <div class="profile-header">
            
            <div class="profile-avatar">
            <img src="{{asset('storage/'.$user->image)}}" alt="Avatar" width="100">
            </div>
            <div class="profile-info">
                <h1>{{ $user->name }}</h1>
                <p>{{ $user->email }}</p>
                <button>Add Friend</button>
                <button>Message</button>
            </div>
        </div>

        <div class="profile-content">
            <h2>About</h2>
            <p>Hello</p>

            <h2>Photos</h2>
            <div class="photo-gallery">
                <!-- Display user's photos -->
                <img src="{{asset('storage/'.$user->image)}}" alt="title" width="100">
                <img src="{{asset('storage/'.$user->image)}}" alt="Avatar" width="100">
            </div>

            <h2>Friends</h2>
            <ul class="friend-list">
                <!-- Display user's friends -->
            </ul>
        </div>
    </div>

</body>
</html>
