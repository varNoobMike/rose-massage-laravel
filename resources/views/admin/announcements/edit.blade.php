@extends('layouts.admin')

@section('page-title', 'Edit Announcement')

@section('breadcrumb-parent', 'Announcements')
@section('breadcrumb-parent-url', route('announcements.index'))

@section('content')
<div class="container-fluid">

    <form action="{{ route('announcements.update', $announcement->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <h2 class="fw-bold text-dark mb-0 h4">
                <i class="bi bi-pencil-square text-primary me-2"></i>
                Edit Announcement
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

            {{-- LEFT SIDE --}}
            <div class="col-12 col-lg-8">

                <div class="card border-0 shadow-sm rounded-3">

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

                                <!-- LINK -->
                                <tr>
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        External Link
                                    </td>

                                    <td class="py-4 pe-4">
                                        <input type="url"
                                               name="link_url"
                                               class="form-control border-2 @error('link_url') is-invalid @enderror"
                                               value="{{ old('link_url', $announcement->link_url) }}"
                                               placeholder="https://example.com">

                                        @error('link_url')
                                            <div class="small text-danger mt-1 fw-bold">
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

            {{-- RIGHT SIDE --}}
            <div class="col-12 col-lg-4">

                <!-- STATUS -->
                <div class="card border-0 shadow-sm rounded-3 mb-4 text-center">
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

                <!-- SCHEDULE -->
                <div class="card border-0 shadow-sm rounded-3">
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
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body">

                <div class="d-flex flex-column flex-md-row gap-2 justify-content-end">

                    <a href="{{ route('announcements.show', $announcement->id) }}"
                       class="btn btn-outline-secondary px-4">
                        Cancel
                    </a>

                    <button type="submit"
                            class="btn btn-primary px-4 fw-bold">
                        <i class="bi bi-check2-circle me-2"></i>
                        Update Announcement
                    </button>

                </div>

            </div>
        </div>

    </form>

</div>
@endsection