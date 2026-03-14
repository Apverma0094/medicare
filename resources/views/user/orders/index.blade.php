<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Orders - Medicine Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @include('partials.header-styles')
        
        :root {
            --primary-color: #16a085;
            --secondary-color: #27ae60;
            --accent-color: #3498db;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }
        .container {
            padding-top: 10px;
        }
        .order-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: all 0.3s ease;
            background: white !important;
        }
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .order-header {
            background: linear-gradient(135deg, #16a085, #27ae60);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 15px 20px;
        }
        .status-badge {
            font-size: 0.9rem;
            padding: 5px 12px;
            border-radius: 20px;
        }
        .btn-custom {
            border-radius: 25px;
            font-weight: 500;
            padding: 8px 20px;
        }

        .page-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1rem;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            text-align: center;
        }

        .page-title {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .main-content {
            margin-top: 0;
        }
    </style>
</head>
<body>
    {{-- Professional Header --}}
    @include('partials.header')

    <div class="container main-content">
        {{-- Page Header --}}
        <div class="page-header">
            <h1 class="page-title"><i class="fas fa-box me-2"></i>My Orders</h1>
            <p class="page-subtitle">Track and manage your medicine orders</p>
        </div>
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
            <div class="col-12">
                <div class="card order-card">
                    <div class="order-header">
                        <h2 class="mb-0">📋 My Orders</h2>
                        <small>Track all your medicine orders here</small>
                    </div>
                    <div class="card-body">
                        @if($orders->count())
                            @foreach($orders as $order)
                                <div class="order-card bg-light">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <h6 class="mb-1"><strong>{{ $order->order_number }}</strong></h6>
                                                <small class="text-muted">{{ $order->order_date->format('d M Y, h:i A') }}</small>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <span class="badge status-badge" style="background: linear-gradient(135deg, var(--{{ $order->status === 'pending' ? 'warning' : ($order->status === 'delivered' ? 'secondary' : ($order->status === 'cancelled' ? 'danger' : 'primary')) }}-color), var(--{{ $order->status === 'pending' ? 'warning' : ($order->status === 'delivered' ? 'secondary' : ($order->status === 'cancelled' ? 'danger' : 'primary')) }}-color)); color: white;">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <strong>₹{{ number_format($order->grand_total, 2) }}</strong>
                                                <br><small class="text-muted">{{ count($order->orderItems) }} items</small>
                                            </div>
                                            
                                            <div class="col-md-2">
                                                <span class="badge" style="background: linear-gradient(135deg, var(--{{ $order->payment_status === 'paid' ? 'secondary' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}-color), var(--{{ $order->payment_status === 'paid' ? 'secondary' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}-color)); color: white;">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                                @if($order->refund_status !== 'none')
                                                    <br><span class="badge mt-1" style="background: linear-gradient(135deg, var(--accent-color), var(--accent-color)); color: white;">{{ ucfirst($order->refund_status) }}</span>
                                                @endif
                                            </div>
                                            
                                            <div class="col-md-3 text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary btn-custom" style="border-color: var(--primary-color); color: var(--primary-color);">
                                                        👁️ View
                                                    </a>
                                                    <a href="{{ route('user.orders.track', $order->id) }}" class="btn btn-sm btn-outline-success btn-custom" style="border-color: var(--secondary-color); color: var(--secondary-color);">
                                                        📍 Track
                                                    </a>
                                                    @if($order->status === 'pending')
                                                        <form action="{{ route('user.orders.cancel', $order->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-custom" 
                                                                    onclick="return confirm('Are you sure you want to cancel this order?')">
                                                                ❌ Cancel
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Order Items Preview -->
                                        <div class="mt-3 pt-3 border-top">
                                            <small class="text-muted">Items: </small>
                                            @foreach($order->orderItems->take(3) as $item)
                                                <span class="badge bg-light text-dark me-1">{{ $item->medicine->name }} ({{ $item->quantity }})</span>
                                            @endforeach
                                            @if($order->orderItems->count() > 3)
                                                <span class="badge bg-secondary">+{{ $order->orderItems->count() - 3 }} more</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $orders->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div style="background: white; border-radius: 20px; padding: 3rem; box-shadow: 0 8px 25px rgba(0,0,0,0.1);">
                                    <i class="fas fa-box-open" style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1rem;"></i>
                                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">📦 No Orders Yet</h3>
                                    <p class="text-muted mb-3">You haven't placed any orders yet. Start shopping for medicines!</p>
                                    <a href="{{ route('home') }}" class="btn btn-custom" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none;">
                                        🛍️ Start Shopping
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Statistics -->
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="card text-center bg-info text-white order-card">
                            <div class="card-body">
                                <h5>🔄 Active Orders</h5>
                                <h3>{{ auth()->user()->orders()->where('status', '!=', 'delivered')->count() }}</h3>
                                <small>In progress orders</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-warning text-white order-card">
                            <div class="card-body">
                                <h5>⏳ Pending</h5>
                                <h3>{{ auth()->user()->orders()->where('status', 'pending')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-success text-white order-card">
                            <div class="card-body">
                                <h5>✅ Delivered</h5>
                                <h3>{{ auth()->user()->orders()->where('status', 'delivered')->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center bg-primary text-white order-card">
                            <div class="card-body">
                                <h5>📦 Total Orders</h5>
                                <h3>{{ auth()->user()->orders()->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Total Spent Section -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card text-center bg-success text-white">
                            <div class="card-body">
                                <h5>💰 Total Spent on Health</h5>
                                <h3>₹{{ number_format(auth()->user()->orders()->where('status', '!=', 'cancelled')->sum('grand_total'), 2) }}</h3>
                                <small>Lifetime investment in health & wellness</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Professional Footer --}}
    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.header-scripts')
</body>
</html>