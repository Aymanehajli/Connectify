@foreach ($notifications as $notification)
    <div class="notification">
        <p>{{ $notification->message }}</p>
        @unless ($notification->read_at)
            <form class="mark-as-read-form" action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="button" class="mark-as-read-btn">Mark as Read</button>
            </form>
        @endunless
    </div>
@endforeach

{{ $notifications->links() }}

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const markAsReadButtons = document.querySelectorAll('.mark-as-read-btn');
        
        markAsReadButtons.forEach(button => {
            button.addEventListener('click', () => {
                const form = button.closest('.mark-as-read-form');
                const url = form.getAttribute('action');

                fetch(url, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({}),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        form.parentNode.remove();
                    } else {
                        alert('Failed to mark notification as read.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                });
            });
        });
    });
</script>
