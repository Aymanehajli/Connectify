@include('components.master')

<title>Authentification</title>
<br><br>
<div class="container w-75 my-2 bg-light p-5">

<center>
<h2>Authentification</h2>
<br>

<form action="{{route('login')}}" method="post">
    @csrf
  <div class="row mb-3">
    <label  class="col-sm-2 col-form-label">Email</label>
    <div class="col-sm-10">
      <input type="email" class="form-control" name="email" >
      @error('email')
      {{$message}}
      @enderror

      @if ($errors->any())
        <div>
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
  <div class="row mb-3">
    <label  class="col-sm-2 col-form-label">Password</label>
    <div class="col-sm-10">
    <div class="password-toggle">
      <input type="password" name="password" id="password" class="form-control" >
      <i class="fas fa-eye toggle-icon" onclick="togglePassword('password')"></i>
                </div>
    </div>
  </div>
  
  <button type="submit" class="btn btn-primary">Sign in</button>
</form>



</center>


</div>
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
    
</style>

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
</script>