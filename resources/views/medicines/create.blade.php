<!DOCTYPE html>
<html>
<head>
    <title>Add Medicine</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<style>
        .dashboard-btn {
            position: fixed;
            top: 5px;
            left: 5px;
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            font-weight: bold;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            transition: 0.3s;
        }
        .dashboard-btn:hover {
            background-color: #2980b9;
        }
    </style>
 {{-- ✅ Dashboard Button --}}
    <div style="margin-bottom: 15px;">
        <a href="{{ route('admin.dashboard') }}" class="dashboard-btn">🏠 Go to Dashboard</a>
    </div>

<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Add New Medicine</h2>

    <form action="{{ route('medicines.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Medicine Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Brand</label>
            <input type="text" name="brand" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price (₹)</label>
            <input type="number" name="price" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Medicine Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <div class="form-text">Upload an image of the medicine (Optional). Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB.</div>
        </div>

        <button type="submit" class="btn btn-success">Add Medicine</button>
        <a href="/medicines" class="btn btn-secondary">Back</a>
    </form>
</div>
</body>
</html>
