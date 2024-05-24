<link href="\bootstrap-5.3.3-dist\bootstrap-5.3.3-dist\css\bootstrap.css" rel="stylesheet">
   
    <script src="\bootstrap-5.3.3-dist\bootstrap-5.3.3-dist\js\bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" type="image/png" href="{{ asset('Logoconnectify.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@php use App\View\Components\Alert; @endphp


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom styles for Facebook navbar */
        .facebook-navbar {
            background-color: #3b5998; /* Facebook blue color */
        }

        .facebook-navbar .navbar-brand {
            color: #fff;
            font-size: 24px;
            font-weight: bold;
        }

        .facebook-navbar .navbar-nav .nav-link {
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            margin-right: 15px;
            transition: all 0.3s ease;
        }

        .facebook-navbar .navbar-nav .nav-link:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        .facebook-navbar .nav-item.dropdown .dropdown-toggle::after {
            display: none; /* Hide the default dropdown arrow */
        }

        .facebook-navbar .nav-item.dropdown .dropdown-menu {
            background-color: #3b5998; /* Facebook blue color */
            border: none;
            border-radius: 0;
        }

        .facebook-navbar .nav-item.dropdown .dropdown-menu .dropdown-item {
            color: #fff;
            font-size: 16px;
        }

        .facebook-navbar .nav-item.dropdown .dropdown-menu .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .facebook-navbar .nav-item .badge-notification {
            background-color: #dc3545; /* Bootstrap danger color */
            font-size: 12px;
            padding: 4px 8px;
        }

        .facebook-navbar .nav-item .dropdown-menu .dropdown-item .badge-notification {
            margin-right: 10px;
        }

        .facebook-navbar .nav-item .dropdown-menu .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Custom styles for the search form */
        .facebook-navbar .search-form input[type="text"] {
            border-radius: 25px;
            padding: 8px 15px;
            border: none;
            margin-right: 10px;
        }

        .facebook-navbar .search-form .btn-search {
            border-radius: 25px;
            padding: 8px 15px;
            border: none;
            background-color: #fff;
            color: #000;
        }

        .facebook-navbar .search-form .btn-search:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg facebook-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Connectify</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.index') }}">Show all users</a>
                        </li>
                        <li class="nav-item">
                        
                            <a class="nav-link" href="{{ route('publication.index') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('publication.create') }}">Ajouter publication</a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('chat.index') }}">Messages</a>
                        </li>
                        
      
        <!-- Notifications Dropdown -->
        <div class="dropdown">
            <button class="nav-link dropdown-toggle" type="#" id="notificationsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="far fa-bell"></i>
                <span id="unreadNotificationsCount" class="badge badge-pill badge-danger bg-danger"></span>
            </button>
            <div class="dropdown-menu" aria-labelledby="notificationsDropdown" id="notificationsDropdownMenu">
                <!-- Notifications will be loaded via AJAX -->
            </div>
        </div>
    @endauth
    @guest
                    <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.create') }}">Inscription</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('loginshow') }}">Connection</a>
                        </li>
                        @endguest
                        
                </ul>
                
                <form class="form-inline my-2 my-lg-0 search-form" id="friendSearchForm" action="{{ route('friends.search') }}" method="GET">
                    <input class="form-control mr-sm-2" type="text" name="search" id="searchInput" placeholder=" Search" >
                    <button class="btn btn-outline-success my-2 my-sm-0 btn-search" type="submit" id="searchButton">Search</button>
                </form>
                
  
                @auth
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle" src="{{ asset('storage/' . auth()->user()->image) }}" width="30" height="30" alt="User Image">
                                {{ auth()->user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('settings.index') }}">Settings</a>
                            </div>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" href="{{ route('logout') }}">Déconnexion</a>
                            </div>
                        </li>
                    </ul>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>



<div id="searchResults"  >

</div>

