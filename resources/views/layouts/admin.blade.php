<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - @yield('title')</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
        }
        .sidebar {
            width: 220px;
            background: #2c3e50;
            height: 100vh;
            color: white;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            margin: 5px 0;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background: #34495e;
        }
        .content {
            margin-left: 240px;
            padding: 20px;
            width: calc(100% - 240px);
        }
        .logout {
            background: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>🩺 Admin Panel</h2>
        <a href="{{ route('admin.dashboard') }}">🏠 Dashboard</a>
        <a href="{{ route('medicines.index') }}">💊 Manage Medicines</a>
        <a href="{{ route('medicines.create') }}">➕ Add Medicine</a>
        <a href="{{ route('profile.edit') }}">👤 Profile</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout" style="width:100%; border:none; color:white; cursor:pointer; padding:10px; border-radius:5px; margin-top:20px;">
                🚪 Logout
            </button>
        </form>
    </div>

    <div class="content">
        @yield('content')
    </div>
</body>
</html>
