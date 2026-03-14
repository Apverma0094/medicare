<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - Medicine Store</title>
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
            min-height: 100vh;
        }
        .container {
            padding-top: 20px;
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
        .track-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            background: white;
        }
        .track-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px;
            text-align: center;
        }
        .status-timeline {
            position: relative;
            padding: 30px 20px;
        }
        .status-step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 30px;
            position: relative;
        }
        .status-step::before {
            content: '';
            position: absolute;
            left: 30px;
            top: 60px;
            width: 3px;
            height: calc(100% + 10px);
            background: #dee2e6;
        }
        .status-step:last-child::before {
            display: none;
        }
        .status-step.active::before {
            background: var(--secondary-color);
        }
        .status-step.completed::before {
            background: var(--secondary-color);
        }
        .status-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #f8f9fa;
            border: 3px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 20px;
            z-index: 2;
            position: relative;
            flex-shrink: 0;
        }
        .status-step.active .status-icon {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
            animation: pulse 2s infinite;
        }
        .status-step.completed .status-icon {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }
        .status-step.cancelled .status-icon {
            background: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
        }
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(39, 174, 96, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(39, 174, 96, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(39, 174, 96, 0);
            }
        }
        .status-content h6 {
            margin-bottom: 5px;
            font-weight: 600;
        }
        .status-content p {
            margin-bottom: 0;
            color: #6c757d;
        }
        .order-info {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    {{-- Professional Header --}}
    @include('partials.header')

    <div class="container main-content">
        {{-- Page Header --}}
        <div class="page-header">
            <h1 class="page-title"><i class="fas fa-map-marker-alt me-2"></i>Track Order</h1>
            <p class="page-subtitle">Monitor your order status and delivery progress</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Order Info -->
                <div class="order-info">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-2">📍 Track Order</h4>
                            <h5>{{ $order->order_number }}</h5>
                            <p class="mb-0">
                                <strong>Delivery to:</strong> {{ $order->delivery_address['name'] }}<br>
                                {{ $order->delivery_address['city'] }} - {{ $order->delivery_address['pincode'] }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <h3>₹{{ number_format($order->grand_total, 2) }}</h3>
                            <span class="badge fs-6 bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'info')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tracking Timeline -->
                <div class="card track-card">
                    <div class="track-header">
                        <h3>📦 Order Status</h3>
                        <p class="mb-0">Real-time tracking of your order</p>
                    </div>
                    
                    <div class="status-timeline">
                        @php
                            $currentFound = false;
                            $statusOrder = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                            $currentIndex = array_search($order->status, $statusOrder);
                            
                            if ($order->status === 'cancelled') {
                                $currentIndex = 0; // Show cancelled at the beginning
                            }
                        @endphp

                        @if($order->status === 'cancelled')
                            <!-- Cancelled Status -->
                            <div class="status-step cancelled">
                                <div class="status-icon">
                                    {{ $statusSteps['cancelled']['icon'] }}
                                </div>
                                <div class="status-content">
                                    <h6>{{ $statusSteps['cancelled']['title'] }}</h6>
                                    <p>{{ $statusSteps['cancelled']['desc'] }}</p>
                                    <small class="text-muted">{{ $order->updated_at->format('d M Y, h:i A') }}</small>
                                </div>
                            </div>
                        @else
                            @foreach($statusOrder as $index => $status)
                                @if(isset($statusSteps[$status]))
                                    @php
                                        $isCompleted = $index < $currentIndex;
                                        $isActive = $index === $currentIndex;
                                        $stepClass = $isCompleted ? 'completed' : ($isActive ? 'active' : '');
                                    @endphp
                                    
                                    <div class="status-step {{ $stepClass }}">
                                        <div class="status-icon">
                                            @if($isCompleted)
                                                ✅
                                            @else
                                                {{ $statusSteps[$status]['icon'] }}
                                            @endif
                                        </div>
                                        <div class="status-content">
                                            <h6>{{ $statusSteps[$status]['title'] }}</h6>
                                            <p>{{ $statusSteps[$status]['desc'] }}</p>
                                            @if($isCompleted || $isActive)
                                                <small class="text-success">
                                                    @if($status === 'pending')
                                                        {{ $order->order_date->format('d M Y, h:i A') }}
                                                    @elseif($status === 'delivered' && $order->delivery_date)
                                                        {{ $order->delivery_date->format('d M Y, h:i A') }}
                                                    @elseif($isActive)
                                                        Updated {{ $order->updated_at->diffForHumans() }}
                                                    @endif
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Order Items Summary -->
                <div class="card track-card">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                        <h6 class="mb-0">🛒 Items in this Order ({{ count($order->orderItems) }})</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($order->orderItems as $item)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 border rounded">
                                    <div class="me-3">
                                        <span class="badge bg-primary">{{ $item->quantity }}x</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $item->medicine->name }}</h6>
                                        <small class="text-muted">{{ $item->medicine->brand }}</small>
                                    </div>
                                    <div>
                                        <strong>₹{{ number_format($item->total, 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card track-card">
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-outline-primary w-100" style="border-color: var(--primary-color); color: var(--primary-color);">
                                    👁️ View Details
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('user.orders.index') }}" class="btn btn-outline-secondary w-100" style="border-color: var(--secondary-color); color: var(--secondary-color);">
                                    ← All Orders
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                @if($order->status === 'pending')
                                    <form action="{{ route('user.orders.cancel', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-danger w-100" 
                                                onclick="return confirm('Are you sure you want to cancel this order?')">
                                            ❌ Cancel Order
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-outline-success w-100" style="border-color: var(--secondary-color); color: var(--secondary-color);" onclick="refreshTracking()">
                                        🔄 Refresh
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delivery Info -->
                @if($order->status === 'delivered' && $order->delivery_date)
                    <div class="card track-card">
                        <div class="card-header text-white" style="background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));">
                            <h6 class="mb-0">✅ Delivery Completed</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>Delivered on:</strong> {{ $order->delivery_date->format('d M Y, h:i A') }}</p>
                            <p><strong>Delivered to:</strong> {{ $order->delivery_address['name'] }}</p>
                            <div class="text-center">
                                <p class="text-success mb-2">🎉 Thank you for your order!</p>
                                <a href="{{ route('home') }}" class="btn" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; border: none; border-radius: 25px;">Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.header-scripts')
    <script>
        function refreshTracking() {
            location.reload();
        }
        
        // Auto-refresh every 30 seconds if order is not delivered or cancelled
        @if($order->status !== 'delivered' && $order->status !== 'cancelled')
        setInterval(function() {
            location.reload();
        }, 30000);
        @endif
    </script>
</body>
</html>