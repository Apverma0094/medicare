<!DOCTYPE html>
<html>
<head>
    <title>Edit Medicine</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
    <h2 class="mb-4">Edit Medicine</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('medicines.update', $medicine) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Medicine Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $medicine->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Brand</label>
            <input type="text" name="brand" class="form-control" value="{{ old('brand', $medicine->brand) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price (₹)</label>
            <input type="number" name="price" step="0.01" class="form-control" value="{{ old('price', $medicine->price) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock" class="form-control" value="{{ old('stock', $medicine->stock) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $medicine->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Medicine Image</label>
            @if($medicine->image)
                <div class="mb-2">
                    <img src="{{ asset($medicine->image) }}" alt="Current Medicine Image" style="max-width: 200px; max-height: 200px; border-radius: 10px;">
                    <p class="text-muted mt-1">Current image</p>
                </div>
            @endif
            <input type="file" name="image" class="form-control" accept="image/*">
            <div class="form-text">Upload a new image to replace current one (Optional). Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB.</div>
        </div>

        <button type="submit" class="btn btn-primary">Update Medicine</button>
        <a href="{{ route('medicines.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