<style>
    .profilesearch-img {
        border-radius: 50%;
        width: 50px; /* Adjust the size as needed */
        height: 50px; /* Adjust the size as needed */
        object-fit: cover; /* Maintain aspect ratio */
        margin-right: 10px; /* Optional margin between image and text */
    }

    ul {
        list-style: none;
        padding: 0;
        margin: 0;
        position: relative;
        padding-right: 0;
    }

    ul li {
        margin-bottom: 20px; /* Optional spacing between list items */
    }
</style>





<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('friendSearchForm');
        const searchInput = document.getElementById('searchInput');
        const searchResultsContainer = document.getElementById('searchResults');

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            searchFriends();
        });

        function searchFriends() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery !== '') {
                fetch(`{{ route('friends.search') }}?search=${encodeURIComponent(searchQuery)}`)
                    .then(response => response.json())
                    .then(data => displaySearchResults(data.friends))
                    .catch(error => console.error('Error fetching search results:', error));
            }
        }

        function displaySearchResults(friends) {
            searchResultsContainer.innerHTML = '';
            if (friends.length > 0) {
                const ul = document.createElement('ul');
                
                friends.forEach(friend => {
                    const li = document.createElement('li');
                    const link = document.createElement('a');
                    const img = document.createElement('img');

                    link.href = `{{ route('user.show', 'user_id') }}`.replace('user_id', friend.id);
                    ul.classList.add('ul-searcheresult');
                    link.textContent = friend.name;

                    img.src = `{{ asset('storage/') }}/${friend.image}`; // Assuming 'image' is the column name for the profile image path in your database
                    img.alt = friend.name;
                    img.classList.add('profilesearch-img'); // Apply the CSS class for rounded images
                    link.appendChild(img);
                    li.appendChild(link);
                    ul.appendChild(li);
                });
                searchResultsContainer.appendChild(ul);
            } else {
              const noResults = document.createElement('div');
                noResults.classList.add('list-group-item');
                noResults.textContent = 'No friends found.';
                searchResultsContainer.appendChild(noResults);
            }
        }
    });
</script>



<script>
    $(document).ready(function() {
        // Function to fetch notifications via AJAX
        function loadNotifications() {
            $.ajax({
                url: "{{ route('notifications.fetch') }}", // Adjust the route name as per your routes setup
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    var notificationsDropdownMenu = $('#notificationsDropdownMenu');
                    var unreadNotificationsCount = response.unreadNotificationsCount; // Use the correct property name

                    notificationsDropdownMenu.empty(); // Clear previous notifications
                    $('#unreadNotificationsCount').text(unreadNotificationsCount);

                    if (response.notifications.length === 0) {
                        notificationsDropdownMenu.append('<span class="dropdown-item">No notifications</span>');
                    } else {
                        response.notifications.forEach(function(notification) {
                            var formattedDate = new Date(notification.created_at).toLocaleString();
                            var notificationItem = '<a class="dropdown-item" href="' + notification.url + '">' + notification.message + ' <small>(' + formattedDate + ')</small></a>';
                            notificationsDropdownMenu.append(notificationItem);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', xhr.responseText);
                }
            });
        }

        // Initial load of notifications when the page loads
        loadNotifications();

        // Set interval to reload notifications every 30 seconds (adjust as needed)
        setInterval(function() {
            loadNotifications();
        }, 1000);
    });
</script>






<script>
    document.addEventListener('DOMContentLoaded', function () {
        const messageContainer = document.getElementById('messageContainer');

        messageContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('sendMessageBtn')) {
                const form = event.target.closest('.send-message-form');
                const formData = new FormData(form);
                const profileId = form.dataset.profileId;
                formData.append('to_id', profileId);

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
                        fetchMessages(); // Fetch and update messages after sending message
                    } else {
                        alert('Error sending message. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error sending message:', error);
                    alert('Error sending message. Please try again.');
                });
            }
        });
        

        function fetchMessages() {
            fetch("{{ route('chat.index') }}")
            .then(response => response.text())
            .then(html => {
                messageContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
            });
        }

        // Automatically fetch messages every 5 seconds
        setInterval(fetchMessages, 300000);
    });
</script>
