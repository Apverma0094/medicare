/* Professional Header Styles */
:root {
    --primary-color: #16a085;
    --secondary-color: #27ae60;
    --accent-color: #3498db;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
}

/* Header Styles */
.professional-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    box-shadow: 0 2px 20px rgba(22, 160, 133, 0.2);
    z-index: 2000;
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

/* Sidebar Panel */
.user-sidebar {
    position: fixed;
    top: 0;
    right: -350px;
    width: 350px;
    height: 100vh;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    z-index: 10000;
    transition: all 0.3s ease;
    box-shadow: -5px 0 25px rgba(0,0,0,0.1);
    overflow-y: auto;
}

/* Ensure sidebar is above all common elements */
.user-sidebar {
    z-index: 10000 !important;
}

.sidebar-overlay {
    z-index: 9999 !important;
}

/* Ensure other elements stay below sidebar */
.modal {
    z-index: 1050 !important;
}

.dropdown-menu {
    z-index: 1000 !important;
}

.sticky-top, .fixed-top {
    z-index: 2000 !important;
}

.toast-container {
    z-index: 1100 !important;
}

/* Ensure sidebar overlay covers everything when active */
body.sidebar-open {
    overflow: hidden;
}

.sidebar-overlay.active {
    z-index: 9999 !important;
}

/* Ensure checkout cards and other content stay below sidebar */
.checkout-card, .card, .btn-group, .dropdown {
    z-index: auto !important;
}

/* Specific fixes for common overlapping elements */
.position-fixed:not(.user-sidebar):not(.sidebar-overlay):not(.professional-header) {
    z-index: 1000 !important;
}

.position-sticky:not(.professional-header) {
    z-index: 1000 !important;
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

/* Integrated Sidebar Toggle in Navbar */
.sidebar-toggle-nav {
    border-radius: 10px;
    transition: all 0.3s ease;
    border-width: 2px;
}

.sidebar-toggle-nav:hover {
    background: rgba(255,255,255,0.2);
    border-color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(255,255,255,0.3);
}

.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
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

.order-badge {
    background: var(--primary-color);
    color: white;
    border-radius: 12px;
    padding: 2px 8px;
    font-size: 12px;
    font-weight: 600;
    margin-left: auto;
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