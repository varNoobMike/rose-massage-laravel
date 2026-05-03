@extends('layouts.admin')

@section('page-title', 'Edit')

@section('breadcrumb-parent', 'Service #' . $service->id)
@section('breadcrumb-parent-url', route('services.show', $service->id))

@section('page-header', true)
@section('page-header-title-showpage', 'Edit Service #' . $service->id)
@section('page-header-subtitle', 'Update this service')

@section('content')
    <form action="{{ route('services.update', $service->id) }}" id="editServiceForm" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- Left Column --}}
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm border">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-uppercase small text-muted tracking-wider">Service Information</h6>
                            <span class="badge bg-light text-primary border px-3 py-2">ID:
                                #{{ str_pad($service->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0 align-middle">
                                <tbody>
                                    {{-- Name Field --}}
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase" style="width: 30%;">
                                            Treatment Name</td>
                                        <td class="py-4 pe-4">
                                            <input type="text" name="name"
                                                class="form-control border-2 @error('name') is-invalid @enderror"
                                                value="{{ old('name', $service->name) }}">
                                            @error('name')
                                                <div class="invalid-feedback fw-bold small mt-1">{{ $message }}</div>
                                            @enderror
                                        </td>
                                    </tr>
                                    {{-- Rate Field (Fixed Name) --}}
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">Service Rate</td>
                                        <td class="py-4 pe-4">
                                            <div class="input-group">
                                                <span
                                                    class="input-group-text bg-light border-2 fw-bold text-primary">₱</span>
                                                <input type="number" step="0.01" name="rate"
                                                    class="form-control border-2 fw-bold font-monospace @error('rate') is-invalid @enderror"
                                                    value="{{ old('rate', $service->price) }}">
                                            </div>
                                            @error('rate')
                                                <div class="small text-danger mt-1 fw-bold">{{ $message }}</div>
                                            @enderror
                                        </td>
                                    </tr>
                                    {{-- Duration Field (Fixed Name) --}}
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">Time Allocation</td>
                                        <td class="py-4 pe-4">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-2"><i
                                                        class="bi bi-clock-history text-primary"></i></span>
                                                <input type="number" name="duration"
                                                    class="form-control border-2 @error('duration') is-invalid @enderror"
                                                    value="{{ old('duration', $service->duration_minutes) }}">
                                                <span class="input-group-text bg-light border-2 small fw-bold">MINS</span>
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
            </div>

            {{-- Right Column --}}
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border mb-4 text-center">
                    <div class="card-body p-4">
                        <small class="text-uppercase text-muted fw-bold mb-3 d-block tracking-wider">Visibility
                            Status</small>
                        <select name="status" class="form-select border-2 fw-bold text-center">
                            <option value="active" {{ old('status', $service->status) === 'active' ? 'selected' : '' }}>
                                Active</option>
                            <option value="inactive" {{ old('status', $service->status) === 'inactive' ? 'selected' : '' }}>
                                Inactive</option>
                        </select>
                        @error('status')
                            <div class="small text-danger mt-1 fw-bold">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card shadow-sm border overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom text-center">
                        <h6 class="mb-0 fw-bold small text-muted text-uppercase tracking-wider">Marketing Image</h6>
                    </div>
                    <div class="card-body p-3 text-center">

                        <div id="imagePreviewWrapper" 
                            class="bg-light d-flex align-items-center justify-content-center mb-3"
                            style="height:180px; overflow:hidden;">

                            @if ($service->image)
                                <img id="imagePreview"
                                     src="{{ asset('storage/' . $service->image) }}"
                                     class="w-100 h-100 object-fit-cover rounded-3">
                            @else
                                <div id="imageFallback"
                                     class="text-muted d-flex align-items-center justify-content-center w-100 h-100 rounded-3">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            @endif

                        </div>

                        <label class="form-label small fw-bold text-uppercase text-muted">
                            Upload New Image
                        </label>

                        <input type="file"
                               name="image"
                               id="serviceImageInput"
                               class="form-control form-control-sm border-2 @error('image') is-invalid @enderror">

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

                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-outline-secondary px-4 shadow-sm">
                        Reset Changes
                    </a>

                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                        <i class="bi bi-save me-2"></i>
                        Save Changes
                    </button>

                </div>

            </div>
        </div>

    </form>
@endsection


@section('page-scripts')
<script>
$(document).ready(function () {

    $('#serviceImageInput').on('change', function (e) {

        let file = e.target.files[0];

        if (!file) {
            resetImagePreview();
            return;
        }

        let reader = new FileReader();

        reader.onload = function (e) {

            $('#imagePreviewWrapper').html(`
                <img id="imagePreview"
                     src="${e.target.result}"
                     class="w-100 h-100 object-fit-cover rounded-3">
            `);
        };

        reader.readAsDataURL(file);
    });

    function resetImagePreview() {

        $('#serviceImageInput').val('');

        let original = "{{ $service->image ? asset('storage/' . $service->image) : '' }}";

        if (original) {

            $('#imagePreviewWrapper').html(`
                <img id="imagePreview"
                     src="${original}"
                     class="w-100 h-100 object-fit-cover rounded-3">
            `);

        } else {

            $('#imagePreviewWrapper').html(`
                <div id="imageFallback"
                     class="text-muted d-flex align-items-center justify-content-center w-100 h-100 rounded-3">
                    <i class="bi bi-image fs-1"></i>
                </div>
            `);
        }
    }

});
</script>
@endsection