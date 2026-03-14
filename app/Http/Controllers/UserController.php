<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\User;
use App\Models\Cart;

class UserController extends Controller
{
    // All medicines show karne ke liye
    public function index()
    {
        $medicines = Medicine::where('stock', '>', 0)->get(); // Only show medicines with stock
        return view('user.shop', compact('medicines'));
    }

    // Single medicine details
    public function show($id)
    {
        $medicine = Medicine::findOrFail($id);
        $userCartCount = Cart::where('user_id', auth()->id())->sum('quantity');
        return view('user.show', compact('medicine', 'userCartCount'));
    }

    // Admin: All users list (New Method)
    public function adminUsersList()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        $totalUsers = $users->count();
        $adminUsers = $users->where('role', 'admin')->count();
        $clientUsers = $users->where('role', 'client')->count();
        
        return view('admin.users', compact('users', 'totalUsers', 'adminUsers', 'clientUsers'));
    }

    // Admin: Single user details (New Method) 
    public function adminUserShow($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user-details', compact('user'));
    }
}
