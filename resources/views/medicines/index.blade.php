<!DOCTYPE html>
<html>
<head>
    <title>All Medicines</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 5px 10px;
            text-decoration: none;
            color: white;
            border-radius: 4px;
            margin-right: 5px;
        }
        .btn-edit { background-color: #007bff; }
        .btn-delete { background-color: #dc3545; }
        .btn-add { background-color: #28a745; }
    </style>
</head>
<body>
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

    <h1 style="text-align:center;">All Medicines</h1>

    {{-- ✅ Success Message --}}
    @if(session('success'))
        <p style="color: green; text-align:center;">{{ session('success') }}</p>
    @endif

    {{-- ✅ Table --}}
    @if($medicines->count() > 0)
        <table>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            @foreach($medicines as $medicine)
            <tr>
                <td>{{ $medicine->id }}</td>
                <td>
                    @if($medicine->image)
                        <img src="{{ asset($medicine->image) }}" alt="{{ $medicine->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                    @else
                        <div style="width: 50px; height: 50px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 8px; color: #6c757d; font-size: 12px;">No Image</div>
                    @endif
                </td>
                <td>{{ $medicine->name }}</td>
                <td>{{ $medicine->brand }}</td>
                <td>{{ $medicine->price }}</td>
                <td>{{ $medicine->stock }}</td>
                <td>{{ $medicine->description }}</td>
                <td>
                    {{-- ✅ Edit Button (Correct Route) --}}
                    <a href="{{ route('medicines.edit', ['medicine' => $medicine->id]) }}" class="btn btn-edit">Edit</a>

                    {{-- ✅ Delete Button (Correct Route) --}}
                    <form action="{{ route('medicines.destroy', ['medicine' => $medicine->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this medicine?');">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    @else
        <p style="text-align:center;">No medicines found.</p>
    @endif

    <br>
    {{-- ✅ Add New Medicine Button --}}
    <div style="text-align:center;">
        <a href="{{ route('medicines.create') }}" class="btn btn-add">+ Add New Medicine</a>
    </div>
</body>
</html>
