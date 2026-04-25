@extends('layouts.admin')

@section('page-title', 'Edit')

@section('breadcrumb-parent', 'Service #' . $service->id)
@section('breadcrumb-parent-url', route('services.show', $service->id))

@section('content')
<div class="container-fluid">
    <form action="{{ route('services.update', $service->id) }}" id="editServiceForm" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <h2 class="fw-bold text-dark mb-0 h4">
                <i class="bi bi-pencil-square text-primary me-2"></i>Edit Service # {{ $service->id }}
            </h2>
        </div>

        <div class="row g-4">

            <!-- Alert -->
            @if(session('success'))
                    <div class="col-12">  
                        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
            @endif

            @if(session('error'))
                    <div class="col-12">  
                        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
            @endif

            {{-- Left Column --}}
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-uppercase small text-muted tracking-wider">Treatment Specifications</h6>
                            <span class="badge bg-light text-primary border px-3 py-2">ID: #{{ str_pad($service->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0 align-middle">
                                <tbody>
                                    {{-- Name Field --}}
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase" style="width: 30%;">Treatment Name</td>
                                        <td class="py-4 pe-4">
                                            <input type="text" name="name" class="form-control border-2 @error('name') is-invalid @enderror" 
                                                value="{{ old('name', $service->name) }}">
                                            @error('name') <div class="invalid-feedback fw-bold small mt-1">{{ $message }}</div> @enderror
                                        </td>
                                    </tr>
                                    {{-- Rate Field (Fixed Name) --}}
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">Service Rate</td>
                                        <td class="py-4 pe-4">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-2 fw-bold text-primary">₱</span>
                                                <input type="number" step="0.01" name="rate" class="form-control border-2 fw-bold font-monospace @error('rate') is-invalid @enderror" 
                                                    value="{{ old('rate', $service->price) }}">
                                            </div>
                                            @error('rate') <div class="small text-danger mt-1 fw-bold">{{ $message }}</div> @enderror
                                        </td>
                                    </tr>
                                    {{-- Duration Field (Fixed Name) --}}
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">Time Allocation</td>
                                        <td class="py-4 pe-4">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-2"><i class="bi bi-clock-history text-primary"></i></span>
                                                <input type="number" name="duration" class="form-control border-2 @error('duration') is-invalid @enderror" 
                                                    value="{{ old('duration', $service->duration_minutes) }}">
                                                <span class="input-group-text bg-light border-2 small fw-bold">MINS</span>
                                            </div>
                                            @error('duration') <div class="small text-danger mt-1 fw-bold">{{ $message }}</div> @enderror
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 mb-4 text-center">
                    <div class="card-body p-4">
                        <small class="text-uppercase text-muted fw-bold mb-3 d-block tracking-wider">Visibility Status</small>
                        <select name="status" class="form-select border-2 fw-bold text-center">
                            <option value="active" {{ old('status', $service->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $service->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <div class="small text-danger mt-1 fw-bold">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom text-center">
                        <h6 class="mb-0 fw-bold small text-muted text-uppercase tracking-wider">Marketing Image</h6>
                    </div>
                    <div class="card-body p-3 text-center">
                        @if($service->image)
                            <img src="{{ asset('storage/' . $service->image) }}" class="img-fluid rounded-3 shadow-sm w-100 object-fit-cover mb-3" style="height: 180px;">
                        @endif
                        <div class="text-start">
                            <label class="form-label x-small fw-bold text-uppercase text-muted">Upload New Cover</label>
                            <input type="file" name="image" class="form-control form-control-sm border-2 @error('image') is-invalid @enderror">
                            @error('image') <div class="small text-danger mt-1 fw-bold">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

            </div>
  
        </div>

        {{-- GLOBAL ACTION BUTTONS --}}
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">

                <div class="d-flex flex-column flex-md-row gap-2 justify-content-end">

                    <a href="{{ route('services.edit', $service->id) }}"
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