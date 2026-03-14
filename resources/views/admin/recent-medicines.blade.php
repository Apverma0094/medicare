<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recent Medicines - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
            padding: 20px;
            position: fixed;
            width: 250px;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255,255,255,0.2);
            border-radius: 5px;
            padding-left: 10px;
            transition: all 0.3s ease;
        }
        .medicine-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }
        .medicine-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .stock-badge {
            font-size: 0.8rem;
            padding: 4px 8px;
        }
        .low-stock {
            background-color: #ffc107;
            color: #000;
        }
        .out-of-stock {
            background-color: #dc3545;
            color: #fff;
        }
        .in-stock {
            background-color: #28a745;
            color: #fff;
        }
    </style>
</head>
<body>
    {{-- Sidebar --}}
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="{{ route('admin.dashboard') }}">🏠 Dashboard</a>
        <a href="{{ route('medicines.index') }}">💊 Manage Medicines</a>
        <a href="{{ route('admin.orders.index') }}">📦 Manage Orders</a>
        <a href="{{ route('admin.recent.orders') }}">📋 Recent Orders</a>
        <a href="{{ route('admin.recent.medicines') }}" class="active">🆕 Recent Medicines</a>
        <a href="{{ route('admin.notifications.index') }}">🔔 Notifications</a>
        <a href="{{ route('admin.users') }}">👥 Manage Users</a>
        <a href="{{ route('profile.edit') }}">👤 Profile</a>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn-danger w-100 mt-3">🚪 Logout</button>
        </form>
    </div>

    {{-- Main Content --}}
    <div class="content">
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

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1>🆕 Recent Medicines</h1>
                <p class="text-muted mb-0">Medicines added in the last 30 days</p>
            </div>
            <div>
                <a href="{{ route('medicines.create') }}" class="btn btn-success">➕ Add Medicine</a>
                <a href="{{ route('medicines.index') }}" class="btn btn-primary">💊 All Medicines</a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">← Back to Dashboard</a>
            </div>
        </div>

        <!-- Medicine Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <h3>{{ $todayMedicines }}</h3>
                    <p class="mb-0">📅 Added Today</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3>{{ $thisWeekMedicines }}</h3>
                    <p class="mb-0">📊 This Week</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3>{{ $lowStockMedicines }}</h3>
                    <p class="mb-0">⚠️ Low Stock</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <h3>{{ $outOfStockMedicines }}</h3>
                    <p class="mb-0">🚫 Out of Stock</p>
                </div>
            </div>
        </div>

        <!-- Recent Medicines List -->
        <div class="row">
            @if($recentMedicines->count())
                @foreach($recentMedicines as $medicine)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card medicine-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h6 class="card-title mb-0">{{ $medicine->name }}</h6>
                                    <span class="badge stock-badge 
                                        {{ $medicine->stock == 0 ? 'out-of-stock' : ($medicine->stock <= 10 ? 'low-stock' : 'in-stock') }}">
                                        {{ $medicine->stock }} left
                                    </span>
                                </div>
                                
                                <p class="card-text">
                                    <strong>Brand:</strong> {{ $medicine->brand }}<br>
                                    <strong>Category:</strong> {{ $medicine->category }}<br>
                                    <strong>Price:</strong> ₹{{ number_format($medicine->price, 2) }}
                                </p>
                                
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <strong>Added:</strong> {{ $medicine->created_at->format('d M Y, h:i A') }}
                                        <br>{{ $medicine->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                
                                @if($medicine->description)
                                    <p class="card-text">
                                        <small class="text-muted">{{ \Str::limit($medicine->description, 100) }}</small>
                                    </p>
                                @endif
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('medicines.show', $medicine->id) }}" class="btn btn-sm btn-outline-primary">
                                        👁️ View
                                    </a>
                                    <a href="{{ route('medicines.edit', $medicine->id) }}" class="btn btn-sm btn-outline-warning">
                                        ✏️ Edit
                                    </a>
                                    @if($medicine->stock <= 10)
                                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#stockModal{{ $medicine->id }}">
                                            📦 Stock
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stock Update Modal -->
                    <div class="modal fade" id="stockModal{{ $medicine->id }}" tabindex="-1">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title">Update Stock</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('medicines.update', $medicine->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="name" value="{{ $medicine->name }}">
                                    <input type="hidden" name="brand" value="{{ $medicine->brand }}">
                                    <input type="hidden" name="category" value="{{ $medicine->category }}">
                                    <input type="hidden" name="price" value="{{ $medicine->price }}">
                                    <input type="hidden" name="description" value="{{ $medicine->description }}">
                                    
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Current Stock: {{ $medicine->stock }}</label>
                                            <input type="number" name="stock" class="form-control" 
                                                   value="{{ $medicine->stock }}" min="0" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary btn-sm">Update Stock</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="col-12">
                    <div class="d-flex justify-content-center mt-4">
                        {{ $recentMedicines->links() }}
                    </div>
                </div>
            @else
                <div class="col-12">
                    <div class="card medicine-card">
                        <div class="card-body text-center py-5">
                            <h3 class="text-muted">💊 No Medicines Found</h3>
                            <p class="text-muted">No medicines have been added yet.</p>
                            <a href="{{ route('medicines.create') }}" class="btn btn-success">
                                ➕ Add First Medicine
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">⚡ Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('medicines.index') }}?filter=low_stock" class="btn btn-warning w-100">
                                    ⚠️ View Low Stock
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('medicines.index') }}?filter=out_of_stock" class="btn btn-danger w-100">
                                    🚫 View Out of Stock
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('medicines.create') }}" class="btn btn-success w-100">
                                    ➕ Add New Medicine
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('medicines.index') }}" class="btn btn-primary w-100">
                                    📋 View All Medicines
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>