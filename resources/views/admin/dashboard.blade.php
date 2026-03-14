<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Medicine Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f6fa;
            display: flex;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            position: fixed;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #00b4d8;
        }
        .sidebar a {
            display: block;
            color: #ddd;
            padding: 10px 20px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
            color: white;
        }
        .content {
            margin-left: 260px;
            padding: 30px;
            flex: 1;
        }
        .card {
            border: none;
            border-radius: 10px;
        }
        .card h3 {
            font-size: 2rem;
        }
    </style>
</head>
<body>

    {{-- ✅ Sidebar --}}
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="{{ route('medicines.index') }}">💊 Manage Medicines</a>
        <a href="{{ route('admin.orders.index') }}">📦 Manage Orders</a>
        <a href="{{ route('admin.recent.orders') }}">📋 Recent Orders</a>
        <a href="{{ route('admin.recent.medicines') }}">🆕 Recent Medicines</a>
        <a href="{{ route('admin.notifications.index') }}">🔔 Notifications</a>
        <a href="{{ route('admin.users') }}">👥 Manage Users</a>
        <a href="{{ route('profile.edit') }}">👤 Profile</a>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn-danger w-100 mt-3">🚪 Logout</button>
        </form>
    </div>

    {{-- ✅ Main Content --}}
    <div class="content">
        <div class="mb-4">
            <h1>Admin Dashboard</h1>
            
            <!-- Quick Links -->
            <div class="d-flex gap-2 mt-3">
                <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary btn-sm">
                    🔔 Notifications
                </a>
                <a href="{{ route('admin.recent.orders') }}" class="btn btn-outline-primary btn-sm">
                    📋 Recent Orders
                </a>
                <a href="{{ route('admin.recent.medicines') }}" class="btn btn-outline-success btn-sm">
                    🆕 Recent Medicines
                </a>
            </div>
        </div>

        {{-- ✅ Dashboard Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm text-center p-3 bg-primary text-white">
                    <h5>💊 Total Medicines</h5>
                    <h3>{{ $totalMedicines }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center p-3 bg-success text-white">
                    <h5>👥 Total Users</h5>
                    <h3>{{ $totalUsers }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center p-3 bg-info text-white">
                    <h5>📦 Total Orders</h5>
                    <h3>{{ $totalOrders }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm text-center p-3 bg-warning text-white">
                    <h5>💰 Revenue</h5>
                    <h3>₹{{ number_format($totalRevenue, 0) }}</h3>
                </div>
            </div>
        </div>

        {{-- Order Status Cards --}}
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card shadow-sm text-center p-2 bg-light">
                    <small>⏳ Pending</small>
                    <h4 class="text-warning">{{ $pendingOrders }}</h4>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card shadow-sm text-center p-2 bg-light">
                    <small>✅ Confirmed</small>
                    <h4 class="text-success">{{ $confirmedOrders }}</h4>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card shadow-sm text-center p-2 bg-light">
                    <small>🚚 Delivered</small>
                    <h4 class="text-primary">{{ $deliveredOrders }}</h4>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card shadow-sm text-center p-2 bg-light">
                    <small>📅 Today</small>
                    <h4 class="text-info">{{ $todayOrders }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-grid">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">
                        📦 Manage All Orders
                    </a>
                </div>
            </div>
        </div>

        </div>

        {{-- Recent sections moved to separate pages accessible via Quick Links above --}}
    </div>

    <script>
        // Load notification count and latest notifications
        function loadNotifications() {
            // Get notification count
            fetch('{{ route("admin.notifications.count") }}')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationCount');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline';
                    } else {
                        badge.style.display = 'none';
                    }
                });

            // Get latest notifications
            fetch('{{ route("admin.notifications.latest") }}')
                .then(response => response.json())
                .then(data => {
                    const notificationsList = document.getElementById('notificationsList');
                    
                    if (data.length === 0) {
                        notificationsList.innerHTML = '<li class="text-center p-3 text-muted">No new notifications</li>';
                    } else {
                        let html = '';
                        data.forEach(notification => {
                            html += `
                                <li class="notification-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <small class="text-muted">${notification.created_at}</small>
                                            <div class="small">${notification.message}</div>
                                            ${notification.order_number ? `<a href="/admin/orders/${notification.order_id}" class="small text-primary">View Order</a>` : ''}
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary" onclick="markAsRead('${notification.id}')">✓</button>
                                    </div>
                                </li>
                            `;
                        });
                        notificationsList.innerHTML = html;
                    }
                });
        }

        // Mark single notification as read
        function markAsRead(notificationId) {
            fetch(`/admin/notifications/${notificationId}/read`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .then(() => {
                loadNotifications();
            });
        }

        // Mark all notifications as read
        function markAllAsRead() {
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
                    loadNotifications();
                } else {
                    alert('Failed to mark notifications as read: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred: ' + error.message);
            });
        }

        // Load notifications on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            
            // Refresh notifications every 30 seconds
            setInterval(loadNotifications, 30000);
        });
    </script>

</body>
</html>
