
    @include('components.master')
    @if (auth()->id() == $user->id)

@include('components.createpost')
@endif
    <br>
    
<div class="container w-65 mx-auto">
<div class="row">
  
<h3>Publications :</h3>
      @foreach($publications as $publication)
      <hr style="border: 0cqmin;">
      
      <x-publication :canUpdate="auth()->user()->id === $publication->user_id"  :publication="$publication" />
      @endforeach



</div>

</div>




