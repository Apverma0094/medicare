<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 20px;
            overflow-y: auto;
        }
        .sidebar h2 {
            color: #fff;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            padding: 12px 15px;
            color: #adb5bd;
            text-decoration: none;
            margin-bottom: 5px;
            border-radius: 5px;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #495057;
            color: white;
        }
        .content {
            margin-left: 260px;
            padding: 30px;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
        }
        .admin-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            border: none;
            border-radius: 10px;
        }
        .client-card {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border: none;
            border-radius: 10px;
        }
        .user-card {
            transition: transform 0.3s ease;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .user-card:hover {
            transform: translateY(-3px);
        }
        .badge-admin {
            background-color: #dc3545;
        }
        .badge-client {
            background-color: #28a745;
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="{{ route('admin.dashboard') }}">🏠 Dashboard</a>
        <a href="{{ route('medicines.index') }}">💊 Manage Medicines</a>
        <a href="{{ route('admin.users') }}" class="active">👥 Manage Users</a>
        <a href="{{ route('profile.edit') }}">👤 Profile</a>
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="btn btn-danger w-100 mt-3">🚪 Logout</button>
        </form>
    </div>

    {{-- Main Content --}}
    <div class="content">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1 class="mb-4">👥 User Management</h1>
                <p class="text-muted">Manage all registered users and view their details</p>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card stats-card">
                    <div class="card-body text-center">
                        <h2 class="card-title">{{ $totalUsers }}</h2>
                        <p class="card-text">📊 Total Users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card admin-card">
                    <div class="card-body text-center">
                        <h2 class="card-title">{{ $adminUsers }}</h2>
                        <p class="card-text">👑 Admin Users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card client-card">
                    <div class="card-body text-center">
                        <h2 class="card-title">{{ $clientUsers }}</h2>
                        <p class="card-text">👤 Client Users</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">📝 All Users List</h4>
                    </div>
                    <div class="card-body">
                        @if($users->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>👤 Name</th>
                                            <th>📧 Email</th>
                                            <th>🎭 Role</th>
                                            <th>📅 Joined</th>
                                            <th>🔍 Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($users as $index => $user)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $user->name }}</strong>
                                                @if($user->id === auth()->id())
                                                    <span class="badge bg-info">You</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if($user->role === 'admin')
                                                    <span class="badge badge-admin">👑 Admin</span>
                                                @else
                                                    <span class="badge badge-client">👤 Client</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.user.show', $user->id) }}" class="btn btn-sm btn-primary">
                                                    👁️ View Details
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <h4>😔 No Users Found</h4>
                                <p class="text-muted">No users are registered yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Users --}}
        <div class="row mt-4">
            <div class="col-md-12">
                <h3 class="mb-3">🕐 Recent Users</h3>
                <div class="row">
                    @foreach($users->take(3) as $user)
                    <div class="col-md-4 mb-3">
                        <div class="card user-card">
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $user->name }}</h5>
                                <p class="card-text text-muted">{{ $user->email }}</p>
                                <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : 'badge-client' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                                <div class="mt-2">
                                    <small class="text-muted">Joined {{ $user->created_at->diffForHumans() }}</small>
                                </div>
                                <a href="{{ route('admin.user.show', $user->id) }}" class="btn btn-sm btn-outline-primary mt-2">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>