<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recent Orders - Admin Panel</title>
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
        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255,255,255,0.2);
            border-radius: 5px;
            padding-left: 10px;
            transition: all 0.3s ease;
        }
        .order-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }
        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
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
        <a href="{{ route('admin.recent.orders') }}" class="active">📋 Recent Orders</a>
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
            <div>
                <h1>📋 Recent Orders</h1>
                <p class="text-muted mb-0">Orders from the last 30 days</p>
            </div>
            <div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">📦 All Orders</a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">← Back to Dashboard</a>
            </div>
        </div>

        <!-- Order Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <h3>{{ $todayOrders }}</h3>
                    <p class="mb-0">📅 Today's Orders</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3>{{ $thisWeekOrders }}</h3>
                    <p class="mb-0">📊 This Week</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3>{{ $thisMonthOrders }}</h3>
                    <p class="mb-0">📈 This Month</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3>{{ $recentOrders->total() }}</h3>
                    <p class="mb-0">🎯 Total Orders</p>
                </div>
            </div>
        </div>

        <!-- Recent Orders List -->
        <div class="row">
            <div class="col-12">
                @if($recentOrders->count())
                    @foreach($recentOrders as $order)
                        <div class="card order-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        <h6 class="mb-1"><strong>{{ $order->order_number }}</strong></h6>
                                        <small class="text-muted">{{ $order->created_at->format('d M Y') }}</small>
                                        <br><small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <strong>{{ $order->user->name }}</strong>
                                        <br><small class="text-muted">{{ $order->user->email }}</small>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <span class="badge fs-6 bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'info')) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        <br>
                                        <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <strong>₹{{ number_format($order->grand_total, 2) }}</strong>
                                        <br><small class="text-muted">{{ count($order->orderItems) }} items</small>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <small class="text-muted">{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</small>
                                        @if($order->refund_status !== 'none')
                                            <br><span class="badge bg-info">{{ ucfirst($order->refund_status) }}</span>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-2 text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                👁️ View
                                            </a>
                                            @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#statusModal{{ $order->id }}">
                                                    ✏️ Update
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Order Items Preview -->
                                <div class="mt-3 pt-3 border-top">
                                    <small class="text-muted"><strong>Items:</strong></small>
                                    @foreach($order->orderItems->take(3) as $item)
                                        <span class="badge bg-light text-dark me-1">{{ $item->medicine->name }} ({{ $item->quantity }})</span>
                                    @endforeach
                                    @if($order->orderItems->count() > 3)
                                        <span class="badge bg-secondary">+{{ $order->orderItems->count() - 3 }} more</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Quick Status Update Modal -->
                        <div class="modal fade" id="statusModal{{ $order->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title">Update Status</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('admin.orders.update.status', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="modal-body">
                                            <select name="status" class="form-select" required>
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>✅ Confirmed</option>
                                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>🔄 Processing</option>
                                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>📦 Delivered</option>
                                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $recentOrders->links() }}
                    </div>
                @else
                    <div class="card order-card">
                        <div class="card-body text-center py-5">
                            <h3 class="text-muted">📋 No Orders Found</h3>
                            <p class="text-muted">No orders have been placed yet.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>