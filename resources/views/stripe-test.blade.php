<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Test Mode - Medicine Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }
        
        .test-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        
        .card-number {
            font-family: 'Courier New', monospace;
            font-size: 1.1em;
            font-weight: bold;
            color: #2c3e50;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        
        .success-card { border-left: 5px solid #27ae60; }
        .decline-card { border-left: 5px solid #e74c3c; }
        .special-card { border-left: 5px solid #3498db; }
    </style>
</head>
<body>

    @include('partials.header')

    <div class="container my-5">
        <div class="text-center mb-5">
            <h1 class="display-4 text-primary">
                <i class="fas fa-credit-card me-3"></i>Stripe Test Mode
            </h1>
            <p class="lead text-muted">Use these test card numbers to test payments safely</p>
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Test Mode Active:</strong> No real money will be charged
            </div>
        </div>

        <div class="row">
            <!-- Successful Payments -->
            <div class="col-md-6 mb-4">
                <div class="test-card success-card p-4">
                    <h4 class="text-success">
                        <i class="fas fa-check-circle me-2"></i>Successful Test Cards
                    </h4>
                    <p class="text-muted">These cards will process payments successfully</p>
                    
                    <div class="mb-3">
                        <strong>Visa (Most Common)</strong>
                        <div class="card-number">4242 4242 4242 4242</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Visa (Debit)</strong>
                        <div class="card-number">4000 0566 5566 5556</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Mastercard</strong>
                        <div class="card-number">5555 5555 5555 4444</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>American Express</strong>
                        <div class="card-number">3782 8224 6310 005</div>
                    </div>
                </div>
            </div>

            <!-- Declined Payments -->
            <div class="col-md-6 mb-4">
                <div class="test-card decline-card p-4">
                    <h4 class="text-danger">
                        <i class="fas fa-times-circle me-2"></i>Declined Test Cards
                    </h4>
                    <p class="text-muted">These cards will be declined</p>
                    
                    <div class="mb-3">
                        <strong>Generic Decline</strong>
                        <div class="card-number">4000 0000 0000 0002</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Insufficient Funds</strong>
                        <div class="card-number">4000 0000 0000 9995</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Lost Card</strong>
                        <div class="card-number">4000 0000 0000 9987</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Stolen Card</strong>
                        <div class="card-number">4000 0000 0000 9979</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Special Scenarios -->
        <div class="row">
            <div class="col-12">
                <div class="test-card special-card p-4">
                    <h4 class="text-primary">
                        <i class="fas fa-cog me-2"></i>Special Test Scenarios
                    </h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <strong>Requires Authentication (3D Secure)</strong>
                            <div class="card-number">4000 0025 0000 3155</div>
                            <small class="text-muted">Will show authentication dialog</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <strong>Processing Error</strong>
                            <div class="card-number">4000 0000 0000 0119</div>
                            <small class="text-muted">Will fail with processing error</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <strong>Expired Card</strong>
                            <div class="card-number">4000 0000 0000 0069</div>
                            <small class="text-muted">Will decline due to expiry</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="row">
            <div class="col-12">
                <div class="test-card p-4">
                    <h4><i class="fas fa-info-circle me-2"></i>Testing Instructions</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-calendar text-primary me-2"></i><strong>Expiry Date:</strong> Use any future date (e.g., 12/25)</li>
                                <li class="mb-2"><i class="fas fa-lock text-primary me-2"></i><strong>CVC:</strong> Use any 3 digits (e.g., 123)</li>
                                <li class="mb-2"><i class="fas fa-user text-primary me-2"></i><strong>Name:</strong> Use any name</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i><strong>ZIP/Postal Code:</strong> Use any valid code</li>
                                <li class="mb-2"><i class="fas fa-globe text-primary me-2"></i><strong>Country:</strong> Use any supported country</li>
                                <li class="mb-2"><i class="fas fa-shield-alt text-success me-2"></i><strong>Safe Testing:</strong> No real charges will occur</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center mt-4">
            <a href="{{ route('user.cart') }}" class="btn btn-primary btn-lg me-3">
                <i class="fas fa-shopping-cart me-2"></i>Test Payment Now
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-home me-2"></i>Back to Home
            </a>
        </div>
    </div>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.header-scripts')
</body>
</html>