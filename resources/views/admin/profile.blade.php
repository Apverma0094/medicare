<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Profile - Medicine Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            background-color: #f5f6fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            position: fixed;
            left: 0;
            top: 0;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #00b4d8;
        }
        .sidebar a {
            display: block;
            color: #ddd;
            padding: 10px 20px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
            color: white;
        }
        .sidebar a.active {
            background-color: #495057;
            color: white;
        }
        .logout {
            background: #e74c3c;
        }
        .content {
            margin-left: 260px;
            padding: 30px;
            flex: 1;
        }
        .profile-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f1f1;
        }
        .profile-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #343a40, #495057);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 3rem;
        }
        .admin-badge {
            background: #00b4d8;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
            margin-top: 0.5rem;
        }
        .form-section {
            margin-bottom: 2rem;
        }
        .section-title {
            color: #343a40;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f1f1f1;
        }
        .form-control:focus {
            border-color: #00b4d8;
            box-shadow: 0 0 0 0.2rem rgba(0, 180, 216, 0.25);
        }
        .btn-primary {
            background: #00b4d8;
            border: none;
            border-radius: 5px;
            padding: 10px 25px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: #0096c7;
        }
        .alert {
            border-radius: 5px;
            border: none;
        }
    </style>
</head>
<body>

    {{-- Admin Sidebar --}}
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="{{ route('admin.dashboard') }}">🏠 Dashboard</a>
        <a href="{{ route('medicines.index') }}">💊 Manage Medicines</a>
        <a href="{{ route('admin.orders.index') }}">📦 Manage Orders</a>
        <a href="{{ route('admin.recent.orders') }}">📋 Recent Orders</a>
        <a href="{{ route('admin.recent.medicines') }}">🆕 Recent Medicines</a>
        <a href="{{ route('admin.notifications.index') }}">🔔 Notifications</a>
        <a href="{{ route('admin.users') }}">👥 Manage Users</a>
        <a href="{{ route('profile.edit') }}" class="active">👤 Profile</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout" style="width:100%; border:none; color:white; cursor:pointer; padding:10px 20px; text-align:left; display:block;">
                🚪 Logout
            </button>
        </form>
    </div>

    {{-- Admin Profile Content --}}
    <div class="content">
        <h1 style="color: #343a40; margin-bottom: 1.5rem;">
            <i class="fas fa-user-shield me-2"></i>Admin Profile
        </h1>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h2>{{ Auth::user()->name }}</h2>
                <p class="text-muted">{{ Auth::user()->email }}</p>
                <span class="admin-badge">
                    <i class="fas fa-crown me-1"></i>Administrator
                </span>
            </div>

            {{-- Profile Information Section --}}
            <div class="form-section">
                <h4 class="section-title">
                    <i class="fas fa-user-edit me-2"></i>Profile Information
                </h4>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', Auth::user()->name) }}" 
                                   required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', Auth::user()->email) }}" 
                                   required>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>

            {{-- Password Section --}}
            <div class="form-section">
                <h4 class="section-title">
                    <i class="fas fa-lock me-2"></i>Change Password
                </h4>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" 
                               class="form-control" 
                               id="current_password" 
                               name="current_password" 
                               required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   required>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key me-2"></i>Update Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- Account Info Section --}}
            <div class="form-section">
                <h4 class="section-title">
                    <i class="fas fa-info-circle me-2"></i>Account Information
                </h4>
                
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Account Type:</strong> Administrator</p>
                        <p><strong>Account Status:</strong> <span class="text-success">Active</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Member Since:</strong> {{ Auth::user()->created_at->format('F j, Y') }}</p>
                        <p><strong>Last Updated:</strong> {{ Auth::user()->updated_at->format('F j, Y') }}</p>
                    </div>
                </div>

                <div class="mt-3 text-end">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
