<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        .post-date {
            font-size: 0.9rem;
            color: #888;
            margin-bottom: 15px;
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

        .comments-section {
            margin-top: 20px;
        }

        .comment {
            margin-bottom: 15px;
        }

        .comment .comment-body {
            margin-left: 10px;
        }

        .add-comment {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }

        .add-comment input {
            flex: 1;
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

                <!-- Post Date -->
                <div class="post-date">
                    <span>{{ $publication->created_at->format('F d, Y') }}</span>
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
                    @endif
                </div>

                @if ($publication->shared_by)
                    <p class="text-muted">Shared from {{ $publication->sharedByUser->name }}</p>
                @endif

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
                    <button class="btn btn-info mx-2 comments-btn" data-id="{{ $publication->id }}"> Comments</button>

                    <!-- Share button -->
                    <button type="button" class="btn btn-success mx-2 share-btn" data-id="{{ $publication->id }}">Share</button>
                </div>

                <!-- Comments Section -->
                <div class="comments-section">
                    <h5>Comments</h5>
                    <div id="commentsList-{{ $publication->id }}">
                        <!-- Comments will be loaded here -->
                    </div>

                    <div id="loadMoreComments-{{ $publication->id }}" class="text-center mt-3" style="display: none;">
                        <button class="btn btn-secondary load-more-comments-btn" data-id="{{ $publication->id }}">Load More Comments</button>
                    </div>

                    <div class="add-comment">
                        <input type="text" class="form-control comment-input" data-id="{{ $publication->id }}" id="comment-input-{{ $publication->id }}" placeholder="Add a comment...">
                        <button class="btn btn-primary add-comment-btn" data-id="{{ $publication->id }}" data-input-id="comment-input-{{ $publication->id }}">Comment</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).off('click', '.like-btn');
            $(document).off('click', '.comments-btn');
            $(document).off('click', '.add-comment-btn');
            $(document).off('click', '.load-more-comments-btn');
            $(document).off('click', '.share-btn');

            $(document).on('click', '.like-btn', function() {
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

            // Event delegation for comments button
            $(document).on('click', '.comments-btn', function() {
                var button = $(this);
                var postId = button.data('id');
                var commentsList = $('#commentsList-' + postId);
                var loadMoreComments = $('#loadMoreComments-' + postId);
                commentsList.html('Loading comments...');

                $.ajax({
                    url: '/publications/comments/' + postId,
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            commentsList.html('');
                            response.comments.forEach(function(comment) {
                                commentsList.append('<div class="comment"><div class="user-profile"><img src="' + comment.user_image + '" alt="User Image"><div class="comment-body"><h6>' + comment.user_name + '</h6><p>' + comment.comment + '</p></div></div></div>');
                            });
                            if (response.next_page_url) {
                                loadMoreComments.show().data('next-page-url', response.next_page_url);
                            } else {
                                loadMoreComments.hide();
                            }
                        } else {
                            commentsList.html('<p>An error occurred: ' + response.message + '</p>');
                        }
                    },
                    error: function(xhr) {
                        commentsList.html('<p>An error occurred: ' + xhr.responseText + '</p>');
                    }
                });
            });

            $(document).on('click', '.load-more-comments-btn', function() {
        var button = $(this);
        var postId = button.data('id');
        var commentsList = $('#commentsList-' + postId);
        var nextPageUrl = button.data('next-page-url');

        if (!nextPageUrl) {
            alert('No more comments to load.');
            return;
        }

        $.ajax({
            url: nextPageUrl,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    response.comments.forEach(function(comment) {
                        commentsList.append('<div class="comment"><div class="user-profile"><img src="' + comment.user_image + '" alt="User Image"><div class="comment-body"><h6>' + comment.user_name + '</h6><p>' + comment.comment + '</p></div></div></div>');
                    });
                    if (response.next_page_url) {
                        button.data('next-page-url', response.next_page_url);
                    } else {
                        button.hide();
                    }
                } else {
                    alert('An error occurred: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('An error occurred: ' + xhr.responseText);
            }
        });
    });
            $(document).on('click', '.add-comment-btn', function() {
                var button = $(this);
                var postId = button.data('id');
                var commentInput = $('.comment-input[data-id="' + postId + '"]');
                var comment = commentInput.val();

                if (comment === '') {
                    alert('Comment cannot be empty.');
                    return;
                }

                $.ajax({
                    url: '/publications/comments/' + postId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        comment: comment
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#commentsList-' + postId).append('<div class="comment"><div class="user-profile"><img src="' + response.comment.user_image + '" alt="User Image"><div class="comment-body"><h6>' + response.comment.user_name + '</h6><p>' + response.comment.comment + '</p></div></div></div>');
                            commentInput.val('');
                        } else {
                            alert('An error occurred: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.responseText);
                    }
                });
            });

            $(document).on('click', '.share-btn', function() {
                var button = $(this);
                var postId = button.data('id');

                $.ajax({
                    url: '/publication/share/' + postId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Post shared successfully!');
                            // Optionally update the UI or perform other actions
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
</body>
</html>
