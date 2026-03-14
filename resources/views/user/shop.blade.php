<!DOCTYPE html>
<html>
<head>
    <title>Medicines Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial;
            margin: 0;
            background-color: #f8f9fa;
        }
        .navbar-custom {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        .medicine-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 10px;
            display: inline-block;
            width: 220px;
            vertical-align: top;
            text-align: center;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            background: white;
        }
        .medicine-card h3 {
            color: #2c3e50;
        }
        .price {
            color: green;
            font-weight: bold;
        }
        .view-btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 10px;
        }
        .view-btn:hover {
            background-color: #2980b9;
            color: white;
        }
        .content-wrapper {
            padding: 2rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">🏥 Medicine Store</a>
            <div class="navbar-nav ms-auto d-flex align-items-center">
                @auth
                <a href="{{ route('user.cart') }}" class="btn btn-outline-success btn-sm me-2 position-relative">
                    🛒 Cart
                    @if(auth()->user()->cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ auth()->user()->cartCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('user.orders.index') }}" class="btn btn-outline-info btn-sm me-2">
                    📋 My Orders
                </a>
                @endauth
                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm me-2">
                    🏠 Home
                </a>
                @auth
                <span class="me-3">👋 {{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        🚪 Logout
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
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

            <h1 class="mb-4">🛒 Medicines Available</h1>
            
            <div class="text-center">
                @foreach($medicines as $med)
                <div class="medicine-card">
                    <h3>{{ $med->name }}</h3>
                    <p><strong>Brand:</strong> {{ $med->brand }}</p>
                    <p class="price">₹{{ $med->price }}</p>
                    <p><small>Stock: {{ $med->stock }} units</small></p>
                    
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('user.show', $med->id) }}" class="view-btn">View Details</a>
                        
                        @auth
                        @if($med->stock > 0)
                        <form method="POST" action="{{ route('cart.add', $med->id) }}" class="d-inline">
                            @csrf
                            <div class="mb-2">
                                <input type="number" name="quantity" value="1" min="1" max="{{ $med->stock }}" 
                                       class="form-control form-control-sm" style="width: 70px; margin: 0 auto;">
                            </div>
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                🛒 Add to Cart
                            </button>
                        </form>
                        @else
                        <button class="btn btn-secondary btn-sm w-100" disabled>
                            ❌ Out of Stock
                        </button>
                        @endif
                        @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                            Login to Buy
                        </a>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
