<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    @include('components.master')
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .password-toggle {
            position: relative;
        }
        .password-toggle .toggle-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .form-container {
            display: none;
        }
        .form-container.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container w-75 my-5 bg-light p-5">
        <center>
            <h2>Authentication</h2>
            <div class="btn-group my-3" role="group">
                <button type="button" class="btn btn-primary" id="login-toggle">Login</button>
                <button type="button" class="btn btn-secondary" id="signup-toggle">Sign Up</button>
            </div>
        </center>
        
        <!-- Login Form -->
        <div id="login-form" class="form-container active">
            <form action="{{route('login')}}" method="post">
                @csrf
                <div class="form-group row mb-3">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" name="email" id="email">
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror

                        @if ($errors->any())
                            <div class="text-danger">
                                @foreach ($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                            </div>
                        @endif

                        @if (session('email'))
                            <div class="alert alert-success">
                                {{ session('email') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row mb-3">
                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-10">
                        <div class="password-toggle">
                            <input type="password" name="password" id="password" class="form-control">
                            <i class="fas fa-eye toggle-icon" onclick="togglePassword('password')"></i>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Sign in</button>
            </form>
        </div>

        <!-- Signup Form -->
        <div id="signup-form" class="form-container">
            <form action="{{route('user.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" value="{{old('name')}}" class="form-control" id="name" placeholder="Enter your name" required>
                </div> 
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" value="{{old('email')}}" class="form-control" id="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm your password" required>
                </div>
                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" name="image" class="form-control" id="image" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <!-- Script to toggle forms -->
    <script>
        document.getElementById('login-toggle').addEventListener('click', function() {
            document.getElementById('login-form').classList.add('active');
            document.getElementById('signup-form').classList.remove('active');
            this.classList.add('btn-primary');
            this.classList.remove('btn-secondary');
            document.getElementById('signup-toggle').classList.add('btn-secondary');
            document.getElementById('signup-toggle').classList.remove('btn-primary');
        });

        document.getElementById('signup-toggle').addEventListener('click', function() {
            document.getElementById('signup-form').classList.add('active');
            document.getElementById('login-form').classList.remove('active');
            this.classList.add('btn-primary');
            this.classList.remove('btn-secondary');
            document.getElementById('login-toggle').classList.add('btn-secondary');
            document.getElementById('login-toggle').classList.remove('btn-primary');
        });

        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling;
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
