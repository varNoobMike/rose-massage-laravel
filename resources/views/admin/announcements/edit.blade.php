@extends('layouts.admin')

@section('page-title', 'Edit Announcement')

@section('breadcrumb-parent', 'Announcements')
@section('breadcrumb-parent-url', route('announcements.index'))

@section('page-header', true)
@section('page-header-title-showpage', 'Edit Announcement #' . $announcement->id)
@section('page-header-subtitle', 'Update, manage, this announcement')

@section('content')
    <form action="{{ route('announcements.update', $announcement->id) }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- LEFT SIDE --}}
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
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase"
                                        style="width:30%;">
                                        Title
                                    </td>

                                    <td class="py-4 pe-4">
                                        <input type="text"
                                               name="title"
                                               class="form-control border-2 @error('title') is-invalid @enderror"
                                               value="{{ old('title', $announcement->title) }}"
                                               placeholder="Enter announcement title">

                                        @error('title')
                                            <div class="small text-danger mt-1 fw-bold">
                                                {{ $message }}
                                            </div>
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
                                                  placeholder="Write announcement message...">{{ old('message', $announcement->message) }}</textarea>

                                        @error('message')
                                            <div class="small text-danger mt-1 fw-bold">
                                                {{ $message }}
                                            </div>
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

                                            <option value="promo"
                                                {{ old('type', $announcement->type) == 'promo' ? 'selected' : '' }}>
                                                Promo
                                            </option>

                                            <option value="update"
                                                {{ old('type', $announcement->type) == 'update' ? 'selected' : '' }}>
                                                Update
                                            </option>

                                            <option value="alert"
                                                {{ old('type', $announcement->type) == 'alert' ? 'selected' : '' }}>
                                                Alert
                                            </option>

                                            <option value="info"
                                                {{ old('type', $announcement->type) == 'info' ? 'selected' : '' }}>
                                                Info
                                            </option>

                                        </select>

                                        @error('type')
                                            <div class="small text-danger mt-1 fw-bold">
                                                {{ $message }}
                                            </div>
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

            {{-- RIGHT SIDE --}}
            <div class="col-12 col-lg-4">

                <!-- STATUS -->
                <div class="card shadow-sm border mb-4 text-center">
                    <div class="card-body p-4">

                        <small class="text-uppercase text-muted fw-bold d-block mb-3">
                            Announcement Status
                        </small>

                        <select name="is_active"
                                class="form-select border-2 fw-bold text-center @error('is_active') is-invalid @enderror">

                            <option value="1"
                                {{ old('is_active', $announcement->is_active) == 1 ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="0"
                                {{ old('is_active', $announcement->is_active) == 0 ? 'selected' : '' }}>
                                Inactive
                            </option>
                        </select>

                        @error('is_active')
                            <div class="small text-danger mt-1 fw-bold">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>

                <!-- Cover Image -->
                <div class="card shadow-sm border mb-4">
                    <div class="card-header bg-white py-3 border-bottom text-center">
                        <h6 class="mb-0 fw-bold small text-muted text-uppercase">
                            Cover Image
                        </h6>
                    </div>

                    <div class="card-body p-3 text-center">

                        <div id="coverImageWrapper"
                             class="bg-light d-flex align-items-center justify-content-center mb-3 rounded-3"
                             style="height:180px; overflow:hidden;">

                            @if (!empty($announcement?->cover_image))
                                <img id="coverPreview"
                                     src="{{ asset('storage/' . $announcement->cover_image) }}"
                                     class="w-100 h-100 object-fit-cover rounded-3">
                            @else
                                <div id="coverFallback"
                                     class="text-muted d-flex align-items-center justify-content-center w-100 h-100 rounded-3">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            @endif

                        </div>

                        <label class="form-label small fw-bold text-uppercase text-muted">
                            Upload Image
                        </label>

                        <input type="file"
                               name="cover_image"
                               id="coverImageInput"
                               class="form-control form-control-sm border-2 @error('cover_image') is-invalid @enderror">

                        @error('cover_image')
                            <div class="small text-danger mt-1 fw-bold">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>
                </div>

                <!-- SCHEDULE -->
                <div class="card shadow-sm border">
                    <div class="card-header bg-white py-3 border-bottom text-center">
                        <h6 class="mb-0 fw-bold small text-muted text-uppercase">
                            Schedule Settings
                        </h6>
                    </div>

                    <div class="card-body p-3">

                        <!-- START -->
                        <label class="form-label small fw-bold text-uppercase text-muted">
                            Starts At
                        </label>

                        <input type="datetime-local"
                               name="starts_at"
                               class="form-control border-2 mb-3"
                               value="{{ old('starts_at', optional($announcement->starts_at)->format('Y-m-d\TH:i')) }}">

                        <!-- END -->
                        <label class="form-label small fw-bold text-uppercase text-muted">
                            Ends At
                        </label>

                        <input type="datetime-local"
                               name="ends_at"
                               class="form-control border-2"
                               value="{{ old('ends_at', optional($announcement->ends_at)->format('Y-m-d\TH:i')) }}">

                    </div>
                </div>
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="card shadow-sm border mt-4">
            <div class="card-body">

                <div class="d-flex flex-column flex-md-row gap-2 justify-content-end">

                    <a href="{{ route('announcements.show', $announcement->id) }}"
                       class="btn btn-outline-secondary px-4 shadow-sm">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary px-4 fw-bold shadow-sm">
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

        let original = "{{ !empty($announcement->cover_image) ? asset('storage/' . $announcement->cover_image) : '' }}";

        if (original) {

            $('#coverImageWrapper').html(`
                <img id="coverPreview"
                     src="${original}"
                     class="w-100 h-100 object-fit-cover rounded-3">
            `);

        } else {

            $('#coverImageWrapper').html(`
                <div id="coverFallback"
                     class="text-muted d-flex align-items-center justify-content-center w-100 h-100 rounded-3">
                    <i class="bi bi-image fs-1"></i>
                </div>
            `);
        }
    }

});
</script>
@endsection