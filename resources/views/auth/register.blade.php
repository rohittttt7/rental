@extends('layouts.app')

@section('title', 'Register - EquipZone')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Create Your EquipZone Account</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Account Type -->
                        <div class="mb-4">
                            <label class="form-label">Account Type</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="role" id="customer" value="customer" 
                                               {{ old('role', 'customer') === 'customer' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="customer">
                                            <strong>Customer</strong><br>
                                            <small class="text-muted">Buy or rent machinery</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="role" id="seller" value="seller"
                                               {{ old('role') === 'seller' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="seller">
                                            <strong>Seller</strong><br>
                                            <small class="text-muted">List and sell machinery</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3">Personal Information</h6>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Account & Company Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3">Account Information</h6>
                                
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>

                                <!-- Company Information (for sellers) -->
                                <div id="company-fields" style="display: none;">
                                    <h6 class="mb-3 text-warning">Company Information</h6>
                                    
                                    <div class="mb-3">
                                        <label for="company_name" class="form-label">Company Name</label>
                                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                               id="company_name" name="company_name" value="{{ old('company_name') }}">
                                        @error('company_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="company_address" class="form-label">Company Address</label>
                                        <textarea class="form-control @error('company_address') is-invalid @enderror" 
                                                  id="company_address" name="company_address" rows="3">{{ old('company_address') }}</textarea>
                                        @error('company_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Create Account
                            </button>
                        </div>
                    </form>

                    <hr>

                    <div class="text-center">
                        <p class="mb-0">Already have an account? 
                            <a href="{{ route('login') }}" class="text-decoration-none">Login here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleRadios = document.querySelectorAll('input[name="role"]');
    const companyFields = document.getElementById('company-fields');
    
    function toggleCompanyFields() {
        const selectedRole = document.querySelector('input[name="role"]:checked').value;
        if (selectedRole === 'seller') {
            companyFields.style.display = 'block';
            companyFields.querySelectorAll('input, textarea').forEach(field => {
                field.required = true;
            });
        } else {
            companyFields.style.display = 'none';
            companyFields.querySelectorAll('input, textarea').forEach(field => {
                field.required = false;
            });
        }
    }
    
    roleRadios.forEach(radio => {
        radio.addEventListener('change', toggleCompanyFields);
    });
    
    // Initialize on page load
    toggleCompanyFields();
});
</script>
@endsection