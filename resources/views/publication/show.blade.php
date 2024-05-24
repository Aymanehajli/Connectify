@include('components.master')


<center>

<table class="table">


<tr  class="table-dark">
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Date verification</th>
<th>Date de creation</th>

</tr>

<tr>
   

    <td>{{ $user->id }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->email_verified_at }}</td>
    <td>{{ $user->created_at }}</td>
 

</tr>

</table>


</center>




