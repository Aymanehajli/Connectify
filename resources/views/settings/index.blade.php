@include('navbar.nav')

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .settings-container {
            max-width: 1200px;
            margin: 50px auto;
            display: flex;
        }
        .settings-aside {
            width: 25%;
            padding: 20px;
            background-color: #fff;
            border-right: 1px solid #dee2e6;
        }
        .settings-main {
            width: 75%;
            padding: 20px;
            background-color: #fff;
        }
        .settings-nav {
            list-style: none;
            padding: 0;
        }
        .settings-nav li {
            margin-bottom: 10px;
        }
        .settings-nav a {
            color: #343a40;
            text-decoration: none;
        }
        .settings-nav a:hover {
            text-decoration: underline;
        }
        .strength-indicator {
            margin-top: 5px;
            font-size: 0.9em;
        }
        .weak {
            color: red;
        }
        .medium {
            color: orange;
        }
        .strong {
            color: green;
        }

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
    </style>
</head>
<body>

    <div class="settings-container">
        <aside class="settings-aside">
            <nav>
                <ul class="settings-nav">
                    <li><a href="#profile" class="settings-link">Profile Settings</a></li>
                    <li><a href="#account" class="settings-link">Account Settings</a></li>
                    <li><a href="#delete" class="settings-link">Delete Account</a></li>
                </ul>
            </nav>
        </aside>
        <main class="settings-main">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @else
                <div class="alert alert-warning">
                    {{ session('status') }}
                </div>
            @endif

            <section id="image">
                <h2>Profile Image</h2>
                <form action="{{ route('settings.image') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="username">Profile Image</label>
                        <input type="file" class="form-control" id="image" name="image" value="{{ old('image', $user->image) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
                <hr>
            </section>

            <section id="profile">
                <h2>Profile Settings</h2>
                <form action="{{ route('settings.updateProfile') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
                <hr>
            </section>
            
            <section id="account">
        <h2>Account Settings</h2>
        <form id="accountForm" action="{{ route('settings.updateAccount') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="old_password">Old Password</label>
                <div class="password-toggle">
                    <input type="password" class="form-control" id="old_password" name="old_password" required>
                    <i class="fas fa-eye toggle-icon" onclick="togglePassword('old_password')"></i>
                </div>
            </div>
            <div class="form-group">
                <label for="password">New Password</label>
                <div class="password-toggle">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <i class="fas fa-eye toggle-icon" onclick="togglePassword('password')"></i>
                </div>
                <div id="passwordStrength" class="strength-indicator"></div>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <div class="password-toggle">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    <i class="fas fa-eye toggle-icon" onclick="togglePassword('password_confirmation')"></i>
                </div>
            </div>
            <button type="submit" id="submitBtn" class="btn btn-primary">Save Changes</button>
        </form>
        <hr>
    </section>
            
            <section id="delete">
                <h2>Delete Account</h2>
                <form action="{{route('user.destroy',$user->id)}}" method="post">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                <hr>
            </section>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
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

        const passwordInput = document.getElementById('password');
        const passwordStrength = document.getElementById('passwordStrength');
        const submitBtn = document.getElementById('submitBtn');
        
        passwordInput.addEventListener('input', function() {
            const strength = checkPasswordStrength(passwordInput.value);
            passwordStrength.textContent = strength.message;
            passwordStrength.className = `strength-indicator ${strength.class}`;
            submitBtn.disabled = strength.class === 'weak';
        });

        function checkPasswordStrength(password) {
            let strength = { message: 'Weak', class: 'weak' };
            if (password.length >= 8) {
                strength.message = 'Medium';
                strength.class = 'medium';
            }
            if (password.length >= 12 && /[A-Z]/.test(password) && /[0-9]/.test(password) && /[!@#\$%\^&\*]/.test(password)) {
                strength.message = 'Strong';
                strength.class = 'strong';
            }
            return strength;
        }
    </script>
</body>
</html>
