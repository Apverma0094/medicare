<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - User Details</title>
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
        .profile-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 15px;
        }
        .info-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .badge-admin {
            background-color: #dc3545;
            font-size: 0.9rem;
        }
        .badge-client {
            background-color: #28a745;
            font-size: 0.9rem;
        }
        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: white;
            margin: 0 auto 20px;
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
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- User Profile Card --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card profile-card">
                    <div class="card-body text-center">
                        <div class="avatar">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <h3 class="card-title">{{ $user->name }}</h3>
                        <p class="card-text">{{ $user->email }}</p>
                        @if($user->role === 'admin')
                            <span class="badge badge-admin mb-3">👑 Administrator</span>
                        @else
                            <span class="badge badge-client mb-3">👤 Client User</span>
                        @endif
                        
                        @if($user->id === auth()->id())
                            <div class="alert alert-info mt-3">
                                <strong>📍 This is your account</strong>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card info-card">
                    <div class="card-header">
                        <h4 class="mb-0">📋 Account Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>👤 Full Name:</strong></div>
                            <div class="col-sm-8">{{ $user->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>📧 Email:</strong></div>
                            <div class="col-sm-8">{{ $user->email }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>🎭 Role:</strong></div>
                            <div class="col-sm-8">
                                @if($user->role === 'admin')
                                    <span class="badge badge-admin">Administrator</span>
                                @else
                                    <span class="badge badge-client">Client</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>📅 Joined:</strong></div>
                            <div class="col-sm-8">{{ $user->created_at->format('F d, Y \a\t h:i A') }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>⏰ Last Updated:</strong></div>
                            <div class="col-sm-8">{{ $user->updated_at->format('F d, Y \a\t h:i A') }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>🆔 User ID:</strong></div>
                            <div class="col-sm-8"><code>#{{ $user->id }}</code></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Activity & Stats --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card info-card">
                    <div class="card-header">
                        <h4 class="mb-0">📊 Account Statistics</h4>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <h3 class="text-primary">{{ $user->created_at->diffInDays() }}</h3>
                                <p class="text-muted">Days Since Joining</p>
                            </div>
                            <div class="col-6">
                                <h3 class="text-success">
                                    @if($user->email_verified_at)
                                        ✅
                                    @else
                                        ❌
                                    @endif
                                </h3>
                                <p class="text-muted">Email Verified</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card info-card">
                    <div class="card-header">
                        <h4 class="mb-0">🕐 Timeline</h4>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="mb-3">
                                <strong>🎯 Account Created:</strong>
                                <br>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                            @if($user->email_verified_at)
                            <div class="mb-3">
                                <strong>✅ Email Verified:</strong>
                                <br>
                                <small class="text-muted">{{ $user->email_verified_at->diffForHumans() }}</small>
                            </div>
                            @endif
                            <div class="mb-3">
                                <strong>🔄 Last Profile Update:</strong>
                                <br>
                                <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                        ← Back to Users List
                    </a>
                    @if($user->id !== auth()->id())
                    <button class="btn btn-warning" onclick="alert('User role modification feature coming soon!')">
                        🔄 Change Role
                    </button>
                    <button class="btn btn-danger" onclick="confirmDelete()">
                        🗑️ Delete User
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete() {
            if(confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                alert('Delete functionality will be implemented soon!');
            }
        }
    </script>
</body>
</html>