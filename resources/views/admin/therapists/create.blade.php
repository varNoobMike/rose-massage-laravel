@extends('layouts.admin')

@section('page-title', 'Create Therapist')

@section('breadcrumb-parent', 'Therapists')
@section('breadcrumb-parent-url', route('therapists.index'))

@section('page-header', true)
@section('page-header-title-showpage', 'Create Therapist')
@section('page-header-subtitle', 'Create new therapist record')

@section('content')
    <form action="{{ route('therapists.store') }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="row g-4">

            <!-- LEFT -->
            <div class="col-lg-8">

                <div class="card shadow-sm border">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                            Receptionist Information
                        </h6>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-borderless mb-0 align-middle">
                            <tbody>

                                <!-- Name -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Full Name
                                    </td>
                                    <td class="py-4 pe-4">
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}">

                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Email -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Email
                                    </td>
                                    <td class="py-4 pe-4">
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}">

                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- Phone -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Phone
                                    </td>
                                    <td class="py-4 pe-4">
                                        <input type="text" name="phone_number" class="form-control"
                                            value="{{ old('phone_number') }}">
                                    </td>
                                </tr>

                                <!-- Address -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Address
                                    </td>
                                    <td class="py-4 pe-4">
                                        <textarea name="address" rows="3" class="form-control">{{ old('address') }}</textarea>
                                    </td>
                                </tr>

                                <!-- Gender -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Gender
                                    </td>
                                    <td class="py-4 pe-4">
                                        <select name="gender" class="form-select">
                                            <option value="">Select Gender</option>
                                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                                Male
                                            </option>
                                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                                Female
                                            </option>
                                        </select>
                                    </td>
                                </tr>

                                <!-- Birthdate -->
                                <tr>
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Birthdate
                                    </td>
                                    <td class="py-4 pe-4">
                                        <input type="date" name="birthdate" class="form-control"
                                            value="{{ old('birthdate') }}">
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- RIGHT -->
            <div class="col-lg-4">

                <!-- Status -->
                <div class="card shadow-sm border mb-4">
                    <div class="card-body">
                        <small class="text-uppercase text-muted fw-bold d-block mb-3">
                            Work Status
                        </small>

                        <select name="status" class="form-select @error('status') is-invalid @enderror">

                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
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
                <div class="card shadow-sm border">
                    <div class="card-header bg-white py-3 border-bottom text-center">
                        <h6 class="mb-0 fw-bold small text-muted text-uppercase">
                            Profile Image
                        </h6>
                    </div>

                    <div class="card-body text-center">

                        <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                            style="width:150px;height:150px;">
                            <i class="bi bi-person fs-1 text-muted"></i>
                        </div>

                        <input type="file" name="image"
                            class="form-control form-control-sm @error('image') is-invalid @enderror">

                        @error('image')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        {{-- GLOBAL ACTION BUTTONS --}}
        <div class="card shadow-sm border mt-4">
            <div class="card-body">

                <div class="d-flex flex-column flex-md-row gap-2 justify-content-end">

                    <a href="{{ route('therapists.create') }}" class="btn btn-outline-secondary px-4 shadow-sm">
                        Reset Changes
                    </a>

                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-2"></i>
                        Save Therapist
                    </button>

                </div>

            </div>
        </div>

    </form>
@endsection
