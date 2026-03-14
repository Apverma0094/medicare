<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout - Medicine Store</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background-color: #f8f9fa; }
        .navbar-custom {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        .checkout-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        
        .address-card {
            transition: all 0.3s ease;
            border: 2px solid #e9ecef !important;
        }
        
        .address-card:hover {
            border-color: #16a085 !important;
            box-shadow: 0 2px 8px rgba(22, 160, 133, 0.15);
        }
        
        .address-card.border-success {
            border-color: #16a085 !important;
            background-color: rgba(22, 160, 133, 0.05) !important;
        }
        
        .address-card .form-check-input:checked {
            background-color: #16a085;
            border-color: #16a085;
        }
        
        .address-card .form-check-label {
            cursor: pointer;
            width: 100%;
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">🏥 Medicine Store</a>

            <div class="navbar-nav ms-auto">
                <a href="{{ route('user.cart') }}" class="btn btn-outline-secondary btn-sm me-2">← Back to Cart</a>
                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">🏠 Home</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1 class="mb-4">🛒 Checkout</h1>

        <div class="row">

            <!-- Left Side - Form -->
            <div class="col-md-8">

                <div class="card checkout-card">
                    <div class="card-header">
                        <h5 class="mb-0">📋 Delivery Information</h5>
                    </div>

                    <div class="card-body">

                        <form id="checkout-form" method="POST" action="{{ route('user.place.order') }}">
                            @csrf

                            {{-- Address Selection Section --}}
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">📍 Delivery Address</h6>
                                </div>
                                <div class="card-body">
                                    @if($addresses && $addresses->count() > 0)
                                        {{-- Saved Addresses --}}
                                        <div class="mb-3">
                                            <label class="form-label">Select Saved Address</label>
                                            @foreach($addresses as $address)
                                                <div class="card mb-2 address-card {{ $address->is_default ? 'border-primary' : '' }}" 
                                                     style="cursor: pointer;" 
                                                     onclick="selectAddress({{ $address->id }})">
                                                    <div class="card-body p-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   name="saved_address_id" 
                                                                   value="{{ $address->id }}" 
                                                                   id="address_{{ $address->id }}"
                                                                   {{ $address->is_default ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="address_{{ $address->id }}">
                                                                <strong>{{ $address->label }}</strong>
                                                                @if($address->is_default)
                                                                    <span class="badge bg-primary ms-2">Default</span>
                                                                @endif
                                                                <br>
                                                                <small class="text-muted">
                                                                    {{ $address->name }} | {{ $address->phone }}<br>
                                                                    {{ $address->formatted_address }}
                                                                </small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Option to use new address --}}
                                        <div class="card address-card" style="cursor: pointer;" onclick="selectNewAddress()">
                                            <div class="card-body p-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           name="saved_address_id" 
                                                           value="new" 
                                                           id="address_new">
                                                    <label class="form-check-label" for="address_new">
                                                        <strong>+ Add New Address</strong>
                                                        <br>
                                                        <small class="text-muted">Use a different address for this order</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted mb-3">No saved addresses found. Please add your delivery address below.</p>
                                    @endif

                                    {{-- New Address Form (Hidden by default if saved addresses exist) --}}
                                    <div id="new-address-form" class="{{ $addresses && $addresses->count() > 0 ? 'd-none' : '' }}">
                                        <hr class="my-4">
                                        <h6 class="mb-3">{{ $addresses && $addresses->count() > 0 ? 'New Address Details' : 'Address Details' }}</h6>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Full Name *</label>
                                                <input type="text" name="name" class="form-control" 
                                                       value="{{ old('name', auth()->user()->name) }}">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Phone Number *</label>
                                                <input type="tel" name="phone" class="form-control" 
                                                       value="{{ old('phone', auth()->user()->profile?->phone ?? '') }}">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Complete Address *</label>
                                            <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">City *</label>
                                                <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">State *</label>
                                                <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">PIN Code *</label>
                                                <input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Landmark (Optional)</label>
                                            <input type="text" name="landmark" class="form-control" value="{{ old('landmark') }}">
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Address Type</label>
                                                <select name="address_type" class="form-select">
                                                    <option value="home" {{ old('address_type') == 'home' ? 'selected' : '' }}>🏠 Home</option>
                                                    <option value="office" {{ old('address_type') == 'office' ? 'selected' : '' }}>🏢 Office</option>
                                                    <option value="other" {{ old('address_type') == 'other' ? 'selected' : '' }}>📍 Other</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Address Label</label>
                                                <input type="text" name="address_label" class="form-control" 
                                                       placeholder="e.g., Home, Office, Parent's House" 
                                                       value="{{ old('address_label') }}">
                                            </div>
                                        </div>

                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="save_address" value="1" id="save_address" 
                                                   {{ old('save_address') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="save_address">
                                                💾 Save this address for future orders
                                            </label>
                                        </div>

                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="make_default" value="1" id="make_default" 
                                                   {{ old('make_default') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="make_default">
                                                ⭐ Make this my default address
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Order Notes (Optional)</label>
                                <textarea name="notes" class="form-control" rows="2" 
                                          placeholder="Any special instructions for delivery...">{{ old('notes') }}</textarea>
                            </div>

                            <!-- Payment Methods -->
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h6 class="mb-0">💳 Payment Method</h6>
                                </div>

                                <div class="card-body">

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio"
                                               name="payment_method" id="cod" value="cod" checked>
                                        <label class="form-check-label" for="cod">
                                            💵 Cash on Delivery
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                               name="payment_method" id="online" value="online">
                                        <label class="form-check-label" for="online">
                                            💳 Online Payment (UPI / Card)
                                        </label>
                                    </div>

                                </div>
                            </div>

                        </form>

                    </div>
                </div>

            </div>

            <!-- Right Side - Summary -->
            <div class="col-md-4">

                <div class="card checkout-card">
                    <div class="card-header">
                        <h5 class="mb-0">📦 Order Summary</h5>
                    </div>

                    <div class="card-body">

                        @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                            <div>
                                <strong>💊 {{ $item->medicine->name }}</strong><br>
                                <small>{{ $item->quantity }} × ₹{{ number_format($item->price,2) }}</small>
                            </div>

                            <strong>₹{{ number_format($item->total,2) }}</strong>
                        </div>
                        @endforeach

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span>₹{{ number_format($total,2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span>Tax (18% GST):</span>
                            <span>₹{{ number_format($total * 0.18,2) }}</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <strong>Grand Total:</strong>
                            <strong class="text-primary">₹{{ number_format($total * 1.18, 2) }}</strong>
                        </div>

                        <!-- BUTTON -->
                        <div class="d-grid">
                            <button type="button" onclick="startPayment()" class="btn btn-success btn-lg pay-btn">
                                ✅ Place Order
                            </button>
                        </div>

                        <div class="text-center mt-2">
                            <small class="text-muted">🔒 Secure Checkout</small>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Stripe -->
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        function startPayment() {
            const method = document.querySelector('input[name="payment_method"]:checked').value;

            if (method === "cod") {
                document.getElementById('checkout-form').submit();
                return;
            }

            // Validate form before proceeding with Stripe payment
            const form = document.getElementById('checkout-form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Show loading state
            const payBtn = document.querySelector('.pay-btn');
            const originalText = payBtn.innerHTML;
            payBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            payBtn.disabled = true;

            // Collect form data
            const formData = new FormData(form);
            
            // Convert to JSON
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            // Online Payment with Stripe
            fetch("{{ route('stripe.payment') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(data => {
                if (data.url) {
                    window.location.href = data.url;
                } else {
                    throw new Error(data.error || 'Payment failed');
                }
            })
            .catch(error => {
                console.error('Payment error:', error);
                alert("Payment failed: " + (error.message || "Please try again"));
                
                // Reset button state
                payBtn.innerHTML = originalText;
                payBtn.disabled = false;
            });
        }

        // Address selection functionality
        function selectAddress(addressId) {
            // Uncheck all address radios
            document.querySelectorAll('input[name="saved_address_id"]').forEach(radio => {
                radio.checked = false;
            });
            
            // Check the selected address
            document.getElementById('address_' + addressId).checked = true;
            
            // Hide new address form
            document.getElementById('new-address-form').classList.add('d-none');
            
            // Remove required attributes from new address fields
            setNewAddressFieldsRequired(false);
            
            // Update card styling
            updateAddressCardStyling();
        }

        function selectNewAddress() {
            // Uncheck all address radios
            document.querySelectorAll('input[name="saved_address_id"]').forEach(radio => {
                radio.checked = false;
            });
            
            // Check the new address option
            document.getElementById('address_new').checked = true;
            
            // Show new address form
            document.getElementById('new-address-form').classList.remove('d-none');
            
            // Add required attributes to new address fields
            setNewAddressFieldsRequired(true);
            
            // Update card styling
            updateAddressCardStyling();
        }

        function setNewAddressFieldsRequired(required) {
            const requiredFields = ['name', 'phone', 'address', 'city', 'state', 'pincode'];
            requiredFields.forEach(field => {
                const input = document.querySelector(`input[name="${field}"], textarea[name="${field}"]`);
                if (input) {
                    if (required) {
                        input.setAttribute('required', 'required');
                    } else {
                        input.removeAttribute('required');
                    }
                }
            });
        }

        function updateAddressCardStyling() {
            // Remove active styling from all cards
            document.querySelectorAll('.address-card').forEach(card => {
                card.classList.remove('border-success', 'bg-light');
            });
            
            // Add active styling to selected card
            const checkedRadio = document.querySelector('input[name="saved_address_id"]:checked');
            if (checkedRadio) {
                const card = checkedRadio.closest('.address-card');
                if (card) {
                    card.classList.add('border-success', 'bg-light');
                }
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there's a default address selected
            const defaultAddress = document.querySelector('input[name="saved_address_id"]:checked');
            if (defaultAddress && defaultAddress.value !== 'new') {
                setNewAddressFieldsRequired(false);
            } else {
                setNewAddressFieldsRequired(true);
            }
            
            updateAddressCardStyling();
            
            // Add click listeners to address cards
            document.querySelectorAll('.address-card').forEach(card => {
                card.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        if (radio.value === 'new') {
                            selectNewAddress();
                        } else {
                            selectAddress(radio.value);
                        }
                    }
                });
            });
        });
    </script>

</body>
</html>
