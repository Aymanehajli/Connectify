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
                    if (data.users && data.users.length > 0) {
                        const onlineUsersDiv = document.getElementById('online-users');

                        // Clear existing online users list
                        onlineUsersDiv.innerHTML = '';

                        // Loop through online users and create user cards
                        data.users.forEach(user => {
                            const userCard = document.createElement('div');
                            userCard.classList.add('user-card');

                            // User image
                            const userImage = document.createElement('img');
                            userImage.src = user.image; // Assuming the user model has an 'image' attribute
                            userImage.alt = user.name; // Alt text for accessibility
                            userCard.appendChild(userImage);

                            // User info container
                            const userInfo = document.createElement('div');
                            userInfo.classList.add('user-info');

                            // User name
                            const userName = document.createElement('div');
                            userName.classList.add('user-name');
                            userName.textContent = user.name;
                            userInfo.appendChild(userName);

                            // User status (online/offline)
                            const userStatus = document.createElement('div');
                            userStatus.classList.add('user-status', 'online');
                            userStatus.textContent = 'Online';
                            userInfo.appendChild(userStatus);

                            userCard.appendChild(userInfo);

                            // Append user card to online users list
                            onlineUsersDiv.appendChild(userCard);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching online users:', error);
                });
        }

        // Initial fetch and then polling every 5 seconds
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