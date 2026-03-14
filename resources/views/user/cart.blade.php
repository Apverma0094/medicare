<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shopping Cart - Medicine Store</title>
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

        .main-content {
            margin-top: 0.5rem;
        }

        .page-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
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
        .cart-item {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }
        .cart-total {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 15px;
        }
        .quantity-input {
            width: 80px;
        }
        .empty-cart {
            text-align: center;
            padding: 4rem 0;
        }
        .empty-cart img {
            width: 150px;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    {{-- Professional Header --}}
    @include('partials.header')

    <div class="container main-content">
        {{-- Page Header --}}
        <div class="page-header">
            <h1 class="page-title"><i class="fas fa-shopping-cart me-2"></i>Shopping Cart</h1>
            <p class="page-subtitle">Review and manage your selected medicines</p>
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
            @endif        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <h1 class="mb-4">🛒 Your Shopping Cart</h1>

        @if($cartItems->count() > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-md-8">
                @foreach($cartItems as $item)
                <div class="card cart-item">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <h5 class="mb-1">💊 {{ $item->medicine->name }}</h5>
                                <small class="text-muted">{{ $item->medicine->brand }}</small>
                            </div>
                            <div class="col-md-2">
                                <strong class="text-success">₹{{ number_format($item->price, 2) }}</strong>
                            </div>
                            <div class="col-md-3">
                                <form method="POST" action="{{ route('cart.update', $item->id) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group">
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                               min="1" max="{{ $item->medicine->stock }}" 
                                               class="form-control quantity-input text-center"
                                               onchange="this.form.submit()">
                                        <span class="input-group-text">qty</span>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <strong class="text-primary">₹{{ number_format($item->total, 2) }}</strong>
                            </div>
                            <div class="col-md-2">
                                <form method="POST" action="{{ route('cart.remove', $item->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Remove this item from cart?')">
                                        🗑️ Remove
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Cart Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <form method="POST" action="{{ route('cart.clear') }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" 
                                        onclick="return confirm('Clear entire cart?')">
                                    🗑️ Clear Cart
                                </button>
                            </form>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                ← Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-md-4">
                <div class="card cart-total sticky-top">
                    <div class="card-body">
                        <h4 class="card-title text-center">📊 Order Summary</h4>
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Items ({{ $cartItems->sum('quantity') }})</span>
                            <span>₹{{ number_format($total, 2) }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span class="text-success">FREE</span>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (18% GST)</span>
                            <span>₹{{ number_format($total * 0.18, 2) }}</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total Amount</strong>
                            <strong>₹{{ number_format($total * 1.18, 2) }}</strong>
                        </div>
                        
                        <a href="{{ route('user.checkout') }}" class="btn btn-light w-100 btn-lg">
                            💳 Proceed to Checkout
                        </a>
                        
                        <div class="mt-3 text-center">
                            <small>💡 Free shipping on all orders!</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Empty Cart -->
        <div class="empty-cart">
            <div class="mb-4">
                <h2>🛒</h2>
                <h3>Your cart is empty</h3>
                <p class="text-muted">Add some medicines to get started!</p>
            </div>
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                🛒 Start Shopping
            </a>
        </div>
        @endif
    </div>

    <!-- Footer -->
    {{-- Professional Footer --}}
    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.header-scripts')
</body>
</html>