
<title>Modifier votre publication</title>

    @include('components.master')
     <br>
     <div class="container w-75 my-2 bg-light p-5">



     @if ($errors-> any())
     <x-alert type="danger">
      <h6>Errors :</h6>
      <ul>
        @foreach($errors->all() as $error)
        <li>{{$$error}}</li>
        @endforeach
      </ul>
     </x-alert>
     @endif
      

    <form  action="{{route('publication.update',$publication->id)}}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')
<center>
<h2>Modifier votre publication</h2>
</center>
<br><br>
  
<div class="mb-3">
  <label for="Name" class="form-label">Titre</label>
  <input type="text" name="titre" value="{{old('titre')}}" class="form-control" id="formGroupExampleInput" placeholder="Entrer le titre">
@error('titre')
<div class="text-danger">{{$message}}</div>
@enderror

</div> 


<div class="mb-3">
  <label for="Email" class="form-label">Description</label>
  <input type="text" name="body" value="{{old('body')}}" class="form-control" id="formGroupExampleInput2" placeholder="Entrer une description">
</div>

<div class="mb-3">
  <label class="form-label">Image</label>
  <input type="file" name="image"  class="form-control" >
</div>



<center>
<button type="submit" class="btn btn-primary">Modifier</button>
</center>
   
 </form>

 </div>