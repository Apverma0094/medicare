<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Success - Medicine Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .success-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .order-number {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">🏥 Medicine Store</a>
            <div class="navbar-nav ms-auto">
                <a href="{{ route('home') }}" class="btn btn-outline-primary">🏠 Home</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card success-card">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="text-success mb-3">🎉 Order Placed Successfully!</h2>
                        <p class="text-muted mb-4">Thank you for your order. We will process it soon.</p>
                        
                        <div class="order-number">
                            <h4 class="mb-0">Order Number: {{ $order->order_number }}</h4>
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="card success-card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">📦 Order Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>📋 Order Information</h6>
                                <p><strong>Order Date:</strong> {{ $order->order_date->format('d M Y, h:i A') }}</p>
                                <p><strong>Status:</strong> <span class="badge bg-warning">{{ ucfirst($order->status) }}</span></p>
                                <p><strong>Payment Method:</strong> {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : 'Online Payment' }}</p>
                                <p><strong>Payment Status:</strong> <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span></p>
                            </div>
                            <div class="col-md-6">
                                <h6>🚚 Delivery Address</h6>
                                <p><strong>{{ $order->delivery_address['name'] }}</strong></p>
                                <p>{{ $order->delivery_address['phone'] }}</p>
                                <p>{{ $order->delivery_address['address'] }}</p>
                                <p>{{ $order->delivery_address['city'] }} - {{ $order->delivery_address['pincode'] }}</p>
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

                <!-- Ordered Items -->
                <div class="card success-card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">🛒 Ordered Items</h5>
                    </div>
                    <div class="card-body">
                        @foreach($order->orderItems as $item)
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <h6 class="mb-1">💊 {{ $item->medicine->name }}</h6>
                                <small class="text-muted">{{ $item->medicine->brand }}</small>
                            </div>
                            <div class="text-end">
                                <div>{{ $item->quantity }} x ₹{{ number_format($item->price, 2) }}</div>
                                <div class="fw-bold">₹{{ number_format($item->total, 2) }}</div>
                            </div>
                        </div>
                        @endforeach
                        
                        <div class="mt-3 pt-3 border-top">
                            <div class="row">
                                <div class="col-md-6 offset-md-6">
                                    <div class="d-flex justify-content-between">
                                        <span>Subtotal:</span>
                                        <span>₹{{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Tax (18% GST):</span>
                                        <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between fw-bold text-primary">
                                        <span>Grand Total:</span>
                                        <span>₹{{ number_format($order->grand_total, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <a href="{{ route('user.orders.index') }}" class="btn btn-success btn-lg me-3">
                        📋 View My Orders
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-primary btn-lg me-3">
                        🏠 Go to Home
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg">
                        🛒 Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-5 py-4 bg-light text-center">
        <div class="container">
            <p class="text-muted mb-0">© 2025 Medicine Store - Your Health, Our Priority</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>