<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Medicine Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            padding-top: 20px;
        }
        .order-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            background: white;
        }
        .order-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
            text-align: center;
        }
        .status-timeline {
            position: relative;
            padding: 20px 0;
        }
        .status-step {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }
        .status-step::before {
            content: '';
            position: absolute;
            left: 25px;
            top: 50px;
            width: 2px;
            height: 100%;
            background: #dee2e6;
        }
        .status-step:last-child::before {
            display: none;
        }
        .status-step.active::before {
            background: #28a745;
        }
        .status-step.active .status-icon {
            background: #28a745;
            color: white;
        }
        .status-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 20px;
            z-index: 2;
            position: relative;
        }
        .navbar {
            background: rgba(255,255,255,0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">📊 Medicine Store</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">🏠 Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">🛑️ Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.cart') }}">🛒 Cart</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('user.orders.index') }}">📋 My Orders</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">🚪 Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
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

        <div class="row">
            <div class="col-md-8">
                <!-- Order Header -->
                <div class="card order-card">
                    <div class="order-header">
                        <h3>📦 Order {{ $order->order_number }}</h3>
                        <p class="mb-0">Placed on {{ $order->order_date->format('d M Y, h:i A') }}</p>
                    </div>
                    <div class="card-body">
                        <!-- Order Items -->
                        <h5 class="mb-3">🛒 Ordered Items</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Medicine</th>
                                        <th>Brand</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->orderItems as $item)
                                    <tr>
                                        <td><strong>{{ $item->medicine->name }}</strong></td>
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

                        <!-- Delivery Address -->
                        <div class="mt-4">
                            <h5>🏠 Delivery Address</h5>
                            <div class="border rounded p-3 bg-light">
                                <strong>{{ $order->delivery_address['name'] }}</strong><br>
                                {{ $order->delivery_address['address'] }}<br>
                                {{ $order->delivery_address['city'] }} - {{ $order->delivery_address['pincode'] }}<br>
                                <strong>Phone:</strong> {{ $order->delivery_address['phone'] }}
                            </div>
                        </div>

                        @if($order->notes)
                        <div class="mt-3">
                            <h6>📝 Order Notes</h6>
                            <p class="text-muted">{{ $order->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Order Status -->
                <div class="card order-card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">📋 Order Status</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <span class="badge fs-6 bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'info')) }}">
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
                        @if($order->refund_status !== 'none')
                        <div class="mt-2">
                            <strong>Refund Status:</strong><br>
                            <span class="badge bg-{{ $order->refund_status === 'completed' ? 'success' : ($order->refund_status === 'rejected' ? 'danger' : 'warning') }}">
                                {{ ucfirst($order->refund_status) }}
                            </span>
                            @if($order->refund_amount > 0)
                                <br><small>Amount: ₹{{ number_format($order->refund_amount, 2) }}</small>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card order-card">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0">⚡ Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('user.orders.track', $order->id) }}" class="btn btn-info">
                                📍 Track Order
                            </a>
                            
                            @if(in_array($order->status, ['pending', 'confirmed']))
                                <button type="button" class="btn btn-outline-danger w-100 mb-2" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                    ❌ Cancel Order
                                </button>
                            @endif
                            
                            @if($order->status === 'delivered' && $order->refund_status === 'none')
                                @php
                                    $deliveryDate = $order->delivery_date ?? $order->updated_at;
                                    $canRefund = $deliveryDate->diffInDays(now()) <= 7;
                                @endphp
                                @if($canRefund)
                                    <button type="button" class="btn btn-outline-warning w-100 mb-2" data-bs-toggle="modal" data-bs-target="#refundModal">
                                        💰 Request Refund
                                    </button>
                                    <button type="button" class="btn btn-outline-info w-100 mb-2" data-bs-toggle="modal" data-bs-target="#returnModal">
                                        🔄 Return/Exchange
                                    </button>
                                @else
                                    <small class="text-muted">Refund/Return period (7 days) expired</small>
                                @endif
                            @endif
                            
                            <a href="{{ route('user.orders.index') }}" class="btn btn-outline-secondary">
                                ← Back to Orders
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="card order-card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">📅 Order Timeline</h6>
                    </div>
                    <div class="card-body">
                        <small class="text-muted">
                            <strong>Placed:</strong> {{ $order->order_date->format('d M Y, h:i A') }}<br>
                            @if($order->delivery_date)
                                <strong>Delivered:</strong> {{ $order->delivery_date->format('d M Y, h:i A') }}<br>
                            @endif
                            <strong>Last Updated:</strong> {{ $order->updated_at->format('d M Y, h:i A') }}
                        </small>
                    </div>
                </div>

                <!-- Reorder -->
                @if($order->status === 'delivered')
                <div class="card order-card">
                    <div class="card-body text-center">
                        <h6>🔄 Loved this order?</h6>
                        <p class="small text-muted">Add the same items to your cart again</p>
                        <button class="btn btn-success btn-sm" onclick="reorderItems()">
                            🛒 Reorder
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">❌ Cancel Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('user.orders.cancel', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cancellation_reason" class="form-label">Reason for Cancellation*</label>
                            <select class="form-select" name="cancellation_reason" required>
                                <option value="">Select a reason...</option>
                                <option value="Changed mind">Changed my mind</option>
                                <option value="Found better price">Found better price elsewhere</option>
                                <option value="Delivery too slow">Delivery taking too long</option>
                                <option value="Wrong item">Ordered wrong item</option>
                                <option value="Financial constraints">Financial reasons</option>
                                <option value="Other">Other reason</option>
                            </select>
                        </div>
                        <div class="alert alert-warning">
                            <small>
                                <strong>Note:</strong> 
                                @if($order->payment_status === 'paid' && $order->payment_method !== 'cod')
                                    Your payment will be refunded within 5-7 business days.
                                @else
                                    This action cannot be undone.
                                @endif
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Order</button>
                        <button type="submit" class="btn btn-danger">Cancel Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Refund Request Modal -->
    <div class="modal fade" id="refundModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">💰 Request Refund</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('user.orders.refund', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="refund_amount" class="form-label">Refund Amount*</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" name="refund_amount" 
                                       max="{{ $order->grand_total }}" value="{{ $order->grand_total }}" 
                                       step="0.01" required>
                            </div>
                            <small class="text-muted">Maximum: ₹{{ number_format($order->grand_total, 2) }}</small>
                        </div>
                        <div class="mb-3">
                            <label for="refund_reason" class="form-label">Reason for Refund*</label>
                            <select class="form-select" name="refund_reason" required>
                                <option value="">Select a reason...</option>
                                <option value="Product damaged">Product arrived damaged</option>
                                <option value="Wrong product">Received wrong product</option>
                                <option value="Expired product">Product was expired</option>
                                <option value="Quality issues">Quality not as expected</option>
                                <option value="Not satisfied">Not satisfied with purchase</option>
                                <option value="Other">Other reason</option>
                            </select>
                        </div>
                        <div class="alert alert-info">
                            <small>
                                <strong>Refund Policy:</strong> 
                                Refunds are processed within 5-7 business days after approval.
                                You may be required to return the product.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Request Refund</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Return/Exchange Modal -->
    <div class="modal fade" id="returnModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">🔄 Return/Exchange</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('user.orders.return', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">What would you like to do?*</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="return_type" value="return" id="return" required>
                                <label class="form-check-label" for="return">
                                    <strong>Return</strong> - Get full refund
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="return_type" value="exchange" id="exchange" required>
                                <label class="form-check-label" for="exchange">
                                    <strong>Exchange</strong> - Replace with same/different product
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="return_reason" class="form-label">Reason*</label>
                            <select class="form-select" name="return_reason" required>
                                <option value="">Select a reason...</option>
                                <option value="Size/fit issues">Size or fit issues</option>
                                <option value="Product damaged">Product damaged/defective</option>
                                <option value="Wrong product">Received wrong product</option>
                                <option value="Quality issues">Quality not as expected</option>
                                <option value="Changed mind">Changed my mind</option>
                                <option value="Other">Other reason</option>
                            </select>
                        </div>
                        <div class="alert alert-info">
                            <small>
                                <strong>Return/Exchange Policy:</strong> 
                                Free return/exchange within 7 days. Product should be in original condition.
                                Pickup will be arranged from your address.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function reorderItems() {
            if(confirm('Add all items from this order to your cart?')) {
                // You can implement reorder functionality here
                alert('Feature will be implemented soon!');
            }
        }
    </script>
</body>
</html>