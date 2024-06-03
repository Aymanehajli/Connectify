@include('navbar.nav')
<!-- Add this code where you want to display the current language code -->


<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
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
          <li><a href="#language" class="settings-link">Language</a></li>

          <li><a href="#delete" class="settings-link" >Delete Account</a></li>
         
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
                
                {{ session('success') }}
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
            <h2>{{ __('messages.profile') }}</h2>
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

    <section id="language">
    <h2>Language Settings</h2>
    <form id="languageForm" method="POST" action="{{ route('language.switch') }}">
        @csrf
        <div class="form-group">
            <label for="language">Select Language</label>
            <select class="form-control" id="language" name="language" onchange="document.getElementById('languageForm').submit()">
                <option value="en" {{ App::getLocale() == 'en' ? 'selected' : '' }}>English</option>
                <option value="fr" {{ App::getLocale() == 'fr' ? 'selected' : '' }}>French</option>
                <option value="es" {{ App::getLocale() == 'es' ? 'selected' : '' }}>Spanish</option>
                <!-- Add more languages as needed -->
            </select>
        </div>
    </form>
    <hr>
</section>

            
            <section id="delete">
                <h2>Delete Account</h2>
                    <button type="submit" class="btn btn-danger"  data-toggle="modal" data-target="#deleteConfirmationModal">Delete</button>
                </form>
                <hr>
            </section>
        </main>
    </div>


    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete Account</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete your account? This action cannot be undone.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <form action="{{ route('user.destroy',$user->id) }}" method="post">
            @method('DELETE')
            @csrf
            <button type="submit" class="btn btn-danger">Delete Account</button>
          </form>
        </div>
      </div>
    </div>
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


