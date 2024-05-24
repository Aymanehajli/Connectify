<h1>Notifications</h1>

@if($notifications->isEmpty())
    <p>No notifications found.</p>
@else
    <ul>
        @foreach($notifications as $notification)
            <li>{{ $notification->message }}</li>
        @endforeach
    </ul>
@endif



<h1>
    second
</h1>


@foreach($notifications as $notification)
    <p>{{ $notification->message }}</p>
@endforeach

{{-- Accessing unreadNotifications --}}
@foreach($unreadNotifications as $unread)
    <p>{{ $unread->message }}</p>
@endforeach


