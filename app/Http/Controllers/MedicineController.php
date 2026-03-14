<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;

class MedicineController extends Controller
{
    public function index()
    {
        $medicines = Medicine::all(); // sab medicines le lo
        return view('medicines.index', compact('medicines')); // page par bhej do
    }

    public function create()
{
    return view('medicines.create');
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'brand' => 'required|string|max:255',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $data = [
        'name' => $request->name,
        'brand' => $request->brand,
        'price' => $request->price,
        'stock' => $request->stock,
        'description' => $request->description,
    ];

    // Handle image upload
    if ($request->hasFile('image')) {
        // Create directory if not exists
        if (!file_exists(public_path('images/medicines'))) {
            mkdir(public_path('images/medicines'), 0777, true);
        }
        
        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images/medicines'), $imageName);
        $data['image'] = 'images/medicines/' . $imageName;
    }

    Medicine::create($data);

return redirect()->route('medicines.index')->with('success', 'Medicine added successfully!');
    
}
  // ---------- EDIT ----------
 public function edit(Medicine $medicine)
    {
        return view('medicines.edit', compact('medicine'));
    }

    // ---------- UPDATE ----------
    public function update(Request $request, Medicine $medicine)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['name','brand','price','stock','description']);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($medicine->image && file_exists(public_path($medicine->image))) {
                unlink(public_path($medicine->image));
            }
            
            // Create directory if not exists
            if (!file_exists(public_path('images/medicines'))) {
                mkdir(public_path('images/medicines'), 0777, true);
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/medicines'), $imageName);
            $data['image'] = 'images/medicines/' . $imageName;
        }

        $medicine->update($data);

        return redirect()->route('medicines.index')->with('success', 'Medicine updated successfully!');
    }

    // ---------- DESTROY ----------
    public function destroy(Medicine $medicine)
    {
        // Delete image if exists
        if ($medicine->image && file_exists(public_path($medicine->image))) {
            unlink(public_path($medicine->image));
        }
        
        $medicine->delete();
        return redirect()->route('medicines.index')->with('success', 'Medicine deleted successfully!');
    }

}
