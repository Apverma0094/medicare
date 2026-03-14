<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Admin Panel</title>
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
        .order-status-pending {
            background-color: #fff3cd;
            border-color: #ffeaa7;
        }
        .order-status-confirmed {
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }
        .order-status-delivered {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .order-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="{{ route('admin.dashboard') }}">🏠 Dashboard</a>
        <a href="{{ route('medicines.index') }}">💊 Manage Medicines</a>
        <a href="{{ route('admin.orders.index') }}" style="background: rgba(255,255,255,0.2);">📦 Manage Orders</a>
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

    {{-- Main Content --}}
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>📦 Orders Management</h1>
            
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.orders.search') }}" class="d-flex">
                <input type="text" name="query" class="form-control me-2" placeholder="Search orders..." value="{{ request('query') }}">
                <button type="submit" class="btn btn-outline-primary">🔍 Search</button>
            </form>
        </div>

        <!-- Order Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="card text-center border-warning">
                    <div class="card-body">
                        <h5 class="card-title text-warning">{{ $pendingOrders }}</h5>
                        <p class="card-text small">⏳ Pending Orders</p>
                        <a href="{{ route('admin.orders.by.status', 'pending') }}" class="btn btn-outline-warning btn-sm">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center border-info">
                    <div class="card-body">
                        <h5 class="card-title text-info">{{ $confirmedOrders }}</h5>
                        <p class="card-text small">✅ Confirmed</p>
                        <a href="{{ route('admin.orders.by.status', 'confirmed') }}" class="btn btn-outline-info btn-sm">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <h5 class="card-title text-success">{{ $deliveredOrders }}</h5>
                        <p class="card-text small">🚚 Delivered</p>
                        <a href="{{ route('admin.orders.by.status', 'delivered') }}" class="btn btn-outline-success btn-sm">View</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-primary">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ $totalOrders }}</h5>
                        <p class="card-text small">📦 Total Orders</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-dark">
                    <div class="card-body">
                        <h5 class="card-title text-dark">₹{{ number_format($totalRevenue, 0) }}</h5>
                        <p class="card-text small">💰 Total Revenue</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Orders</h5>
                <small>Total: {{ $orders->total() }} orders</small>
            </div>
            <div class="card-body">
                @if($orders->count())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr class="order-status-{{ $order->status }}">
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <div>{{ $order->user->name }}</div>
                                        <small class="text-muted">{{ $order->user->email }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $order->order_date->format('d M Y') }}</div>
                                        <small class="text-muted">{{ $order->order_date->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $order->orderItems->count() }} items</span>
                                    </td>
                                    <td>
                                        <strong>₹{{ number_format($order->grand_total, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Online' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'delivered' ? 'success' : 'info') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical btn-group-sm">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                                👁️ View
                                            </a>
                                            @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                                            <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal{{ $order->id }}">
                                                ✏️ Update
                                            </button>
                                            @endif
                                        </div>

                                        <!-- Status Update Modal -->
                                        <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-sm">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Update Status</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.orders.update.status', $order->id) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Order Status</label>
                                                                <select name="status" class="form-select" required>
                                                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                                                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>✅ Confirmed</option>
                                                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>🔄 Processing</option>
                                                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                                                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
                                                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Update Status</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <h5 class="text-muted">No orders found</h5>
                        <p class="text-muted">Orders will appear here when customers place orders.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>