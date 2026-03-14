<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - MediStore</title>
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
            --dark-color: #2c3e50;
            --light-bg: #f8f9fa;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .main-content {
            margin-top: 0;
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
            min-height: 100vh;
        }

        /* Top Navigation */
        .top-navbar {
            background: var(--white);
            border-bottom: 1px solid #e9ecef;
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
            display: flex;
            align-items: center;
        }

        .navbar-brand i {
            margin-right: 8px;
            font-size: 1.8rem;
        }

        /* Search Bar */
        .search-container {
            flex: 1;
            max-width: 500px;
            margin: 0 2rem;
        }

        .search-input {
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 12px 20px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .search-input:focus {
            border-color: var(--primary-color);
            background: var(--white);
            box-shadow: 0 0 0 0.2rem rgba(22, 160, 133, 0.1);
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary-color);
            border: none;
            border-radius: 20px;
            color: white;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }

        .search-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-50%) scale(1.05);
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .cart-icon {
            position: relative;
            color: var(--dark-color);
            font-size: 1.3rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .cart-icon:hover {
            color: var(--primary-color);
            transform: scale(1.1);
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Main Content */
        .main-content {
            padding: 0;
        }

        .welcome-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 20px;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 200px;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.1)"/></svg>') no-repeat;
            transform: translate(50px, -50px);
        }

        .welcome-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        /* Quick Actions */
        .quick-actions {
            margin-bottom: 3rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: var(--primary-color);
        }

        .action-card {
            background: var(--white);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            height: 100%;
            text-decoration: none;
            color: inherit;
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            text-decoration: none;
            color: inherit;
        }

        .action-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .action-card.cart .action-icon {
            background: linear-gradient(135deg, var(--accent-color), #3b82f6);
        }

        .action-card.orders .action-icon {
            background: linear-gradient(135deg, var(--warning-color), #f59e0b);
        }

        .action-card.profile .action-icon {
            background: linear-gradient(135deg, #8b5cf6, #a855f7);
        }

        .action-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark-color);
        }

        .action-description {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Stats Cards */
        .stats-section {
            margin-bottom: 3rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid #e9ecef;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--dark-color);
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .stat-description {
            color: #6c757d;
            font-size: 0.85rem;
        }

        /* Recent Orders */
        .recent-section {
            background: var(--white);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid #e9ecef;
        }

        .order-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-right: 1rem;
        }

        .order-details {
            flex: 1;
        }

        .order-number {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .order-date {
            color: #6c757d;
            font-size: 0.85rem;
        }

        .order-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-delivered {
            background: #d1fae5;
            color: #065f46;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-processing {
            background: #dbeafe;
            color: #1e40af;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-container {
                margin: 1rem 0;
                order: 3;
                flex-basis: 100%;
            }

            .navbar-brand {
                font-size: 1.2rem;
            }

            .welcome-title {
                font-size: 1.5rem;
            }

            .action-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .stat-number {
                font-size: 1.5rem;
            }
        }

        /* Animations */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-up {
            opacity: 0;
            transform: translateY(20px);
        }

        .animate-slide-up.animate-ready {
            animation: slideUp 0.6s ease-out forwards;
        }

        /* Prevent scroll restoration issues */
        html {
            scroll-behavior: auto;
        }

        /* Ensure stable layout */
        body {
            position: relative;
        }

        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.2s; }
        .animate-delay-3 { animation-delay: 0.3s; }
        .animate-delay-4 { animation-delay: 0.4s; }
    </style>
</head>
<body>

    {{-- Professional Header --}}
    @include('partials.header')

    {{-- Main Content --}}
    <div class="container main-content">
        {{-- Page Header --}}
        <div class="page-header animate-slide-up">
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Welcome back, {{ Auth::user()->name }}! Manage your account and orders</p>
        </div>

        {{-- Welcome Section --}}
        <div class="welcome-section animate-slide-up">
            <h2 class="welcome-title">Your Health Overview 📊</h2>
            <p class="welcome-subtitle">Manage your health, track your orders, and discover new medicines</p>
        </div>

        {{-- Stats Section --}}
        <div class="stats-section animate-slide-up animate-delay-2">
            <h2 class="section-title">
                <i class="fas fa-chart-bar"></i>
                Your Stats
            </h2>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-number" id="totalOrders">{{ $activeOrdersCount ?? 0 }}</div>
                        <div class="stat-label">Active Orders</div>
                        <div class="stat-description">Orders in progress</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-number" id="cartItems">{{ $cartItemsCount ?? 0 }}</div>
                        <div class="stat-label">Cart Items</div>
                        <div class="stat-description">Ready to checkout</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-number">₹{{ number_format($totalSpent ?? 0, 2) }}</div>
                        <div class="stat-label">Total Spent</div>
                        <div class="stat-description">Lifetime investment in health</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-number">{{ $thisMonthOrders ?? 0 }}</div>
                        <div class="stat-label">This Month</div>
                        <div class="stat-description">Recent active orders</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Orders --}}
        <div class="recent-section animate-slide-up animate-delay-3">
            <h2 class="section-title">
                <i class="fas fa-clock"></i>
                Recent Orders
            </h2>
            <div class="text-center py-4">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">No orders yet. Your order history will appear here.</p>
            </div>
        </div>

        {{-- Account Information --}}
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card" style="background: var(--white); border-radius: 15px; border: 1px solid #e9ecef;">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0;">
                        <h5 class="mb-0">
                            <i class="fas fa-user-circle me-2"></i>Account Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Account Type:</strong> 
                                    <span class="badge bg-success">{{ ucfirst(Auth::user()->role ?? 'user') }} User</span>
                                </p>
                                <p><strong>Member Since:</strong> {{ Auth::user()->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100" style="background: linear-gradient(135deg, #f093fb, #f5576c); color: white; border: none; border-radius: 15px;">
                    <div class="card-body text-center">
                        <h5 class="card-title">
                            <i class="fas fa-phone me-2"></i>Need Help?
                        </h5>
                        <p class="card-text">Contact our support team</p>
                        <div class="d-grid gap-2">
                            <button class="btn btn-light" onclick="alert('Contact: support@medistore.com')">
                                <i class="fas fa-envelope me-2"></i>Email Support
                            </button>
                            <button class="btn btn-outline-light">
                                <i class="fas fa-phone me-2"></i>Call: +91-1234567890
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Health Tips Section --}}
        <div class="row mt-4 mb-5">
            <div class="col-md-6">
                <div class="card h-100" style="background: var(--white); border-radius: 15px; border: 1px solid #e9ecef;">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 15px 15px 0 0;">
                        <h5 class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>Health Tips
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Take medicines as prescribed</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Store medicines in cool, dry place</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Check expiry dates regularly</li>
                            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Complete the full course</li>
                            <li class="mb-0"><i class="fas fa-check-circle text-success me-2"></i>Consult doctor for side effects</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100" style="background: var(--white); border-radius: 15px; border: 1px solid #e9ecef;">
                    <div class="card-header" style="background: linear-gradient(135deg, #f093fb, #f5576c); color: white; border-radius: 15px 15px 0 0;">
                        <h5 class="mb-0">
                            <i class="fas fa-ambulance me-2"></i>Emergency Contacts
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-danger">
                                <i class="fas fa-ambulance me-2"></i>Emergency: 102
                            </button>
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-phone me-2"></i>Pharmacy: +91-1234567890
                            </button>
                            <button class="btn btn-outline-success">
                                <i class="fas fa-hospital me-2"></i>Nearest Hospital
                            </button>
                            <button class="btn btn-outline-info">
                                <i class="fas fa-user-md me-2"></i>Doctor Consultation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Prevent scroll restoration that causes page shifting
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
        
        // Force scroll to top on page load
        window.addEventListener('beforeunload', function() {
            window.scrollTo(0, 0);
        });

        // Load cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCartCount();
            
            // Add stable animations without layout shift
            const animateElements = document.querySelectorAll('.animate-slide-up');
            
            // Use CSS animations instead of JS to prevent layout shifts
            animateElements.forEach((el, index) => {
                el.style.animationDelay = `${index * 0.1}s`;
                el.classList.add('animate-ready');
            });
        });

        async function loadCartCount() {
            try {
                const response = await fetch('/cart/count');
                const data = await response.json();
                if (data.count !== undefined) {
                    document.getElementById('cartCount').textContent = data.count;
                    document.getElementById('cartItems').textContent = data.count;
                }
            } catch (error) {
                console.error('Error loading cart count:', error);
            }
        }

        // Add some interactivity
        document.querySelectorAll('.action-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    </script>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.header-scripts')
</body>
</html>