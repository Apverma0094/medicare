<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;
        $addresses = $user->addresses;
        
        // Return admin-specific profile view if user is admin
        if ($user->role === 'admin') {
            return view('admin.profile', compact('user', 'profile', 'addresses'));
        }
        
        return view('user.profile', compact('user', 'profile', 'addresses'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->fill($request->only(['name', 'email']));
        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user's detailed profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'phone' => ['nullable', 'string', 'max:15'],
            'date_of_birth' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'blood_group' => ['nullable', 'string', 'max:5'],
            'medical_conditions' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'current_medications' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:15'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:50'],
        ]);

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only([
                'phone', 'date_of_birth', 'gender', 'blood_group',
                'medical_conditions', 'allergies', 'current_medications',
                'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation'
            ])
        );

        return back()->with('success', 'Personal details updated successfully!');
    }

    /**
     * Store a new address for the user.
     */
    public function storeAddress(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:10'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:home,office,other'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        // If this is set as default, unset all other defaults
        if ($request->boolean('is_default')) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create($request->only([
            'label', 'name', 'phone', 'address', 'city', 'state', 'pincode',
            'landmark', 'type', 'is_default'
        ]));

        return back()->with('success', 'Address added successfully!');
    }

    /**
     * Update an existing address.
     */
    public function updateAddress(Request $request, UserAddress $address)
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:15'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:10'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:home,office,other'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        // If this is set as default, unset all other defaults
        if ($request->boolean('is_default')) {
            Auth::user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($request->only([
            'label', 'name', 'phone', 'address', 'city', 'state', 'pincode',
            'landmark', 'type', 'is_default'
        ]));

        return back()->with('success', 'Address updated successfully!');
    }

    /**
     * Delete an address.
     */
    public function deleteAddress(UserAddress $address)
    {
        // Ensure the address belongs to the authenticated user
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $address->delete();

        return back()->with('success', 'Address deleted successfully!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors([
                'current_password' => 'The provided password does not match your current password.',
            ]);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Account deleted successfully.');
    }
}
