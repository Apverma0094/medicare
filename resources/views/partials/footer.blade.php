{{-- Consistent Footer Component --}}
<footer class="professional-footer mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="footer-brand">
                    <a href="{{ route('home') }}" class="footer-logo">
                        <i class="fas fa-pills me-2"></i>MediStore
                    </a>
                    <p class="footer-tagline">Your Health, Our Priority</p>
                    <p class="footer-desc">Quality medicines and healthcare products delivered safely to your doorstep.</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="footer-title">Quick Links</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    @auth
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('user.cart') }}">Cart</a></li>
                        <li><a href="{{ route('user.orders.index') }}">Orders</a></li>
                    @else
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="footer-title">Categories</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('home', ['category' => 'tablet']) }}">Tablets</a></li>
                    <li><a href="{{ route('home', ['category' => 'syrup']) }}">Syrups</a></li>
                    <li><a href="{{ route('home', ['category' => 'injection']) }}">Injections</a></li>
                    <li><a href="{{ route('home', ['category' => 'capsule']) }}">Capsules</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="footer-title">Support</h6>
                <ul class="footer-links">
                    <li><a href="#" onclick="return false;">Help Center</a></li>
                    <li><a href="#" onclick="return false;">Contact Us</a></li>
                    <li><a href="#" onclick="return false;">FAQ</a></li>
                    <li><a href="#" onclick="return false;">Shipping Info</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="footer-title">Connect</h6>
                <div class="social-links">
                    <a href="#" class="social-link" onclick="return false;">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-link" onclick="return false;">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link" onclick="return false;">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="social-link" onclick="return false;">
                        <i class="fab fa-linkedin"></i>
                    </a>
                </div>
                <div class="footer-contact mt-3">
                    <p><i class="fas fa-phone me-2"></i>+91 98765 43210</p>
                    <p><i class="fas fa-envelope me-2"></i>support@medistore.com</p>
                </div>
            </div>
        </div>
        
        <hr class="footer-divider">
        
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="footer-copyright">
                    &copy; {{ date('Y') }} <a href="{{ route('home') }}" class="brand-link">MediStore</a>. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="footer-badges">
                    <span class="badge bg-success me-2"><i class="fas fa-shield-alt me-1"></i>100% Authentic</span>
                    <span class="badge bg-primary me-2"><i class="fas fa-shipping-fast me-1"></i>Fast Delivery</span>
                    <span class="badge bg-warning"><i class="fas fa-lock me-1"></i>Secure Payment</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.professional-footer {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 3rem 0 1rem;
    margin-top: auto;
}

.footer-logo {
    color: var(--primary-color);
    font-size: 1.8rem;
    font-weight: 700;
    text-decoration: none;
    display: inline-block;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.footer-logo:hover {
    color: var(--secondary-color);
    transform: translateY(-2px);
}

.footer-tagline {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.footer-desc {
    color: #bdc3c7;
    font-size: 0.9rem;
    line-height: 1.6;
}

.footer-title {
    color: white;
    font-weight: 600;
    margin-bottom: 1rem;
    border-bottom: 2px solid var(--primary-color);
    display: inline-block;
    padding-bottom: 0.5rem;
}

.footer-links {
    list-style: none;
    padding: 0;
}

.footer-links li {
    margin-bottom: 0.5rem;
}

.footer-links a {
    color: #bdc3c7;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.footer-links a:hover {
    color: var(--primary-color);
    padding-left: 5px;
}

.social-links {
    display: flex;
    gap: 10px;
    margin-bottom: 1rem;
}

.social-link {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-link:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-3px);
}

.footer-contact p {
    color: #bdc3c7;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.footer-contact i {
    color: var(--primary-color);
}

.footer-divider {
    border-color: rgba(255, 255, 255, 0.2);
    margin: 2rem 0 1rem;
}

.footer-copyright {
    color: #bdc3c7;
    margin: 0;
    font-size: 0.9rem;
}

.brand-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
}

.brand-link:hover {
    color: var(--secondary-color);
}

.footer-badges {
    display: flex;
    gap: 5px;
    justify-content: flex-end;
}

.footer-badges .badge {
    padding: 8px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
}

@media (max-width: 768px) {
    .footer-badges {
        justify-content: center;
        margin-top: 1rem;
    }
    
    .social-links {
        justify-content: center;
    }
}
</style>