<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Store - Your Health, Our Priority</title>
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
        }

        /* Header Styles */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 20px rgba(22, 160, 133, 0.2);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-1px);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        /* Search Bar */
        .search-container {
            position: relative;
            max-width: 600px;
            margin: 0 auto 3rem;
        }

        .search-input {
            border: none;
            border-radius: 50px;
            padding: 15px 60px 15px 25px;
            font-size: 1rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--accent-color);
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            color: white;
        }

        /* Category Pills */
        .category-pills {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .category-pill {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.3);
        }

        .category-pill:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* Product Grid */
        .products-section {
            padding: 80px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            position: relative;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 2px;
        }

        .product-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #f1f1f1;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(22, 160, 133, 0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
        }

        .product-image i {
            font-size: 4rem;
            color: var(--primary-color);
            opacity: 0.7;
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--warning-color);
            color: white;
            padding: 4px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .stock-badge {
            background: var(--secondary-color);
        }

        .stock-badge.low {
            background: var(--danger-color);
        }

        .product-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .product-brand {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .product-details {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-bottom: 15px;
        }

        .btn-add-cart {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(22, 160, 133, 0.4);
            color: white;
        }

        .btn-add-cart:disabled {
            background: #95a5a6;
            cursor: not-allowed;
        }

        /* Features Section */
        .features-section {
            background: white;
            padding: 60px 0;
        }

        .feature-item {
            text-align: center;
            padding: 30px 20px;
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }

        /* Sidebar */
        .sidebar {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .sidebar h5 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f1f1;
        }

        /* Dropdown Filters */
        .dropdown-menu {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .dropdown-menu .dropdown-item {
            transition: all 0.3s ease;
            border-radius: 5px;
            margin: 2px 5px;
        }

        .dropdown-menu .dropdown-item:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .dropdown-menu .dropdown-item:hover i {
            color: white;
        }

        /* Filter Tags */
        .filter-tag {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .filter-tag .remove-filter {
            cursor: pointer;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .filter-tag .remove-filter:hover {
            opacity: 1;
        }

        /* Quantity Selector */
        .quantity-selector {
            text-align: center;
        }

        .quantity-selector .btn {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .quantity-selector .btn:hover {
            background: var(--primary-color);
            color: white;
        }

        .quantity-selector input {
            border-color: var(--primary-color);
            font-weight: 600;
        }

        .quantity-selector input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(22, 160, 133, 0.25);
        }

        /* Product Card Enhancements */
        .product-card {
            position: relative;
            overflow: hidden;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .product-card:hover::before {
            left: 100%;
        }

        .btn-outline-primary:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
        }

        /* Loading States */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }

        .loading-overlay .spinner-border {
            color: var(--primary-color);
        }

        /* Filter Item Enhancements */
        .filter-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-item:hover {
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .filter-item input {
            margin-right: 10px;
        }

        /* Sidebar Panel */
        .user-sidebar {
            position: fixed;
            top: 0;
            right: -350px;
            width: 350px;
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: -5px 0 25px rgba(0,0,0,0.1);
            overflow-y: auto;
        }

        .user-sidebar.active {
            right: 0;
        }

        .sidebar-header {
            padding: 30px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            text-align: center;
        }

        .sidebar-avatar {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 2rem;
        }

        .sidebar-menu {
            padding: 0;
        }

        .sidebar-menu li {
            list-style: none;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            border-left-color: white;
            color: white;
        }

        .sidebar-menu i {
            width: 20px;
            margin-right: 15px;
            text-align: center;
        }

        .sidebar-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1001;
            background: var(--primary-color);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 20px rgba(22, 160, 133, 0.3);
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(22, 160, 133, 0.4);
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .cart-badge {
            background: var(--danger-color);
            color: white;
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 12px;
            font-weight: 600;
            margin-left: auto;
        }

        .logout-btn {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            color: inherit;
            font-size: inherit;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .user-sidebar {
                width: 300px;
            }
            
            .user-info {
                display: none !important;
            }
            
            .user-dropdown-btn {
                padding: 8px 12px !important;
            }
        }

        /* Professional User Dropdown Styles */
        .user-dropdown .dropdown-toggle::after {
            display: none;
        }

        .user-dropdown-btn {
            background: rgba(255,255,255,0.95);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 12px;
            padding: 8px 16px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .user-dropdown-btn:hover {
            background: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        .user-info {
            text-align: left;
            line-height: 1.2;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: #2c3e50;
        }

        .user-email {
            font-size: 11px;
            color: #7f8c8d;
        }

        .dropdown-arrow {
            font-size: 10px;
            transition: transform 0.3s ease;
            color: #7f8c8d;
        }

        .user-dropdown.show .dropdown-arrow {
            transform: rotate(180deg);
        }

        /* Enhanced User Menu */
        .user-menu {
            border: none;
            border-radius: 15px;
            padding: 10px 0;
            min-width: 320px;
            margin-top: 10px;
            background: white;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15) !important;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .user-menu .dropdown-header {
            padding: 15px 20px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            margin: 5px 10px 10px;
        }

        .header-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 2px 10px;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transition: width 0.3s ease;
            z-index: 1;
        }

        .menu-item:hover::before {
            width: 4px;
        }

        .menu-item:hover {
            background: linear-gradient(135deg, rgba(22, 160, 133, 0.05), rgba(39, 174, 96, 0.05));
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .menu-item-danger:hover {
            background: rgba(231, 76, 60, 0.05);
            color: var(--danger-color);
        }

        .menu-item-danger:hover::before {
            background: var(--danger-color);
        }

        .menu-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 15px;
            font-size: 16px;
            flex-shrink: 0;
        }

        .menu-content {
            flex-grow: 1;
        }

        .menu-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .menu-subtitle {
            font-size: 12px;
            color: #7f8c8d;
            opacity: 0.8;
        }

        .menu-arrow {
            font-size: 12px;
            color: #bdc3c7;
            transition: all 0.3s ease;
            margin-left: 10px;
        }

        .menu-item:hover .menu-arrow {
            color: var(--primary-color);
            transform: translateX(3px);
        }

        .menu-item-danger:hover .menu-arrow {
            color: var(--danger-color);
        }

        .menu-badge {
            background: var(--danger-color);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-left: auto;
        }

        .cart-btn {
            transition: all 0.3s ease;
            border-radius: 10px;
        }

        .cart-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(255,255,255,0.3);
        }

        .cart-badge-header {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: translate(-50%, -50%) scale(1);
            }
            50% {
                transform: translate(-50%, -50%) scale(1.1);
            }
            100% {
                transform: translate(-50%, -50%) scale(1);
            }
        }

        /* Loading Animation */
        .loading {
            display: none;
            text-align: center;
            padding: 40px;
        }

        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>

    {{-- Professional Header --}}
    @include('partials.header')

    {{-- Hero Section --}}
    <section class="hero-section">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">Welcome to MediStore</h1>
                <p class="hero-subtitle">Your Health, Our Priority - Quality medicines and healthcare products delivered to your door</p>
                
                {{-- Search Bar --}}
                <div class="search-container">
                    <form action="{{ route('home') }}" method="GET">
                        <div class="position-relative">
                            <input type="text" 
                                   name="search" 
                                   class="form-control search-input" 
                                   placeholder="Search for medicines, brands, or health products..."
                                   value="{{ request('search') }}">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Category Pills --}}
                <div class="category-pills">
                    <a href="{{ route('home', ['category' => 'tablet']) }}" class="category-pill">
                        <i class="fas fa-pills me-1"></i>Tablets
                    </a>
                    <a href="{{ route('home', ['category' => 'syrup']) }}" class="category-pill">
                        <i class="fas fa-flask me-1"></i>Syrups
                    </a>
                    <a href="{{ route('home', ['category' => 'injection']) }}" class="category-pill">
                        <i class="fas fa-syringe me-1"></i>Injections
                    </a>
                    <a href="{{ route('home', ['category' => 'capsule']) }}" class="category-pill">
                        <i class="fas fa-capsules me-1"></i>Capsules
                    </a>
                    <a href="{{ route('home', ['category' => 'ointment']) }}" class="category-pill">
                        <i class="fas fa-pump-medical me-1"></i>Ointments
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="products-section" id="medicines">
        <div class="container">
            <div class="row">
                {{-- Sidebar Filters --}}
                <div class="col-lg-3">
                    <div class="sidebar">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filters</h5>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearAllFilters()">
                                <i class="fas fa-times me-1"></i>Clear
                            </button>
                        </div>
                        
                        {{-- Price Range Dropdown --}}
                        <div class="mb-4">
                            <div class="dropdown">
                                <button class="btn btn-outline-primary w-100 dropdown-toggle text-start d-flex justify-content-between align-items-center" 
                                        type="button" id="priceDropdown" data-bs-toggle="dropdown">
                                    <span><i class="fas fa-rupee-sign me-2"></i>Price Range</span>
                                </button>
                                <ul class="dropdown-menu w-100" aria-labelledby="priceDropdown">
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="price-filter me-2" value="0-100" onchange="applyFilters()">
                                            Under ₹100
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="price-filter me-2" value="100-500" onchange="applyFilters()">
                                            ₹100 - ₹500
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="price-filter me-2" value="500-1000" onchange="applyFilters()">
                                            ₹500 - ₹1000
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="price-filter me-2" value="1000-999999" onchange="applyFilters()">
                                            Above ₹1000
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Categories Dropdown --}}
                        <div class="mb-4">
                            <div class="dropdown">
                                <button class="btn btn-outline-success w-100 dropdown-toggle text-start d-flex justify-content-between align-items-center" 
                                        type="button" id="categoryDropdown" data-bs-toggle="dropdown">
                                    <span><i class="fas fa-tags me-2"></i>Categories</span>
                                </button>
                                <ul class="dropdown-menu w-100" aria-labelledby="categoryDropdown">
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="category-filter me-2" value="tablet" onchange="applyFilters()">
                                            <i class="fas fa-pills me-2"></i>Tablets
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="category-filter me-2" value="syrup" onchange="applyFilters()">
                                            <i class="fas fa-flask me-2"></i>Syrups
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="category-filter me-2" value="injection" onchange="applyFilters()">
                                            <i class="fas fa-syringe me-2"></i>Injections
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="category-filter me-2" value="capsule" onchange="applyFilters()">
                                            <i class="fas fa-capsules me-2"></i>Capsules
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="category-filter me-2" value="ointment" onchange="applyFilters()">
                                            <i class="fas fa-pump-medical me-2"></i>Ointments
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Brands Dropdown --}}
                        <div class="mb-4">
                            <div class="dropdown">
                                <button class="btn btn-outline-info w-100 dropdown-toggle text-start d-flex justify-content-between align-items-center" 
                                        type="button" id="brandDropdown" data-bs-toggle="dropdown">
                                    <span><i class="fas fa-industry me-2"></i>Brands</span>
                                </button>
                                <ul class="dropdown-menu w-100" aria-labelledby="brandDropdown">
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="brand-filter me-2" value="Cipla" onchange="applyFilters()">
                                            Cipla
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="brand-filter me-2" value="Sun Pharma" onchange="applyFilters()">
                                            Sun Pharma
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="brand-filter me-2" value="Dr. Reddy's" onchange="applyFilters()">
                                            Dr. Reddy's
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="brand-filter me-2" value="Lupin" onchange="applyFilters()">
                                            Lupin
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="brand-filter me-2" value="Ranbaxy" onchange="applyFilters()">
                                            Ranbaxy
                                        </label>
                                    </li>
                                    <li>
                                        <label class="dropdown-item">
                                            <input type="checkbox" class="brand-filter me-2" value="Abbott" onchange="applyFilters()">
                                            Abbott
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Sort Dropdown --}}
                        <div class="mb-4">
                            <div class="dropdown">
                                <button class="btn btn-outline-warning w-100 dropdown-toggle text-start d-flex justify-content-between align-items-center" 
                                        type="button" id="sortDropdown" data-bs-toggle="dropdown">
                                    <span><i class="fas fa-sort me-2"></i>Sort By</span>
                                </button>
                                <ul class="dropdown-menu w-100" aria-labelledby="sortDropdown">
                                    <li>
                                        <button class="dropdown-item" onclick="setSortOption('name', 'asc')">
                                            <i class="fas fa-sort-alpha-down me-2"></i>Name (A to Z)
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item" onclick="setSortOption('name', 'desc')">
                                            <i class="fas fa-sort-alpha-up me-2"></i>Name (Z to A)
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item" onclick="setSortOption('price', 'asc')">
                                            <i class="fas fa-sort-numeric-down me-2"></i>Price (Low to High)
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item" onclick="setSortOption('price', 'desc')">
                                            <i class="fas fa-sort-numeric-up me-2"></i>Price (High to Low)
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item" onclick="setSortOption('created_at', 'desc')">
                                            <i class="fas fa-clock me-2"></i>Newest First
                                        </button>
                                    </li>
                                    <li>
                                        <button class="dropdown-item" onclick="setSortOption('stock', 'desc')">
                                            <i class="fas fa-cubes me-2"></i>Stock (High to Low)
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        {{-- Applied Filters Display --}}
                        <div id="appliedFilters" class="mb-3" style="display: none;">
                            <h6 class="fw-semibold mb-2">Applied Filters:</h6>
                            <div id="filterTags" class="d-flex flex-wrap gap-2">
                                <!-- Filter tags will be added here dynamically -->
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Products Grid --}}
                <div class="col-lg-9">
                    <div class="section-title">
                        <h2>Featured Medicines</h2>
                        <p class="text-muted">Quality healthcare products for your wellbeing</p>
                    </div>

                    {{-- Results Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="results-info">
                            <span class="badge bg-primary fs-6" id="resultCount">
                                <i class="fas fa-pills me-1"></i>{{ $medicines->count() }} Products
                            </span>
                            @if(request()->filled('search'))
                                <span class="ms-2 text-muted">
                                    <i class="fas fa-search me-1"></i>Search: "{{ request('search') }}"
                                </span>
                            @endif
                        </div>
                        <div class="view-options">
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="viewType" id="gridView" checked>
                                <label class="btn btn-outline-secondary btn-sm" for="gridView">
                                    <i class="fas fa-th"></i>
                                </label>
                                <input type="radio" class="btn-check" name="viewType" id="listView">
                                <label class="btn btn-outline-secondary btn-sm" for="listView">
                                    <i class="fas fa-list"></i>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Loading Indicator --}}
                    <div class="loading" id="loading">
                        <div class="spinner"></div>
                        <p>Loading medicines...</p>
                    </div>

                    {{-- Products Grid --}}
                    <div class="row" id="productsGrid">
                        @forelse($medicines as $medicine)
                            <div class="col-lg-4 col-md-6 medicine-item" 
                                 data-price="{{ $medicine->price }}"
                                 data-brand="{{ $medicine->brand }}"
                                 data-category="{{ strtolower($medicine->category ?? 'tablet') }}">
                                <div class="product-card">
                                    <div class="product-image">
                                        @if($medicine->image)
                                            <img src="{{ asset($medicine->image) }}" alt="{{ $medicine->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                                        @else
                                            <i class="fas fa-pills"></i>
                                        @endif
                                        <div class="product-badge {{ $medicine->stock > 10 ? 'stock-badge' : 'stock-badge low' }}">
                                            Stock: {{ $medicine->stock }}
                                        </div>
                                    </div>
                                    
                                    <h4 class="product-title">{{ $medicine->name }}</h4>
                                    <p class="product-brand">
                                        <i class="fas fa-industry me-1"></i>{{ $medicine->brand }}
                                    </p>
                                    <div class="product-price">₹{{ number_format($medicine->price, 0) }}</div>
                                    
                                    <div class="product-details">
                                        <div><i class="fas fa-info-circle me-1"></i>{{ $medicine->description ?? 'Quality medicine' }}</div>
                                        <div><i class="fas fa-calendar me-1"></i>Exp: {{ $medicine->expiry_date ?? 'N/A' }}</div>
                                    </div>
                                    
                                    @auth
                                        @if($medicine->stock > 0)
                                            {{-- Quantity Selector --}}
                                            <div class="quantity-selector mb-3">
                                                <label class="form-label fw-semibold">
                                                    <i class="fas fa-sort-numeric-up me-1"></i>Quantity:
                                                </label>
                                                <div class="input-group" style="width: 130px; margin: 0 auto;">
                                                    <button class="btn btn-outline-secondary btn-sm" 
                                                            type="button" 
                                                            onclick="changeQuantity({{ $medicine->id }}, -1)">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" 
                                                           class="form-control text-center" 
                                                           id="qty_{{ $medicine->id }}" 
                                                           value="1" 
                                                           min="1" 
                                                           max="{{ $medicine->stock }}"
                                                           style="border-left: none; border-right: none;">
                                                    <button class="btn btn-outline-secondary btn-sm" 
                                                            type="button" 
                                                            onclick="changeQuantity({{ $medicine->id }}, 1)">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <small class="text-muted d-block text-center mt-1">
                                                    Max: {{ $medicine->stock }} available
                                                </small>
                                            </div>

                                            {{-- Add to Cart Button with Quantity --}}
                                            <button class="btn btn-add-cart position-relative" 
                                                    onclick="addToCartWithQuantity({{ $medicine->id }})">
                                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                                <span id="homeCartCount{{ $medicine->id }}" class="cart-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">0</span>
                                            </button>

                                            {{-- Buy Now Button --}}
                                            <button class="btn btn-outline-primary w-100 mt-2" 
                                                    onclick="buyNow({{ $medicine->id }})">
                                                <i class="fas fa-bolt me-2"></i>Buy Now
                                            </button>
                                        @else
                                            <div class="text-center">
                                                <button class="btn btn-secondary w-100" disabled>
                                                    <i class="fas fa-times-circle me-2"></i>Out of Stock
                                                </button>
                                                <small class="text-muted d-block mt-2">
                                                    <i class="fas fa-bell me-1"></i>Notify when available
                                                </small>
                                            </div>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-add-cart">
                                            <i class="fas fa-sign-in-alt me-2"></i>Login to Purchase
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center">
                                <div class="p-5">
                                    <i class="fas fa-pills fa-5x text-muted mb-3"></i>
                                    <h3 class="text-muted">No Medicines Found</h3>
                                    <p class="text-muted">Try adjusting your search or filters</p>
                                    <button class="btn btn-primary" onclick="clearAllFilters()">
                                        <i class="fas fa-refresh me-2"></i>Clear Filters
                                    </button>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if($medicines->hasPages())
                        <div class="d-flex justify-content-center mt-5">
                            {{ $medicines->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="features-section" id="about">
        <div class="container">
            <div class="section-title">
                <h2>Why Choose MediStore?</h2>
                <p class="text-muted">Your trusted healthcare partner</p>
            </div>
            
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5>100% Authentic</h5>
                        <p class="text-muted">All medicines are genuine and sourced from licensed suppliers</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h5>Fast Delivery</h5>
                        <p class="text-muted">Quick and secure delivery to your doorstep</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h5>Expert Support</h5>
                        <p class="text-muted">24/7 customer support from healthcare professionals</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h5>Secure Payment</h5>
                        <p class="text-muted">Safe and secure payment options for your convenience</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Professional Footer --}}
    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @auth
    <script>
        // Global variables for filtering
        let currentFilters = {
            prices: [],
            categories: [],
            brands: [],
            sortBy: 'name',
            sortOrder: 'asc'
        };

        // Add to Cart Function with Quantity
        async function addToCartWithQuantity(medicineId) {
            const quantity = parseInt(document.getElementById(`qty_${medicineId}`).value) || 1;
            
            try {
                const response = await fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        medicine_id: medicineId,
                        quantity: quantity
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    // Update cart count using the global function
                    if (typeof updateCartCount === 'function') {
                        updateCartCount(data.cartCount);
                    } else {
                        // Fallback to manual update
                        const headerCartCount = document.getElementById('headerCartCount');
                        const sidebarCartCount = document.getElementById('sidebarCartCount');
                        
                        if (headerCartCount) {
                            headerCartCount.textContent = data.cartCount;
                            headerCartCount.style.display = data.cartCount > 0 ? 'inline' : 'none';
                        }
                        if (sidebarCartCount) {
                            sidebarCartCount.textContent = data.cartCount;
                            sidebarCartCount.style.display = data.cartCount > 0 ? 'inline' : 'none';
                        }
                    }
                    
                    // Show success message with quantity
                    showToast(`${quantity} item(s) added to cart successfully!`, 'success');
                    
                    // Reset quantity to 1
                    document.getElementById(`qty_${medicineId}`).value = 1;
                } else {
                    showToast(data.message || 'Error adding to cart', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error adding to cart', 'error');
            }
        }

        // Original Add to Cart Function (kept for compatibility)
        async function addToCart(medicineId) {
            return addToCartWithQuantity(medicineId);
        }

        // Change Quantity Function
        function changeQuantity(medicineId, change) {
            const qtyInput = document.getElementById(`qty_${medicineId}`);
            const currentQty = parseInt(qtyInput.value) || 1;
            const maxQty = parseInt(qtyInput.max) || 99;
            const minQty = 1;
            
            let newQty = currentQty + change;
            
            if (newQty < minQty) newQty = minQty;
            if (newQty > maxQty) {
                newQty = maxQty;
                showToast(`Maximum ${maxQty} items available`, 'warning');
            }
            
            qtyInput.value = newQty;
        }

        // Buy Now Function
        async function buyNow(medicineId) {
            const quantity = parseInt(document.getElementById(`qty_${medicineId}`).value) || 1;
            
            try {
                // Add to cart first
                await addToCartWithQuantity(medicineId);
                
                // Redirect to checkout or cart
                setTimeout(() => {
                    window.location.href = '/cart';
                }, 1000);
                
            } catch (error) {
                console.error('Error:', error);
                showToast('Error processing order', 'error');
            }
        }

        // Filter Functions
        function applyFilters() {
            // Get selected price filters
            currentFilters.prices = Array.from(document.querySelectorAll('.price-filter:checked'))
                .map(cb => cb.value);
            
            // Get selected category filters
            currentFilters.categories = Array.from(document.querySelectorAll('.category-filter:checked'))
                .map(cb => cb.value);
            
            // Get selected brand filters
            currentFilters.brands = Array.from(document.querySelectorAll('.brand-filter:checked'))
                .map(cb => cb.value);
            
            // Apply filters to products
            filterProducts();
            updateFilterTags();
        }

        function filterProducts() {
            const products = document.querySelectorAll('.medicine-item');
            let visibleCount = 0;
            
            products.forEach(product => {
                let showProduct = true;
                
                // Price filter
                if (currentFilters.prices.length > 0) {
                    const price = parseFloat(product.dataset.price);
                    let matchesPrice = false;
                    
                    currentFilters.prices.forEach(range => {
                        const [min, max] = range.split('-').map(Number);
                        if (price >= min && price <= max) {
                            matchesPrice = true;
                        }
                    });
                    
                    if (!matchesPrice) showProduct = false;
                }
                
                // Category filter
                if (currentFilters.categories.length > 0) {
                    const category = product.dataset.category;
                    if (!currentFilters.categories.includes(category)) {
                        showProduct = false;
                    }
                }
                
                // Brand filter
                if (currentFilters.brands.length > 0) {
                    const brand = product.dataset.brand;
                    if (!currentFilters.brands.includes(brand)) {
                        showProduct = false;
                    }
                }
                
                // Show/hide product
                if (showProduct) {
                    product.style.display = 'block';
                    visibleCount++;
                } else {
                    product.style.display = 'none';
                }
            });
            
            // Show no results message if needed
            const noResults = document.querySelector('.no-results');
            if (visibleCount === 0 && !noResults) {
                const gridContainer = document.getElementById('productsGrid');
                const noResultsDiv = document.createElement('div');
                noResultsDiv.className = 'col-12 text-center no-results';
                noResultsDiv.innerHTML = `
                    <div class="p-5">
                        <i class="fas fa-search fa-5x text-muted mb-3"></i>
                        <h3 class="text-muted">No Products Match Your Filters</h3>
                        <p class="text-muted">Try adjusting your filters or search terms</p>
                        <button class="btn btn-primary" onclick="clearAllFilters()">
                            <i class="fas fa-refresh me-2"></i>Clear All Filters
                        </button>
                    </div>
                `;
                gridContainer.appendChild(noResultsDiv);
            } else if (visibleCount > 0 && noResults) {
                noResults.remove();
            }
        }

        function updateFilterTags() {
            const appliedFiltersDiv = document.getElementById('appliedFilters');
            const filterTagsDiv = document.getElementById('filterTags');
            
            filterTagsDiv.innerHTML = '';
            
            let hasFilters = false;
            
            // Add price filter tags
            currentFilters.prices.forEach(price => {
                hasFilters = true;
                const tag = createFilterTag(`Price: ₹${price.replace('-', ' - ₹')}`, 'price', price);
                filterTagsDiv.appendChild(tag);
            });
            
            // Add category filter tags
            currentFilters.categories.forEach(category => {
                hasFilters = true;
                const tag = createFilterTag(`Category: ${category}`, 'category', category);
                filterTagsDiv.appendChild(tag);
            });
            
            // Add brand filter tags
            currentFilters.brands.forEach(brand => {
                hasFilters = true;
                const tag = createFilterTag(`Brand: ${brand}`, 'brand', brand);
                filterTagsDiv.appendChild(tag);
            });
            
            appliedFiltersDiv.style.display = hasFilters ? 'block' : 'none';
        }

        function createFilterTag(text, type, value) {
            const tag = document.createElement('span');
            tag.className = 'filter-tag';
            tag.innerHTML = `
                ${text}
                <i class="fas fa-times remove-filter" onclick="removeFilter('${type}', '${value}')"></i>
            `;
            return tag;
        }

        function removeFilter(type, value) {
            if (type === 'price') {
                currentFilters.prices = currentFilters.prices.filter(p => p !== value);
                document.querySelector(`.price-filter[value="${value}"]`).checked = false;
            } else if (type === 'category') {
                currentFilters.categories = currentFilters.categories.filter(c => c !== value);
                document.querySelector(`.category-filter[value="${value}"]`).checked = false;
            } else if (type === 'brand') {
                currentFilters.brands = currentFilters.brands.filter(b => b !== value);
                document.querySelector(`.brand-filter[value="${value}"]`).checked = false;
            }
            
            filterProducts();
            updateFilterTags();
        }

        function clearAllFilters() {
            // Uncheck all checkboxes
            document.querySelectorAll('.price-filter, .category-filter, .brand-filter')
                .forEach(cb => cb.checked = false);
            
            // Clear filters
            currentFilters = {
                prices: [],
                categories: [],
                brands: [],
                sortBy: 'name',
                sortOrder: 'asc'
            };
            
            // Show all products
            document.querySelectorAll('.medicine-item').forEach(product => {
                product.style.display = 'block';
            });
            
            // Remove no results message if exists
            const noResults = document.querySelector('.no-results');
            if (noResults) noResults.remove();
            
            // Hide filter tags
            document.getElementById('appliedFilters').style.display = 'none';
            
            showToast('All filters cleared', 'info');
        }

        function setSortOption(sortBy, sortOrder) {
            currentFilters.sortBy = sortBy;
            currentFilters.sortOrder = sortOrder;
            
            // Update button text
            const sortButton = document.querySelector('#sortDropdown');
            const sortTexts = {
                'name_asc': 'Name (A to Z)',
                'name_desc': 'Name (Z to A)',
                'price_asc': 'Price (Low to High)',
                'price_desc': 'Price (High to Low)',
                'created_at_desc': 'Newest First',
                'stock_desc': 'Stock (High to Low)'
            };
            
            const sortKey = `${sortBy}_${sortOrder}`;
            if (sortTexts[sortKey]) {
                sortButton.innerHTML = `<span><i class="fas fa-sort me-2"></i>${sortTexts[sortKey]}</span>`;
            }
            
            // Sort products
            sortProducts();
            
            showToast(`Sorted by ${sortTexts[sortKey]}`, 'info');
        }

        function sortProducts() {
            const container = document.getElementById('productsGrid');
            const products = Array.from(container.querySelectorAll('.medicine-item'));
            
            products.sort((a, b) => {
                let aValue, bValue;
                
                if (currentFilters.sortBy === 'price') {
                    aValue = parseFloat(a.dataset.price);
                    bValue = parseFloat(b.dataset.price);
                } else if (currentFilters.sortBy === 'name') {
                    aValue = a.querySelector('.product-title').textContent.toLowerCase();
                    bValue = b.querySelector('.product-title').textContent.toLowerCase();
                } else if (currentFilters.sortBy === 'brand') {
                    aValue = a.dataset.brand.toLowerCase();
                    bValue = b.dataset.brand.toLowerCase();
                } else {
                    return 0; // No sorting for other criteria in client-side
                }
                
                if (currentFilters.sortOrder === 'asc') {
                    return aValue > bValue ? 1 : -1;
                } else {
                    return aValue < bValue ? 1 : -1;
                }
            });
            
            // Reorder products in DOM
            products.forEach(product => container.appendChild(product));
        }

        // Show Toast Notification (Enhanced)
        function showToast(message, type = 'success') {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${getToastColor(type)} border-0`;
            toast.setAttribute('role', 'alert');
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="${getToastIcon(type)} me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;

            // Add to page
            let toastContainer = document.getElementById('toastContainer');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toastContainer';
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '9999';
                document.body.appendChild(toastContainer);
            }
            
            toastContainer.appendChild(toast);
            
            // Show toast
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Remove after hiding
            toast.addEventListener('hidden.bs.toast', () => {
                toast.remove();
            });
        }

        function getToastColor(type) {
            const colors = {
                success: 'success',
                error: 'danger',
                warning: 'warning',
                info: 'info'
            };
            return colors[type] || 'primary';
        }

        function getToastIcon(type) {
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            return icons[type] || 'fas fa-bell';
        }

        // Load cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCartCount();
            loadOrderCount();
            initializeSidebar();
            initializeTooltips();
        });

        // Initialize Tooltips
        function initializeTooltips() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Initialize Sidebar
        function initializeSidebar() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('userSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.add('active');
                    overlay.classList.add('active');
                });
            }

            if (overlay) {
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }

            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                }
            });
        }

        async function loadOrderCount() {
            try {
                const response = await fetch('/orders/count');
                const data = await response.json();
                if (data.count !== undefined) {
                    // Use global updateOrderCount function if available
                    if (typeof updateOrderCount === 'function') {
                        updateOrderCount(data.count);
                    } else {
                        // Fallback to manual update
                        const sidebarOrderCount = document.getElementById('sidebarOrderCount');
                        
                        if (sidebarOrderCount) {
                            sidebarOrderCount.textContent = data.count;
                            sidebarOrderCount.style.display = data.count > 0 ? 'inline' : 'none';
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading order count:', error);
            }
        }

        async function loadCartCount() {
            try {
                const response = await fetch('/cart/count');
                const data = await response.json();
                if (data.count !== undefined) {
                    // Use global updateCartCount function if available
                    if (typeof updateCartCount === 'function') {
                        updateCartCount(data.count);
                    } else {
                        // Fallback to manual update
                        const headerCartCount = document.getElementById('headerCartCount');
                        const sidebarCartCount = document.getElementById('sidebarCartCount');
                        
                        if (headerCartCount) {
                            headerCartCount.textContent = data.count;
                            headerCartCount.style.display = data.count > 0 ? 'inline' : 'none';
                        }
                        if (sidebarCartCount) {
                            sidebarCartCount.textContent = data.count;
                            sidebarCartCount.style.display = data.count > 0 ? 'inline' : 'none';
                        }
                        
                        // Update all home page cart counts
                        const allHomeCartCounts = document.querySelectorAll('[id^="homeCartCount"]');
                        allHomeCartCounts.forEach(element => {
                            element.textContent = data.count;
                            element.style.display = data.count > 0 ? 'inline' : 'none';
                        });
                    }
                }
            } catch (error) {
                console.error('Error loading cart count:', error);
            }
        }
    </script>
    @endauth

</body>
</html>