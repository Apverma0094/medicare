<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profile Settings - MediStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @include('partials.header-styles')
        
        :root {
            --primary-color: #16a085;
            --secondary-color: #27ae60;
            --accent-color: #3498db;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .profile-container {
            margin: 1rem auto;
            max-width: 800px;
        }

        .page-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            text-align: center;
        }

        .page-title {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
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
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 3rem;
        }

        .form-section {
            margin-bottom: 2rem;
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #f1f1f1;
        }

        .section-subtitle {
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.1rem;
        }

        .address-card {
            transition: all 0.3s ease;
        }

        .address-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(22, 160, 133, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(22, 160, 133, 0.4);
        }

        .btn-danger {
            background: var(--danger-color);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    {{-- Professional Header --}}
    @include('partials.header')

    <div class="container profile-container">
        {{-- Page Header --}}
        <div class="page-header">
            <h1 class="page-title"><i class="fas fa-user-edit me-2"></i>Profile Settings</h1>
            <p class="page-subtitle">Manage your account information and preferences</p>
        </div>

        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h2>{{ Auth::user()->name }}</h2>
                <p class="text-muted">{{ Auth::user()->email }}</p>
            </div>

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

            {{-- Personal Details Section --}}
            <div class="form-section">
                <h4 class="section-title">
                    <i class="fas fa-address-card me-2"></i>Personal Details
                </h4>
                <form method="POST" action="{{ route('profile.update.details') }}">
                    @csrf
                    @method('patch')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $profile->phone ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date_of_birth" 
                                   name="date_of_birth" 
                                   value="{{ old('date_of_birth', $profile && $profile->date_of_birth ? $profile->date_of_birth->format('Y-m-d') : '') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control" id="gender" name="gender">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $profile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $profile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $profile->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="blood_group" class="form-label">Blood Group</label>
                            <select class="form-control" id="blood_group" name="blood_group">
                                <option value="">Select Blood Group</option>
                                <option value="A+" {{ old('blood_group', $profile->blood_group ?? '') == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_group', $profile->blood_group ?? '') == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_group', $profile->blood_group ?? '') == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_group', $profile->blood_group ?? '') == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('blood_group', $profile->blood_group ?? '') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('blood_group', $profile->blood_group ?? '') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ old('blood_group', $profile->blood_group ?? '') == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_group', $profile->blood_group ?? '') == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="medical_conditions" class="form-label">Medical Conditions</label>
                        <textarea class="form-control" 
                                  id="medical_conditions" 
                                  name="medical_conditions" 
                                  rows="3" 
                                  placeholder="List any medical conditions (e.g., diabetes, hypertension, asthma)">{{ old('medical_conditions', $profile->medical_conditions ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="allergies" class="form-label">Allergies</label>
                        <textarea class="form-control" 
                                  id="allergies" 
                                  name="allergies" 
                                  rows="3" 
                                  placeholder="List any allergies (e.g., penicillin, nuts, latex)">{{ old('allergies', $profile->allergies ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="current_medications" class="form-label">Current Medications</label>
                        <textarea class="form-control" 
                                  id="current_medications" 
                                  name="current_medications" 
                                  rows="3" 
                                  placeholder="List any medications you're currently taking">{{ old('current_medications', $profile->current_medications ?? '') }}</textarea>
                    </div>

                    {{-- Emergency Contact --}}
                    <h5 class="section-subtitle mt-4 mb-3">
                        <i class="fas fa-phone me-2 text-danger"></i>Emergency Contact
                    </h5>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="emergency_contact_name" class="form-label">Contact Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="emergency_contact_name" 
                                   name="emergency_contact_name" 
                                   value="{{ old('emergency_contact_name', $profile->emergency_contact_name ?? '') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="emergency_contact_phone" class="form-label">Contact Phone</label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="emergency_contact_phone" 
                                   name="emergency_contact_phone" 
                                   value="{{ old('emergency_contact_phone', $profile->emergency_contact_phone ?? '') }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="emergency_contact_relation" class="form-label">Relationship</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="emergency_contact_relation" 
                                   name="emergency_contact_relation" 
                                   value="{{ old('emergency_contact_relation', $profile->emergency_contact_relation ?? '') }}"
                                   placeholder="e.g., Spouse, Parent, Sibling">
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Personal Details
                        </button>
                    </div>
                </form>
            </div>

            {{-- Saved Addresses Section --}}
            <div class="form-section">
                <h4 class="section-title d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-map-marker-alt me-2"></i>Saved Addresses</span>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="fas fa-plus me-2"></i>Add New Address
                    </button>
                </h4>

                @if($addresses && $addresses->count() > 0)
                    <div class="row">
                        @foreach($addresses as $address)
                            <div class="col-md-6 mb-3">
                                <div class="card address-card" style="border-radius: 10px; border: 1px solid #e9ecef;">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="card-title mb-1">{{ $address->label }}</h6>
                                                @if($address->is_default)
                                                    <span class="badge bg-primary">Default</span>
                                                @endif
                                                <span class="badge bg-secondary">{{ ucfirst($address->type) }}</span>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#" onclick="editAddress({{ $address->id }}, '{{ $address->label }}', '{{ $address->name }}', '{{ $address->phone }}', '{{ $address->address }}', '{{ $address->city }}', '{{ $address->state }}', '{{ $address->pincode }}', '{{ $address->landmark }}', '{{ $address->type }}', {{ $address->is_default ? 'true' : 'false' }})">
                                                            <i class="fas fa-edit me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="{{ route('profile.address.delete', $address) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this address?')">
                                                                <i class="fas fa-trash me-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <p class="card-text mb-2">
                                            <strong>{{ $address->name }}</strong><br>
                                            {{ $address->formatted_address }}<br>
                                            Phone: {{ $address->phone }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No saved addresses yet. Add your first address to get started.</p>
                    </div>
                @endif
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

            {{-- Account Actions Section --}}
            <div class="form-section">
                <h4 class="section-title">
                    <i class="fas fa-cogs me-2"></i>Account Actions
                </h4>
                
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Account Status</h6>
                        <p class="text-muted mb-0">Your account is active and in good standing</p>
                    </div>
                    <div>
                        <a href="{{ route('home') }}" class="btn btn-outline-primary me-2">
                            <i class="fas fa-home me-2"></i>Home
                        </a>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="fas fa-trash me-2"></i>Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Address Modal --}}
    <div class="modal fade" id="addAddressModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Add New Address
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('profile.address.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="label" class="form-label">Address Label *</label>
                                <input type="text" class="form-control" id="label" name="label" placeholder="e.g., Home, Office" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">Address Type *</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="home">Home</option>
                                    <option value="office">Office</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Full Address *</label>
                            <textarea class="form-control" id="address" name="address" rows="3" placeholder="House/Flat No., Building Name, Street Name" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="city" name="city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label">State *</label>
                                <input type="text" class="form-control" id="state" name="state" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pincode" class="form-label">PIN Code *</label>
                                <input type="text" class="form-control" id="pincode" name="pincode" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="landmark" class="form-label">Landmark</label>
                                <input type="text" class="form-control" id="landmark" name="landmark" placeholder="Near...">
                            </div>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="is_default" name="is_default">
                            <label class="form-check-label" for="is_default">
                                Set as default address
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Address
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Address Modal --}}
    <div class="modal fade" id="editAddressModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Edit Address
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="editAddressForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_label" class="form-label">Address Label *</label>
                                <input type="text" class="form-control" id="edit_label" name="label" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_type" class="form-label">Address Type *</label>
                                <select class="form-control" id="edit_type" name="type" required>
                                    <option value="home">Home</option>
                                    <option value="office">Office</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="edit_phone" name="phone" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_address" class="form-label">Full Address *</label>
                            <textarea class="form-control" id="edit_address" name="address" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="edit_city" name="city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_state" class="form-label">State *</label>
                                <input type="text" class="form-control" id="edit_state" name="state" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_pincode" class="form-label">PIN Code *</label>
                                <input type="text" class="form-control" id="edit_pincode" name="pincode" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_landmark" class="form-label">Landmark</label>
                                <input type="text" class="form-control" id="edit_landmark" name="landmark">
                            </div>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="edit_is_default" name="is_default">
                            <label class="form-check-label" for="edit_is_default">
                                Set as default address
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Address
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Account Modal --}}
    <div class="modal fade" id="deleteAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Account
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                    <p><strong>This will permanently:</strong></p>
                    <ul>
                        <li>Delete all your personal information</li>
                        <li>Cancel any pending orders</li>
                        <li>Remove your order history</li>
                        <li>Clear your cart</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('profile.destroy') }}" class="d-inline">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i>Delete Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Professional Footer --}}
    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.header-scripts')
    
    <script>
        function editAddress(id, label, name, phone, address, city, state, pincode, landmark, type, isDefault) {
            // Set form action
            document.getElementById('editAddressForm').action = `/profile/address/${id}`;
            
            // Fill form fields
            document.getElementById('edit_label').value = label;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_address').value = address;
            document.getElementById('edit_city').value = city;
            document.getElementById('edit_state').value = state;
            document.getElementById('edit_pincode').value = pincode;
            document.getElementById('edit_landmark').value = landmark || '';
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_is_default').checked = isDefault;
            
            // Show modal
            new bootstrap.Modal(document.getElementById('editAddressModal')).show();
        }
    </script>
</body>
</html>