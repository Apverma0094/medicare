<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Notifications - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
            padding: 20px;
            position: fixed;
            width: 250px;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar a:hover {
            background-color: rgba(255,255,255,0.1);
            border-radius: 5px;
            padding-left: 10px;
            transition: all 0.3s ease;
        }
        .notification-card {
            border-left: 4px solid;
            transition: all 0.3s ease;
        }
        .notification-card.unread {
            border-left-color: #dc3545;
            background-color: #fff5f5;
        }
        .notification-card.read {
            border-left-color: #28a745;
            background-color: #f8fff8;
        }
        .notification-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="{{ route('admin.dashboard') }}">🏠 Dashboard</a>
        <a href="{{ route('medicines.index') }}">💊 Manage Medicines</a>
        <a href="{{ route('admin.orders.index') }}">📦 Manage Orders</a>
        <a href="{{ route('admin.recent.orders') }}">📋 Recent Orders</a>
        <a href="{{ route('admin.recent.medicines') }}">🆕 Recent Medicines</a>
        <a href="{{ route('admin.notifications.index') }}" style="background: rgba(255,255,255,0.2);">🔔 Notifications</a>
        <a href="{{ route('admin.users') }}">👥 Manage Users</a>
        <a href="{{ route('profile.edit') }}">👤 Profile</a>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn-danger w-100 mt-3">🚪 Logout</button>
        </form>
    </div>

    {{-- Main Content --}}
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>🔔 All Notifications</h1>
            
            <div class="d-flex gap-2">
                <button class="btn btn-success" onclick="markAllAsRead()">
                    ✅ Mark All Read
                </button>
                <button class="btn btn-outline-danger" onclick="clearAllNotifications()">
                    🗑️ Clear All
                </button>
            </div>
        </div>

        <!-- Notification Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h5>📨 Total</h5>
                        <h3>{{ $notifications->total() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-danger text-white">
                    <div class="card-body">
                        <h5>🔴 Unread</h5>
                        <h3>{{ auth()->user()->unreadNotifications()->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h5>✅ Read</h5>
                        <h3>{{ auth()->user()->readNotifications()->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <h5>📦 Orders Today</h5>
                        <h3>{{ \App\Models\Order::whereDate('created_at', today())->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="row">
            <div class="col-12">
                @if($notifications->count())
                    @foreach($notifications as $notification)
                        <div class="card notification-card {{ $notification->read_at ? 'read' : 'unread' }} mb-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                @if($notification->read_at)
                                                    <span class="badge bg-success">✅ Read</span>
                                                @else
                                                    <span class="badge bg-danger">🔴 Unread</span>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-1">{{ $notification->data['message'] ?? 'New Notification' }}</h6>
                                                @if(isset($notification->data['order_number']))
                                                    <p class="mb-1">
                                                        <strong>Order:</strong> {{ $notification->data['order_number'] }}<br>
                                                        <strong>Customer:</strong> {{ $notification->data['customer_name'] ?? 'N/A' }}<br>
                                                        <strong>Amount:</strong> ₹{{ number_format($notification->data['total_amount'] ?? 0, 2) }}
                                                    </p>
                                                @endif
                                                <small class="text-muted">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                    @if($notification->read_at)
                                                        | Read {{ $notification->read_at->diffForHumans() }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4 text-end">
                                        <div class="btn-group" role="group">
                                            @if(isset($notification->data['order_id']))
                                                <a href="{{ route('admin.orders.show', $notification->data['order_id']) }}" class="btn btn-sm btn-outline-primary">
                                                    👁️ View Order
                                                </a>
                                            @endif
                                            
                                            @if(!$notification->read_at)
                                                <button class="btn btn-sm btn-success" onclick="markAsRead('{{ $notification->id }}')">
                                                    ✅ Mark Read
                                                </button>
                                            @endif
                                            
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteNotification('{{ $notification->id }}')">
                                                🗑️ Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <h3 class="text-muted">🔔 No Notifications</h3>
                            <p class="text-muted">You don't have any notifications yet.</p>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Back to Dashboard</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mark single notification as read
        function markAsRead(notificationId) {
            fetch(`/admin/notifications/${notificationId}/read`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to mark notification as read');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while marking notification as read');
            });
        }

        // Mark all notifications as read
        function markAllAsRead() {
            if (confirm('Are you sure you want to mark all notifications as read?')) {
                fetch('{{ route("admin.notifications.read-all") }}', {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Network response was not ok');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to mark notifications as read: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred: ' + error.message);
                });
            }
        }

        // Delete single notification
        function deleteNotification(notificationId) {
            if (confirm('Are you sure you want to delete this notification?')) {
                fetch(`/admin/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to delete notification');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting notification');
                });
            }
        }

        // Clear all notifications
        function clearAllNotifications() {
            if (confirm('Are you sure you want to delete ALL notifications? This action cannot be undone.')) {
                // You can implement this by calling delete for each notification
                // For now, we'll just reload the page
                alert('Feature will be implemented soon!');
            }
        }
    </script>
</body>
</html>