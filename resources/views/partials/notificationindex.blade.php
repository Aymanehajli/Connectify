<h4 class="mb-4 fw-700 font-xss">Notifications</h4>

@foreach ($notifications as $notification)
    <a href="{{ $notification->url ?? '#' }}">
        <div class="mb-3 border-0 card bg-transparent-card w-100 border-bottom shadow">
            <p class="mt-0 mb-1 font-xsss text-grey-900 fw-700 d-block">
                {{ $notification->created_at->diffForHumans() }}</p>
            <h6 class="text-grey-500 fw-500 font-xssss lh-4">{{ $notification->message }}</h6>
        </div>
    </a>
@endforeach

@if ($notifications->isEmpty())
    <h6 class="text-center text-danger my-3">No Notifications Found!</h6>
@endif
