<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Custom styles */
        .facebook-post-card {
            max-width: 800px;
            margin: 20px auto;
            background-color: #FCFCFC;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .facebook-post-card .card-body {
            padding: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .user-profile img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
            cursor: pointer;
        }

        .user-profile h5 {
            margin-bottom: 0;
            cursor: pointer;
        }

        .post-content {
            margin-bottom: 20px;
        }

        .post-content img {
            width: 100%;
            max-width: 100%;
            height: 300px; /* Fixed height for all post images */
            object-fit: cover; /* Ensures image covers the area without distortion */
            border-radius: 8px;
        }

        .post-actions {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .post-actions .btn {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card facebook-post-card">
            <div class="card-body">
                <div class="dropdown">
                    @auth
                        @if ($canUpdate === true)
                            <button class="float-end btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" style="background-color: #717D7E; color: #FFFFFF;" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li>
                                    <form action="{{ route('publication.edit', $publication->id) }}" method="GET">
                                        @csrf
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Modifier</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('publication.destroy', $publication->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Supprimer</button>
                                    </form>
                                </li>
                            </ul>
                        @endif
                    @endauth
                </div>

                <div class="user-profile">
                    <a href="{{ route('user.show', $publication->user->id) }}">
                        <img src="{{ asset('storage/' . $publication->user->image) }}" alt="User Image">
                        <h5>{{ $publication->user->name }}</h5>
                    </a>
                </div>

                <div class="post-content">
                    <p>{{ $publication->titre }}</p>
                    <hr>
                    <p>{{ $publication->body }}</p>
                    @if ($publication->image)
        <img class="img-fluid" src="{{ asset('storage/' . $publication->image) }}" alt="{{ $publication->titre }}">
    @endif
    
    @if ($publication->video)
        <video class="img-fluid" controls>
            <source src="{{ asset('storage/' . $publication->video) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    @endif </div>

                <hr>

                <div class="post-actions text-center">
                    <!-- Like button -->
                    @unless ($publication->isLikedByUser(auth()->id()))
                        <button class="btn btn-primary mx-2 like-btn" data-id="{{ $publication->id }}" data-action="like">{{ $publication->likes }} Like</button>
                    @endunless

                    @if ($publication->isLikedByUser(auth()->id()))
                        <button class="btn btn-primary mx-2 like-btn" data-id="{{ $publication->id }}" data-action="dislike">{{ $publication->likes }} Dislike</button>
                    @endif

                    <!-- Comments button -->
                    <button class="btn btn-info mx-2" data-bs-toggle="modal" data-bs-target="#commentsModal">{{ $publication->comments }} Comments</button>

                    <!-- Share button -->
                    <button class="btn btn-success mx-2">Share</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<script>
    // Example JavaScript code for handling button actions
    document.addEventListener('DOMContentLoaded', function () {
        const likeButton = document.querySelector('.btn-primary');
        const commentsButton = document.querySelector('.btn-info');
        const shareButton = document.querySelector('.btn-success');

        likeButton.addEventListener('click', function () {
            // Implement like functionality
        });

        commentsButton.addEventListener('click', function () {
            // Implement comments modal opening
            const commentsModal = new bootstrap.Modal(document.getElementById('commentsModal'));
            commentsModal.show();
        });

        shareButton.addEventListener('click', function () {
            // Implement share functionality
        });
    });
</script>

<!-- Share Modal -->
<div id="shareModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Share this post</h2>
            <p>Copy this link to share:</p>
            <input type="text" id="shareLink" class="form-control" value="https://example.com/post/123" readonly>
            <button class="share-btn" id="copyLinkBtn">Copy Link</button>
        </div>
    </div>

    <!-- Script to handle form visibility and share modal -->
    <script>
        const toggleFormInput = document.getElementById('toggleFormInput');
        const postForm = document.getElementById('postForm');
        const shareModal = document.getElementById('shareModal');
        const closeModalBtn = document.querySelector('.close');
        const copyLinkBtn = document.getElementById('copyLinkBtn');
        const shareLink = document.getElementById('shareLink');

        toggleFormInput.addEventListener('click', () => {
            postForm.style.display = postForm.style.display === 'none' ? 'block' : 'none';
        });

        closeModalBtn.onclick = function() {
            shareModal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == shareModal) {
                shareModal.style.display = 'none';
            }
        }

        copyLinkBtn.addEventListener('click', () => {
            shareLink.select();
            document.execCommand('copy');
            alert('Link copied to clipboard!');
        });

        function openShareModal(postId) {
            // Set the share link dynamically (example link used here)
            shareLink.value = `https://example.com/post/${postId}`;
            shareModal.style.display = 'block';
        }

        document.querySelector('.share-btn').addEventListener('click', function() {
            openShareModal(123); // Pass the actual post ID here
        });
    </script>


<!-- Comments Modal -->
<div class="modal fade" id="commentsModal" aria-labelledby="commentsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentsModalLabel">Comments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Comments content goes here -->
                <!-- Example: -->
                <p>This is a comment.</p>
                <p>Another comment.</p>
            </div>
        </div>
    </div>
</div>


<script>
        $(document).ready(function() {
            $('.like-btn').on('click', function() {
                var button = $(this);
                var postId = button.data('id');
                var action = button.data('action');
                var url = action === 'like' ? '/publications/like/' + postId : '/publications/dislike/' + postId;

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: action === 'like' ? 'POST' : 'PUT'
                    },
                    success: function(response) {
                        if (response.success) {
                            button.data('action', action === 'like' ? 'dislike' : 'like');
                            button.text(response.likes + (action === 'like' ? ' Dislike' : ' Like'));
                        } else {
                            alert('An error occurred: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            });
        });
    </script>


<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    