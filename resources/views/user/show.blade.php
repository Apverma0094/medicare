<!DOCTYPE html>
<html>
<head>
    <title>{{ $medicine->name }} - Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-custom {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        .medicine-info {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 25px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .price {
            color: green;
            font-size: 24px;
            font-weight: bold;
        }
        .content-wrapper {
            padding: 2rem 0;
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
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="medicine-info">
                        <h1 class="mb-4">💊 {{ $medicine->name }}</h1>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>🏷️ Brand:</strong> {{ $medicine->brand }}</p>
                                <p><strong>📝 Description:</strong> {{ $medicine->description }}</p>
                                <p><strong>⚕️ Type:</strong> {{ $medicine->type }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>📦 Stock:</strong> {{ $medicine->stock }} units</p>
                                <p><strong>📅 Manufactured:</strong> {{ $medicine->manufacture_date }}</p>
                                <p><strong>⏰ Expires:</strong> {{ $medicine->expiry_date }}</p>
                            </div>
                        </div>
                        
                        <div class="price mb-4">💰 ₹{{ $medicine->price }}</div>
                        
                        <div class="d-flex gap-2 align-items-center">
                            <a href="{{ route('home') }}" class="btn btn-secondary">
                                ← Back to Shop
                            </a>
                            
                            @auth
                            @if($medicine->stock > 0)
                            <form method="POST" action="{{ route('cart.add', $medicine->id) }}" class="d-flex gap-2 align-items-center">
                                @csrf
                                <div>
                                    <label for="quantity" class="form-label mb-0 me-2">Quantity:</label>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" 
                                           max="{{ $medicine->stock }}" class="form-control" style="width: 80px;">
                                </div>
                                <button type="submit" class="btn btn-success btn-lg">
                                    🛒 Add to Cart
                                </button>
                            </form>
                            @else
                            <button class="btn btn-secondary btn-lg" disabled>
                                ❌ Out of Stock
                            </button>
                            @endif
                            @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                Login to Buy
                            </a>
                            @endauth
                        </div>
                        
                        @if($medicine->stock <= 5 && $medicine->stock > 0)
                        <div class="alert alert-warning">
                            <strong>⚠️ Limited Stock:</strong> Only {{ $medicine->stock }} units left!
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
        }
        .dashboard-btn {
            position: fixed;
            top: 15px;
            right: 15px;
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>
<body>

<a href="{{ route('admin.dashboard') }}" class="dashboard-btn">🏠 Go to Dashboard</a>

<div class="medicine-info">
    <h2>{{ $medicine->name }}</h2>
    <p><strong>Brand:</strong> {{ $medicine->brand }}</p>
    <p><strong>Description:</strong> {{ $medicine->description }}</p>
    <p><strong>Stock:</strong> {{ $medicine->stock }}</p>
    <p class="price">Price: ₹{{ $medicine->price }}</p>

    <a href="{{ route('home') }}" class="btn-back">⬅ Back to Shop</a>
    <a href="#" class="btn-buy">🛒 Add to Cart</a>
</div>

</body>
</html>
