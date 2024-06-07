<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier votre publication</title>
    @include('components.master')
    <style>
        .container {
            max-width: 75%;
            margin-top: 2rem;
            background-color: #f8f9fa;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
        .form-control-file {
            margin-top: 0.5rem;
        }
        .text-danger {
            margin-top: 0.5rem;
        }
        .media-preview {
            margin-top: 1rem;
        }
        video {
            display: block;
            margin: 0 auto;
        }
        .btn-primary {
            margin-top: 2rem;
            padding: 0.5rem 2rem;
        }
        h2 {
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>

<div class="container">
    @if ($errors->any())
        <x-alert type="danger">
            <h6>Errors :</h6>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <form action="{{ route('publication.update', $publication->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <center>
            <h2>Modifier votre publication</h2>
        </center>
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" name="titre" value="{{ $publication->titre }}" class="form-control" id="formGroupExampleInput" placeholder="Entrer le titre">
            @error('titre')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="body" class="form-label">Description</label>
            <input type="text" name="body" value="{{ $publication->body }}" class="form-control" id="formGroupExampleInput2" placeholder="Entrer une description">
        </div>

        @if ($publication->image)
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" name="image" class="form-control-file" accept="image/*">
                <div class="media-preview">
                    <img src="{{ asset('storage/' . $publication->image) }}" alt="Image actuelle" style="max-width: 100%; height: auto;">
                </div>
            </div>
        @elseif ($publication->video)
            <div class="form-group">
                <label for="video">Video</label>
                <input type="file" name="video" class="form-control-file" accept="video/*">
                <div class="media-preview">
                    <video controls style="max-width: 100%; height: auto;">
                        <source src="{{ asset('storage/' . $publication->video) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        @endif

        <center>
            <button type="submit" class="btn btn-primary">Modifier</button>
        </center>
    </form>
</div>

</body>
</html>
