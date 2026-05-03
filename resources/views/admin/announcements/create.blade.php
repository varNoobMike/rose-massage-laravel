@extends('layouts.admin')

@section('page-title', 'Create Announcement')

@section('breadcrumb-parent', 'Announcements')
@section('breadcrumb-parent-url', route('announcements.index'))

@section('page-header', true)
@section('page-header-title-showpage', 'Announcements')
@section('page-header-subtitle', 'Manage client announcements')

@section('content')

    <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf

        <div class="row g-4">

            {{-- LEFT --}}
            <div class="col-12 col-lg-8">

                <div class="card shadow-sm border">

                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                            Announcement Details
                        </h6>
                    </div>

                    <div class="card-body p-0">

                        <table class="table table-borderless mb-0 align-middle">
                            <tbody>

                                <!-- TITLE -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase" style="width:30%;">
                                        Title
                                    </td>
                                    <td class="py-4 pe-4">
                                        <input type="text"
                                               name="title"
                                               class="form-control text-capitalize border-2 @error('title') is-invalid @enderror"
                                               value="{{ old('title') }}"
                                               placeholder="e.g. Holiday Schedule Notice">

                                        @error('title')
                                            <div class="small text-danger mt-1 fw-bold">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- MESSAGE -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Message
                                    </td>
                                    <td class="py-4 pe-4">
                                        <textarea name="message"
                                                  rows="5"
                                                  class="form-control border-2 @error('message') is-invalid @enderror"
                                                  placeholder="Write your announcement...">{{ old('message') }}</textarea>

                                        @error('message')
                                            <div class="small text-danger mt-1 fw-bold">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- TYPE -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Type
                                    </td>
                                    <td class="py-4 pe-4">
                                        <select name="type"
                                                class="form-select border-2 @error('type') is-invalid @enderror">

                                            <option value="promo" {{ old('type') == 'promo' ? 'selected' : '' }}>Promo</option>
                                            <option value="update" {{ old('type') == 'update' ? 'selected' : '' }}>Update</option>
                                            <option value="alert" {{ old('type') == 'alert' ? 'selected' : '' }}>Alert</option>
                                            <option value="info" {{ old('type') == 'info' ? 'selected' : '' }}>Info</option>

                                        </select>

                                        @error('type')
                                            <div class="small text-danger mt-1 fw-bold">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <!-- LINK PAGE -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Link Page
                                    </td>
                                    <td class="py-4 pe-4">
                                        <select name="link_page"
                                                class="form-select border-2 @error('link_page') is-invalid @enderror">
                                            <option value="" {{ old('link_page') == '' ? 'selected' : '' }}>-- Select --</option>
                                            <option value="bookings" {{ old('link_page') == 'bookings' ? 'selected' : '' }}>Bookings</option>
                                            <option value="services" {{ old('link_page') == 'services' ? 'selected' : '' }}>Services</option>
                                        </select>

                                        @error('link_page')
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
                            Status
                        </small>

                        <select name="is_active"
                                class="form-select border-2 fw-bold text-center @error('is_active') is-invalid @enderror">

                            <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                        @error('is_active')
                            <div class="small text-danger mt-1 fw-bold">{{ $message }}</div>
                        @enderror

                    </div>
                </div>

                <!-- Cover Image -->
                <div class="card shadow-sm border mb-4">
                    <div class="card-header bg-white py-3 border-bottom text-center">
                        <h6 class="mb-0 fw-bold small text-muted text-uppercase">
                            Marketing Image
                        </h6>
                    </div>

                    <div class="card-body p-3 text-center">

                        <!-- Preview Box (same as Service) -->
                        <div id="coverImageWrapper"
                             class="bg-light d-flex align-items-center justify-content-center mb-3 rounded-3"
                             style="height:180px; overflow:hidden;">

                            <div id="coverFallback"
                                 class="text-muted d-flex align-items-center justify-content-center w-100 h-100">
                                <i class="bi bi-image fs-1"></i>
                            </div>

                        </div>

                        <!-- Label (same as Service) -->
                        <label class="form-label small fw-bold text-uppercase text-muted">
                            Upload Image
                        </label>

                        <!-- Input -->
                        <input type="file"
                               name="cover_image"
                               id="coverImageInput"
                               class="form-control form-control-sm @error('cover_image') is-invalid @enderror">

                        @error('cover_image')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                    
                </div>

                <!-- SCHEDULE -->
                <div class="card shadow-sm border">

                    <div class="card-header bg-white py-3 border-bottom text-center">
                        <h6 class="mb-0 fw-bold small text-muted text-uppercase">
                            Schedule (Optional)
                        </h6>
                    </div>

                    <div class="card-body p-3">

                        <!-- START -->
                        <label class="form-label small fw-bold text-uppercase text-muted">
                            Starts At
                        </label>
                        <input type="datetime-local"
                               name="starts_at"
                               class="form-control border-2 mb-3 @error('starts_at') is-invalid @enderror"
                               value="{{ old('starts_at') }}">

                        @error('starts_at')
                            <div class="small text-danger mt-1 fw-bold">{{ $message }}</div>
                        @enderror

                        <!-- END -->
                        <label class="form-label small fw-bold text-uppercase text-muted">
                            Ends At
                        </label>
                        <input type="datetime-local"
                               name="ends_at"
                               class="form-control border-2 @error('ends_at') is-invalid @enderror"
                               value="{{ old('ends_at') }}">

                        @error('ends_at')
                            <div class="small text-danger mt-1 fw-bold">{{ $message }}</div>
                        @enderror

                    </div>
                </div>

            </div>

        </div>

        {{-- ACTIONS --}}
        <div class="card shadow-sm border mt-4">
            <div class="card-body">

                <div class="d-flex flex-column flex-md-row gap-2 justify-content-end">

                    <a href="{{ route('announcements.create') }}"
                       class="btn btn-outline-secondary px-4 shadow-sm">
                        Reset
                    </a>

                    <button type="submit"
                            class="btn btn-primary px-4 fw-bold shadow-sm">
                        <i class="bi bi-check2-circle me-2"></i>
                        Publish Announcement
                    </button>

                </div>

            </div>
        </div>

    </form>

@endsection


@section('page-scripts')
<script>
$(document).ready(function () {

    $('#coverImageInput').on('change', function (e) {

        let file = e.target.files[0];

        if (!file) {
            resetCoverImage();
            return;
        }

        let reader = new FileReader();

        reader.onload = function (e) {

            $('#coverImageWrapper').html(`
                <img id="coverPreview"
                     src="${e.target.result}"
                     class="w-100 h-100 object-fit-cover rounded-3">
            `);
        };

        reader.readAsDataURL(file);
    });

    function resetCoverImage() {

        $('#coverImageInput').val('');

        $('#coverImageWrapper').html(`
            <div id="coverFallback"
                 class="text-muted d-flex align-items-center justify-content-center w-100 h-100">
                <i class="bi bi-image fs-1"></i>
            </div>
        `);
    }

});
</script>
@endsection