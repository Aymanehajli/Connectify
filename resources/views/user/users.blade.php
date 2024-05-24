
    @include('components.master')

    <br><br><br>

    <center>
    <div class="container w-275 my-132 bg-light p-5">

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<table class="table">

<tr  class="table-dark">
<th>ID</th>
<th>Image</th>
<th>Name</th>
<th>Email</th>

<th>More information</th>
<th>edit</th>

<th>Delete</th>
</tr>
@foreach ($users as $user)
<tr>
   

    <td>{{ $user->id }}</td>
    <td>
        <img src="{{asset('storage/'.$user->image)}}" alt="Avatar" width="100"></td>
        
    
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    
    <td><a class="btn btn-primary" href="{{route('user.show',$user->id)}}">Afficher </a  ></td>
    <td>
        <form action="{{route('user.edit',$user->id)}}" method="GET">
           
            @csrf
            <button class="btn btn-primary" float-end>Edit</button>
        </form>
    </td>
    <td>
        <form action="{{route('user.destroy',$user->id)}}" method="post">
            @method('DELETE')
            @csrf
            <button class="btn btn-danger" float-end>Supprimer</button>
        </form>
    </td>
    



</tr>
@endforeach
</table>
<br>
<br>
<br>
{{ $users->links()}}

</center>




</div>