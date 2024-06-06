@include('components.master')

<div class="container-fluid d-flex flex-column justify-content-center align-items-center">
  @if (auth()->id() == $user->id)
    @include('components.createpost')
  @endif

  <div class="row w-100 mt-5">
    <!-- Publications Section -->
    <div class="col-md-8">
      <div class="card shadow-sm border">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h3>Publications</h3>
        </div>
        <div class="card-body">
          @foreach($publications as $publication)
            <x-publication :canUpdate="auth()->user()->id === $publication->user_id" :publication="$publication" class="mb-3" />
          @endforeach
        </div>
      </div>
    </div>

    <!-- Friends and Friend Suggestions Section -->
    <div class="col-md-4">
      <div class="sticky-top">
        <!-- Friends Section -->
        <div class="card shadow-sm border mb-3">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Friends</h3>
            <a href="/friends" class="text-muted">See All</a>
          </div>
          <div class="card-body">
            <ul class="list-group">
              @foreach($friends as $friend)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <a href="{{ route('user.show', $friend->id) }}" class="text-decoration-none d-flex align-items-center">
                    <div class="d-flex align-items-center">
                      <img src="{{ asset('storage/' . $friend->image) }}" alt="Avatar" width="40" class="rounded-circle mr-2">
                      <span>{{ $friend->name }}</span>
                    </div>
                  </a>
                  <a href="/chat" class="btn btn-sm btn-outline-primary">Message</a>
                </li>
              @endforeach
            </ul>
          </div>
        </div>

        <!-- Friend Suggestions Section -->
        <div class="card shadow-sm border">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h3>Friend Suggestions</h3>
          </div>
          <div class="card-body">
            @foreach($friendSuggestions as $suggestedUser)
              <div class="d-flex justify-content-between align-items-center mb-2">
                <a href="{{ route('user.show', $suggestedUser->id) }}" class="text-decoration-none d-flex align-items-center">
                  <div class="d-flex align-items-center">
                    <img src="{{ asset('storage/' . $suggestedUser->image) }}" alt="Avatar" width="40" class="rounded-circle mr-2">
                    <span>{{ $suggestedUser->name }}</span>
                  </div>
                </a>
                <form action="{{ route('friend-request.send', $suggestedUser->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-primary">Add Friend</button>
                </form>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .sticky-top {
    position: -webkit-sticky;
    position: sticky;
    top: 20px; /* Adjust this value to control the sticky position */
  }
</style>
