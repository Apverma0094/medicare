{{-- Professional Header Component with Integrated Sidebar Menu --}}
<nav class="navbar navbar-expand-lg navbar-dark sticky-top professional-header">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="fas fa-pills me-2"></i>MediStore
        </a>
        
        <div class="d-flex align-items-center">
            @guest
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-sign-in-alt me-1"></i>Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-user-plus me-1"></i>Register
                </a>
            @endguest
            @auth
                <a href="{{ route('user.cart') }}" class="btn btn-outline-light btn-sm me-3 position-relative cart-btn">
                    <i class="fas fa-shopping-cart me-1"></i>Cart
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge-header" id="headerCartCount" style="display: none;">
                        0
                    </span>
                </a>
                
                {{-- Integrated Hamburger Menu Button --}}
                <button class="btn btn-outline-light sidebar-toggle-nav" id="sidebarToggle">
                    <i class="fas fa-bars me-1"></i>
                    <span class="d-none d-md-inline">Menu</span>
                </button>
            @endauth
        </div>
    </div>
</nav>

{{-- User Sidebar Panel --}}
@auth
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="user-sidebar" id="userSidebar">
        <div class="sidebar-header">
            <div class="sidebar-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h5 class="mb-1">{{ Auth::user()->name }}</h5>
            <p class="mb-0 opacity-75">{{ Auth::user()->email }}</p>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.cart') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>My Cart</span>
                    <span class="cart-badge" id="sidebarCartCount" style="display: none;">0</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.orders.index') }}">
                    <i class="fas fa-box"></i>
                    <span>My Orders</span>
                    <span class="order-badge" id="sidebarOrderCount" style="display: none;">0</span>
                </a>
            </li>
            <li>
                <a href="{{ route('profile.edit') }}">
                    <i class="fas fa-user-edit"></i>
                    <span>Profile Settings</span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.orders.index') }}">
                    <i class="fas fa-truck"></i>
                    <span>Track Orders</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="return false;" style="opacity: 0.7;">
                    <i class="fas fa-heart"></i>
                    <span>Wishlist</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="openSupportModal()">
                    <i class="fas fa-headset"></i>
                    <span>Support</span>
                </a>
            </li>
            <li style="margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2);">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Support Modal -->
    <div class="modal fade" id="supportModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-headset me-2"></i>Support & Help</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card h-100 border-0" style="background: linear-gradient(135deg, #f093fb, #f5576c); color: white;">
                                <div class="card-body text-center">
                                    <h5 class="card-title">
                                        <i class="fas fa-phone me-2"></i>Need Help?
                                    </h5>
                                    <p class="card-text">Contact our support team</p>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-light" onclick="alert('Email: support@medistore.com\nResponse time: 2-4 hours')">
                                            <i class="fas fa-envelope me-2"></i>Email Support
                                        </button>
                                        <button class="btn btn-outline-light" onclick="alert('Phone: +91-1234567890\nAvailable: 9 AM - 6 PM')">
                                            <i class="fas fa-phone me-2"></i>Call: +91-1234567890
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-0" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                                <div class="card-body">
                                    <h5 class="mb-3">
                                        <i class="fas fa-ambulance me-2"></i>Emergency Contacts
                                    </h5>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-light" onclick="window.open('tel:102')">
                                            <i class="fas fa-ambulance me-2"></i>Emergency: 102
                                        </button>
                                        <button class="btn btn-outline-light" onclick="window.open('tel:+911234567890')">
                                            <i class="fas fa-phone me-2"></i>Pharmacy: +91-1234567890
                                        </button>
                                        <button class="btn btn-outline-light" onclick="alert('Finding nearest hospital...')">
                                            <i class="fas fa-hospital me-2"></i>Nearest Hospital
                                        </button>
                                        <button class="btn btn-outline-light" onclick="alert('Doctor consultation booking...')">
                                            <i class="fas fa-user-md me-2"></i>Doctor Consultation
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card border-0">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-lightbulb me-2"></i>Health Tips
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Take medicines as prescribed</li>
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Store medicines in cool, dry place</li>
                                                <li class="mb-0"><i class="fas fa-check-circle text-success me-2"></i>Check expiry dates regularly</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Complete the full course</li>
                                                <li class="mb-0"><i class="fas fa-check-circle text-success me-2"></i>Consult doctor for side effects</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openSupportModal() {
            var supportModal = new bootstrap.Modal(document.getElementById('supportModal'));
            supportModal.show();
        }
    </script>
@endauth