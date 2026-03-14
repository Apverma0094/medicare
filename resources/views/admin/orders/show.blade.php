<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Admin Panel</title>
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
        .order-number {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
        }
        .status-badge {
            font-size: 1.1rem;
            padding: 0.5rem 1rem;
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
            <h1>📦 Order Details</h1>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                ← Back to Orders
            </a>
        </div>

        <div class="row">
            <!-- Order Information -->
            <div class="col-md-8">
                <!-- Order Number -->
                <div class="order-number mb-4">
                    <h3 class="mb-0">Order: {{ $order->order_number }}</h3>
                    <small>Placed on {{ $order->order_date->format('d M Y, h:i A') }}</small>
                </div>

                <!-- Customer Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">👤 Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ $order->user->name }}</p>
                                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                                <p><strong>Phone:</strong> {{ $order->delivery_address['phone'] }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Delivery Address:</strong></p>
                                <address>
                                    {{ $order->delivery_address['name'] }}<br>
                                    {{ $order->delivery_address['address'] }}<br>
                                    {{ $order->delivery_address['city'] }} - {{ $order->delivery_address['pincode'] }}
                                </address>
                            </div>
                        </div>
                        @if($order->notes)
                        <div class="mt-3">
                            <strong>Order Notes:</strong>
                            <p class="text-muted">{{ $order->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Ordered Items -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">🛒 Ordered Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Medicine</th>
                                        <th>Brand</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->medicine->name }}</strong>
                                        </td>
                                        <td>{{ $item->medicine->brand }}</td>
                                        <td>₹{{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>₹{{ number_format($item->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="4">Subtotal:</th>
                                        <th>₹{{ number_format($order->total_amount, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="4">Tax (18% GST):</th>
                                        <th>₹{{ number_format($order->tax_amount, 2) }}</th>
                                    </tr>
                                    <tr class="table-success">
                                        <th colspan="4">Grand Total:</th>
                                        <th>₹{{ number_format($order->grand_total, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Status & Actions -->
            <div class="col-md-4">
                <!-- Current Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">📋 Order Status</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span class="badge status-badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'delivered' ? 'success' : 'info') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>Payment Status:</strong><br>
                            <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        <div>
                            <strong>Payment Method:</strong><br>
                            {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment' }}
                        </div>
                    </div>
                </div>

                <!-- Update Status -->
                @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">✏️ Update Status</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.orders.update.status', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
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
                            <button type="submit" class="btn btn-primary w-100">Update Status</button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Update Payment Status -->
                @if($order->payment_status !== 'paid')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">💳 Update Payment</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.orders.update.payment', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label">Payment Status</label>
                                <select name="payment_status" class="form-select" required>
                                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>✅ Paid</option>
                                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>❌ Failed</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Update Payment</button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Refund Management -->
                @if($order->refund_status !== 'none')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">💰 Refund Management</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Current Status:</strong>
                            <span class="badge bg-{{ $order->refund_status === 'completed' ? 'success' : ($order->refund_status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($order->refund_status) }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>Refund Amount:</strong> ₹{{ number_format($order->refund_amount, 2) }}
                        </div>
                        @if($order->refund_requested_at)
                        <div class="mb-3">
                            <strong>Requested:</strong> {{ $order->refund_requested_at->format('d M Y, h:i A') }}
                        </div>
                        @endif
                        @if($order->refund_notes)
                        <div class="mb-3">
                            <strong>Customer Notes:</strong>
                            <p class="text-muted">{{ $order->refund_notes }}</p>
                        </div>
                        @endif
                        
                        @if($order->refund_status === 'requested')
                        <form action="{{ route('admin.orders.update.refund', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label">Update Refund Status</label>
                                <select name="refund_status" class="form-select" required>
                                    <option value="approved">✅ Approve</option>
                                    <option value="processing">🔄 Processing</option>
                                    <option value="completed">💚 Completed</option>
                                    <option value="rejected">❌ Reject</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Admin Notes (Optional)</label>
                                <textarea name="refund_notes" class="form-control" rows="3" placeholder="Add notes about refund processing..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Refund Reference (Optional)</label>
                                <input type="text" name="refund_reference" class="form-control" placeholder="Transaction ID, Reference number, etc.">
                            </div>
                            <button type="submit" class="btn btn-warning w-100">Update Refund Status</button>
                        </form>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Delivery Information -->
                @if($order->delivery_date)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">🚚 Delivery Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Delivered on:</strong><br>{{ $order->delivery_date->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
                @endif

                <!-- Danger Zone -->
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0">⚠️ Danger Zone</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Delete this order permanently. This action cannot be undone.</p>
                        <button type="button" class="btn btn-outline-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            🗑️ Delete Order
                        </button>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Deletion</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete order <strong>{{ $order->order_number }}</strong>?</p>
                                <p class="text-danger">This action will also restore medicine stock and cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete Order</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>