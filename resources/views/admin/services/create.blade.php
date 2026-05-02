@extends('layouts.admin')

@section('page-title', 'Create Service')

@section('breadcrumb-parent', 'Services')
@section('breadcrumb-parent-url', route('services.index'))

@section('page-header', true)
@section('page-header-title-showpage', 'Create Service')
@section('page-header-subtitle', 'Create new service')

@section('content')
    <form action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

            {{-- LEFT --}}
            <div class="col-12 col-lg-8">

                <div class="card shadow-sm border">

                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                            Service Information
                        </h6>
                    </div>

                    <div class="card-body p-0">

                        <table class="table table-borderless mb-0 align-middle">
                            <tbody>

                                <!-- NAME -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase" style="width:30%;">
                                        Treatment Name
                                    </td>
                                    <td class="py-4 pe-4">
                                        <input type="text" name="name"
                                            class="form-control text-capitalize border-2 @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" placeholder="e.g. Swedish Massage">

                                        @error('name')
                                            <div class="small text-danger mt-1 fw-bold">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- RATE -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Service Rate
                                    </td>
                                    <td class="py-4 pe-4">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-2 fw-bold text-primary">₱</span>
                                            <input type="number" step="0.01" name="rate"
                                                class="form-control border-2 @error('rate') is-invalid @enderror"
                                                value="{{ old('rate') }}" placeholder="0.00">
                                        </div>

                                        @error('rate')
                                            <div class="small text-danger mt-1 fw-bold">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- DURATION -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Duration
                                    </td>
                                    <td class="py-4 pe-4">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-2">
                                                <i class="bi bi-clock text-primary"></i>
                                            </span>

                                            <input type="number" name="duration"
                                                class="form-control border-2 @error('duration') is-invalid @enderror"
                                                value="{{ old('duration') }}" placeholder="Minutes">

                                            <span class="input-group-text bg-light border-2 small fw-bold">
                                                MINS
                                            </span>
                                        </div>

                                        @error('duration')
                                            <div class="small text-danger mt-1 fw-bold">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                            </tbody>
                        </table>

                    </div>
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="col-12 col-lg-4">

                <!-- STATUS -->
                <div class="card shadow-sm border mb-4 text-center">
                    <div class="card-body p-4">

                        <small class="text-uppercase text-muted fw-bold d-block mb-3">
                            Visibility Status
                        </small>

                        <select name="status"
                            class="form-select border-2 fw-bold text-center @error('status') is-invalid @enderror">

                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('status')
                            <div class="small text-danger mt-1 fw-bold">{{ $message }}</div>
                        @enderror

                    </div>
                </div>

                <!-- IMAGE -->
                <div class="card shadow-sm border overflow-hidden">

                    <div class="card-header bg-white py-3 border-bottom text-center">
                        <h6 class="mb-0 fw-bold small text-muted text-uppercase">
                            Marketing Image
                        </h6>
                    </div>

                    <div class="card-body p-3">

                        <div class="mb-3 text-center text-muted">
                            <i class="bi bi-image fs-1 opacity-25"></i>
                            <p class="small mb-0">No image selected</p>
                        </div>

                        <label class="form-label small fw-bold text-uppercase text-muted">
                            Upload Image
                        </label>

                        <input type="file" name="image"
                            class="form-control border-2 @error('image') is-invalid @enderror">

                        @error('image')
                            <div class="small text-danger mt-1 fw-bold">{{ $message }}</div>
                        @enderror

                    </div>
                </div>

            </div>

        </div>

        {{-- GLOBAL ACTION BUTTONS --}}
        <div class="card shadow-sm border mt-4">
            <div class="card-body">

                <div class="d-flex flex-column flex-md-row gap-2 justify-content-end">

                    <a href="{{ route('services.create') }}" class="btn btn-outline-secondary px-4 shadow-sm">
                        Reset Changes
                    </a>

                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-2"></i>
                        Save Service
                    </button>

                </div>

            </div>
        </div>

    </form>
@endsection
