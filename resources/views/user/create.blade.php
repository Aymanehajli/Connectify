
<title>Inscription</title>

    @include('components.master')
     <br>
     <div class="container w-75 my-2 bg-light p-5">
      

    <form  action="{{route('user.store')}}" method="POST" enctype="multipart/form-data">
@csrf
<center>
<h2>Ajouter un compte</h2>
</center>
<br><br>
  
<div class="mb-3">
  <label for="Name" class="form-label">Name</label>
  <input type="text" name="name" value="{{old('name')}}" class="form-control" id="formGroupExampleInput" placeholder="Entrer votre nom">
  </div> 


<div class="mb-3">
  <label for="Email" class="form-label">Email</label>
  <input type="email" name="email" value="{{old('email')}}" class="form-control" id="formGroupExampleInput2" placeholder="Entrer votre email">
</div>


<div class="mb-3">
  <label for="Password" class="form-label">Password</label>
  <input type="password" name="password" class="form-control" id="formGroupExampleInput2" placeholder="Entrer votre mot de pass">
</div>
<div class="mb-3">
  <label for="Password2" class="form-label">Password</label>
  <input type="password" name="password_confirmation" class="form-control" id="formGroupExampleInput2" placeholder="Confirmer votre mot de pass">
</div>

<div class="mb-3">
  <label class="form-label">Image</label>
  <input type="file" name="image"  class="form-control" >
</div>



<center>
<button type="submit" class="btn btn-primary">Submit</button>
</center>
   
 </form>

 </div>



 <script>
    document.addEventListener('DOMContentLoaded', function () {
    const registrationForm = document.getElementById('registrationForm');

    registrationForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(registrationForm);

        fetch(registrationForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Registration successful');
                window.location.href = "{{ route('user.create') }}"; // Redirect to desired page
            } else {
                showAlert('error', 'Registration failed');
            }
        })
        .catch(error => {
            console.error('Error registering user:', error);
            showAlert('error', 'Error registering user');
        });
    });

    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.classList.add('alert', `alert-${type}`);
        alertDiv.textContent = message;

        // Append the alert to a container (e.g., <div id="alertsContainer"></div>)
        const alertsContainer = document.getElementById('alertsContainer');
        alertsContainer.appendChild(alertDiv);

        // Remove the alert after a certain duration (e.g., 5 seconds)
        setTimeout(() => {
            alertDiv.remove();
        }, 5000); // 5 seconds (5000 milliseconds)
    }
});

</script>