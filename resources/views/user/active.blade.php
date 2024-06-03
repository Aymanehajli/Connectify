@include('navbar.nav')


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Users</title>
    <style>
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
    <aside class="sidebar">
        <!-- Search input -->
        <input type="text" class="search-input" placeholder="Search for users..." oninput="searchUsers(this.value)">
        
        <!-- Online users list -->
        <div id="online-users">
            <!-- Online users will be dynamically added here -->
        </div>
    </aside>

    <script>
function fetchOnlineUsers() {
  fetch("/getActiveUsers")
    .then(response => response.json())
    .then(data => {
      const onlineUsersDiv = document.getElementById('online-users');
      onlineUsersDiv.innerHTML = ''; // Clear existing users

      data.users.forEach(user => {
        // Use user.name and user.image to populate your user cards
      });
    })
    .catch(error => {
      console.error('Error fetching online users:', error);
    });
}

// Call on initial load and every 5 seconds
fetchOnlineUsers();
setInterval(fetchOnlineUsers, 5000);
</script>
</body>
</html>

<aside class="sidebar">
    <!-- Search input -->
    <input type="text" class="search-input" placeholder="Search for users..." oninput="searchUsers(this.value)">
    
    <!-- Online users list -->
    <div id="online-users">
        <!-- Online users will be dynamically added here -->
    </div>
</aside>