@extends('layouts.admin')

@section('page-title', 'Edit')

@section('breadcrumb-parent', 'User #' . $user->id)
@section('breadcrumb-parent-url', route('users.show', $user->id))

@section('content')
<div class="container-fluid">

    <form action="{{ route('users.update', $user->id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <h2 class="fw-bold text-dark mb-0 h4">
                <i class="bi bi-pencil-square text-primary me-2"></i>
                Edit User #{{ $user->id }}
            </h2>
        </div>

        <div class="row g-4">

            <!-- Alerts -->
            @if(session('success'))
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show shadow-sm rounded">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif


            <!-- LEFT SIDE -->
            <div class="col-12 col-lg-8">

                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                                User Information
                            </h6>

                            <span class="badge bg-light text-primary border px-3 py-2">
                                ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0 align-middle">
                                <tbody>

                                    <!-- Full Name -->
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase"
                                            style="width:30%;">
                                            Full Name
                                        </td>
                                        <td class="py-4 pe-4">
                                            <input type="text"
                                                   name="name"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name', $user->name) }}">

                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </td>
                                    </tr>

                                    <!-- Email -->
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                            Email
                                        </td>
                                        <td class="py-4 pe-4">
                                            <input type="email"
                                                   name="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   value="{{ old('email', $user->email) }}">

                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </td>
                                    </tr>

                                    <!-- Phone -->
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                            Phone
                                        </td>
                                        <td class="py-4 pe-4">
                                            <input type="text"
                                                   name="phone_number"
                                                   class="form-control @error('phone_number') is-invalid @enderror"
                                                   value="{{ old('phone_number', $user->profile->phone_number) ?? 'N?A' }}">

                                            @error('phone_number')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </td>
                                    </tr>

                                    <!-- Address -->
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                            Address
                                        </td>
                                        <td class="py-4 pe-4">
                                            <textarea name="address"
                                                      rows="3"
                                                      class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->profile->address) }}</textarea>

                                            @error('address')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </td>
                                    </tr>

                                    <!-- Gender -->
                                    <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                                        <option value="male"
                                            {{ old('gender', $user->profile->gender) == 'male' ? 'selected' : '' }}>
                                            Male
                                        </option>

                                        <option value="female"
                                            {{ old('gender', $user->profile->gender) == 'female' ? 'selected' : '' }}>
                                            Female
                                        </option>
                                    </select>

                                    <!-- Birthdate -->
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                            Birthdate
                                        </td>
                                        <td class="py-4 pe-4">

                                            <input type="date"
                                                   name="birthdate"
                                                   class="form-control @error('birthdate') is-invalid @enderror"
                                                   value="{{ old('birthdate', $user->profile->birthdate) }}">

                                            @error('birthdate')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </td>
                                    </tr>

                                    <!-- Password -->
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                            Password
                                        </td>
                                        <td class="py-4 pe-4">

                                            <input type="password"
                                                   name="password"
                                                   class="form-control @error('birthdate') is-invalid @enderror"
                                                   value="{{ old('password') }}"
                                                   placeholder="********">

                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </td>
                                    </tr>

                                    <!-- Password Confirmation -->
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                            Confirm Password
                                        </td>
                                        <td class="py-4 pe-4">

                                            <input type="password"
                                                   name="password_confirmation"
                                                   class="form-control @error('birthdate') is-invalid @enderror"
                                                   value=""
                                                   placeholder="********">

                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </td>
                                    </tr>


                                    <!-- Role -->
                                    <tr>
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                            Role
                                        </td>
                                        <td class="py-4 pe-4">
                                            <select name="role"
                                                    class="form-select @error('role') is-invalid @enderror">

                                                <option value="client"
                                                    {{ old('role', $user->role) == 'client' ? 'selected' : '' }}>
                                                    Client
                                                </option>

                                                <option value="therapist"
                                                    {{ old('role', $user->role) == 'therapist' ? 'selected' : '' }}>
                                                    Therapist
                                                </option>

                                                <option value="receptionist"
                                                    {{ old('role', $user->role) == 'receptionist' ? 'selected' : '' }}>
                                                    Receptionist
                                                </option>

                                                <option value="owner"
                                                    {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>
                                                    Owner
                                                </option>

                                            </select>

                                            @error('role')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>


            <!-- RIGHT SIDE -->
            <div class="col-12 col-lg-4">

                <!-- Status -->
                <div class="card border-0 shadow-sm rounded-3 mb-4 text-center">
                    <div class="card-body p-4">

                        <small class="text-uppercase text-muted fw-bold mb-3 d-block">
                            Account Status
                        </small>

                        <select name="status"
                                class="form-select text-center fw-bold">

                            <option value="active"
                                {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>

                        @error('status')
                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>


                <!-- Profile Image -->
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom text-center">
                        <h6 class="mb-0 fw-bold small text-muted text-uppercase">
                            Profile Image
                        </h6>
                    </div>

                    <div class="card-body p-3 text-center">

                        @if($user->profile->avatar)
                            <img src="{{ asset('storage/' . $user->profile->avatar) }}"
                                class="img-fluid rounded-3 shadow-sm w-100 object-fit-cover"
                                style="height: 250px;">
                        @else
                            <div class="bg-light rounded-3 text-center py-5 border border-dashed">
                                <i class="bi bi-person text-muted fs-1 opacity-25"></i>
                            </div>
                        @endif

                        <div class="text-start">
                            <label class="form-label small fw-bold text-uppercase text-muted">
                                Upload New Image
                            </label>

                            <input type="file"
                                   name="image"
                                   class="form-control form-control-sm @error('image') is-invalid @enderror">

                            @error('image')
                                <div class="text-danger small mt-2">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>

        </div>

        {{-- GLOBAL ACTION BUTTONS --}}
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">

                <div class="d-flex flex-column flex-md-row gap-2 justify-content-end">

                    <a href="{{ route('users.edit', $user->id) }}"
                            class="btn btn-outline-secondary px-4">
                        Reset Changes
                    </a>

                    <button type="submit"
                            class="btn btn-primary px-4 fw-bold">
                        <i class="bi bi-save me-2"></i>
                        Save All Changes
                    </button>

                </div>

            </div>
        </div>


    </form>
</div>
@endsection