<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <style>
        /* Styles for the form container */
        .create-post-container {
            display: none; /* Initially hidden */
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            width: 45%;
            margin: 0 auto;
        }

        /* Style for the input placeholder */
        .create-post-input {
            width: 200px; /* Adjust width as needed */
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            cursor: pointer;
        }

        /* Style for the text area */
        .create-post-textarea {
            width: 100%;
            min-height: 100px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            resize: vertical; /* Allow vertical resizing of the textarea */
            margin-bottom: 10px;
        }

        /* Style for the upload button container */
        .upload-btn-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        /* Style for the upload button */
        .form-control-file {
            padding: 8px 16px;
            border-radius: 5px;
            background-color: #1877f2;
            color: #fff;
            border: none;
            margin-right: 10px;
            cursor: pointer;
        }

        /* Style for the submit button */
        .submit-btn {
            padding: 8px 16px;
            border-radius: 5px;
            background-color: #1877f2;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        /* Style for the submit button on hover */
        .submit-btn:hover {
            background-color: #0e5a9c;
        }
    </style>
</head>
<body>
    <!-- Input placeholder to toggle the form visibility -->
    <input id="toggleFormInput" type="text" class="form-control create-post-input" placeholder="Create Post">

    <!-- Form container -->
    <div id="postForm" class="create-post-container">
        <h3>Create Post :</h3>
        <form action="{{ route('publication.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="titre">Title</label>
                <input type="text" name="titre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="body">Content</label>
                <textarea name="body" class="create-post-textarea" required placeholder="What's on your mind?"></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" class="form-control-file" accept="image/*">
            </div>
            <div class="form-group">
                <label for="video">Video</label>
                <input type="file" name="video" class="form-control-file" accept="video/*">
            </div>
            <button type="submit" class="submit-btn">Post</button>
        </form>
    </div>

    <!-- Script to handle form visibility -->
    <script>
        const toggleFormInput = document.getElementById('toggleFormInput');
        const postForm = document.getElementById('postForm');

        toggleFormInput.addEventListener('click', () => {
            postForm.style.display = postForm.style.display === 'none' ? 'block' : 'none';
        });
    </script>
</body>
</html>
