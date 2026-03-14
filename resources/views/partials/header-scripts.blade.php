{{-- Professional Header JavaScript --}}
<script>
    // Global cart and user functionality
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
                document.body.classList.add('sidebar-open');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            });
        }

        // Close sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            }
        });
    }

    // Load Cart Count
    async function loadCartCount() {
        try {
            const response = await fetch('/cart/count');
            const data = await response.json();
            if (data.count !== undefined) {
                updateCartCount(data.count);
            }
        } catch (error) {
            console.error('Error loading cart count:', error);
        }
    }

    // Load Order Count
    async function loadOrderCount() {
        try {
            const response = await fetch('/orders/count');
            const data = await response.json();
            if (data.count !== undefined) {
                updateOrderCount(data.count);
            }
        } catch (error) {
            console.error('Error loading order count:', error);
        }
    }

    // Update Cart Count in all locations
    function updateCartCount(count) {
        const headerCartCount = document.getElementById('headerCartCount');
        const sidebarCartCount = document.getElementById('sidebarCartCount');
        
        // Update header and sidebar cart counts
        if (headerCartCount) {
            headerCartCount.textContent = count;
            headerCartCount.style.display = count > 0 ? 'inline' : 'none';
        }
        if (sidebarCartCount) {
            sidebarCartCount.textContent = count;
            sidebarCartCount.style.display = count > 0 ? 'inline' : 'none';
        }
        
        // Update all other cart count elements throughout the app
        const allCartCounts = document.querySelectorAll('[id*="CartCount"], .cart-count, .cart-badge-count');
        allCartCounts.forEach(element => {
            if (element && element !== headerCartCount && element !== sidebarCartCount) {
                element.textContent = count;
                element.style.display = count > 0 ? 'inline' : 'none';
            }
        });
    }

    // Update Order Count in all locations
    function updateOrderCount(count) {
        const sidebarOrderCount = document.getElementById('sidebarOrderCount');
        
        if (sidebarOrderCount) {
            sidebarOrderCount.textContent = count;
            sidebarOrderCount.style.display = count > 0 ? 'inline' : 'none';
        }
    }

    // Add to Cart Function (for pages that use it)
    async function addToCart(medicineId, quantity = 1) {
        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    medicine_id: medicineId,
                    quantity: quantity
                })
            });

            const data = await response.json();
            
            if (data.success) {
                updateCartCount(data.cartCount);
                showToast(`${quantity} item(s) added to cart successfully!`, 'success');
            } else {
                showToast(data.message || 'Error adding to cart', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToast('Error adding to cart', 'error');
        }
    }

    // Show Toast Notification
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

    // Load Cart Count
    async function loadCartCount() {
        try {
            const response = await fetch('/cart/count');
            const data = await response.json();
            if (data.count !== undefined) {
                updateCartCount(data.count);
            }
        } catch (error) {
            console.error('Error loading cart count:', error);
        }
    }
</script>