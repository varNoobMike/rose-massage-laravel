@extends('layouts.admin')

@section('page-title', 'Edit')
@section('breadcrumb-parent', 'Booking #' . $booking->id)
@section('breadcrumb-parent-url', route('bookings.show', $booking->id))

@section('page-header', true)
@section('page-header-title-showpage', 'Edit Booking #' . $booking->id)
@section('page-header-subtitle', 'Update, manage, or reschedule this booking')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        Please fix the errors below.
    </div>
@endif

<form method="POST" action="{{ route('bookings.update', $booking->id) }}" id="bookingEditForm">
    @csrf
    @method('PUT')

    <div class="row g-4">

        {{-- LEFT --}}
        <div class="col-lg-8">

            <div class="card shadow-sm border">
                <div class="card-header bg-white">
                    <h6 class="fw-bold text-uppercase small text-muted mb-0">
                        Booking Information
                    </h6>
                </div>

                <div class="card-body">

                    {{-- CLIENT --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Client</label>
                        <input type="text" class="form-control"
                            value="{{ optional($booking->client)->name }}" disabled>
                    </div>

                    {{-- DATE --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Booking Date</label>
                        <input type="date" name="booking_date"
                            class="form-control @error('booking_date') is-invalid @enderror"
                            value="{{ old('booking_date', $booking->booking_date) }}">

                        @error('booking_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TIME --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Start Time</label>
                            <input type="time" name="start_time"
                                class="form-control @error('start_time') is-invalid @enderror"
                                value="{{ old('start_time', $booking->start_time) }}">

                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">End Time</label>
                            <input type="time" name="end_time"
                                class="form-control @error('end_time') is-invalid @enderror"
                                value="{{ old('end_time', $booking->end_time) }}">

                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- STATUS --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>

                        <select name="status"
                            class="form-select @error('status') is-invalid @enderror">

                            @foreach (['pending','confirmed','active','completed','cancelled'] as $status)
                                <option value="{{ $status }}"
                                    {{ old('status', $booking->status) == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach

                        </select>

                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TOTAL --}}
                    <div>
                        <label class="form-label fw-semibold">Total Amount</label>
                        <input type="text" class="form-control"
                            value="₱{{ number_format($booking->total_amount, 2) }}" disabled>
                    </div>

                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="col-lg-4">

            <div class="card shadow-sm border">
                <div class="card-header bg-white text-center">
                    <h6 class="fw-bold text-uppercase small text-muted mb-0">
                        Manage Services
                    </h6>
                </div>

                <div class="card-body">

                    <div id="serviceItemsContainer">

                        @foreach ($booking->items as $index => $item)
                            <div class="service-item border p-3 mb-3">

                                <input type="hidden"
                                    name="existing_items[{{ $index }}][id]"
                                    value="{{ $item->id }}">

                                {{-- SERVICE --}}
                                <div class="mb-2">
                                    <label class="small fw-bold">Service</label>

                                    <select name="existing_items[{{ $index }}][service_id]"
                                        class="form-select @error('existing_items.$index.service_id') is-invalid @enderror">

                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}"
                                                {{ old('existing_items.$index.service_id', $item->service_id) == $service->id ? 'selected' : '' }}>
                                                {{ $service->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error("existing_items.$index.service_id")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- THERAPIST --}}
                                <div class="mb-2">
                                    <label class="small fw-bold">Therapist</label>

                                    <select name="existing_items[{{ $index }}][therapist_id]"
                                        class="form-select @error("existing_items.$index.therapist_id") is-invalid @enderror">

                                        <option value="">Unassigned</option>

                                        @foreach ($therapists as $therapist)
                                            <option value="{{ $therapist->id }}"
                                                {{ old("existing_items.$index.therapist_id", $item->therapist_id) == $therapist->id ? 'selected' : '' }}>
                                                {{ $therapist->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error("existing_items.$index.therapist_id")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="button"
                                    class="btn btn-outline-danger btn-sm w-100 remove-service">
                                    Remove Service
                                </button>

                            </div>
                        @endforeach

                    </div>

                    <button type="button" id="addServiceBtn"
                        class="btn btn-outline-primary w-100">
                        + Add New Service
                    </button>

                </div>
            </div>

        </div>

    </div>

    {{-- ACTIONS --}}
    <div class="card shadow-sm border mt-4">
        <div class="card-body d-flex justify-content-end gap-2">

            <a href="{{ route('bookings.edit', $booking->id) }}"
                class="btn btn-outline-secondary">
                Reset
            </a>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i> Save Changes
            </button>

        </div>
    </div>

</form>

@endsection


@section('page-scripts')
<script>
    let serviceIndex = {{ $booking->items->count() }};
    let errors = @json($errors->toArray());

    function getErrorClass(field) {
        return errors[field] ? 'is-invalid' : '';
    }

    function getErrorMessage(field) {
        return errors[field]
            ? `<div class="invalid-feedback">${errors[field][0]}</div>`
            : '';
    }

    $('#addServiceBtn').click(function () {

        let html = `
        <div class="service-item border p-3 mb-3">

            <div class="mb-2">
                <label class="small fw-bold">Service</label>

                <select name="new_items[${serviceIndex}][service_id]"
                    class="form-select ${getErrorClass('new_items.'+serviceIndex+'.service_id')}">

                    @foreach ($services as $service)
                        <option value="{{ $service->id }}">
                            {{ $service->name }}
                        </option>
                    @endforeach

                </select>

                ${getErrorMessage('new_items.'+serviceIndex+'.service_id')}
            </div>

            <div class="mb-2">
                <label class="small fw-bold">Therapist</label>

                <select name="new_items[${serviceIndex}][therapist_id]"
                    class="form-select ${getErrorClass('new_items.'+serviceIndex+'.therapist_id')}">

                    <option value="">Unassigned</option>

                    @foreach ($therapists as $therapist)
                        <option value="{{ $therapist->id }}">
                            {{ $therapist->name }}
                        </option>
                    @endforeach

                </select>

                ${getErrorMessage('new_items.'+serviceIndex+'.therapist_id')}
            </div>

            <button type="button"
                class="btn btn-outline-danger btn-sm w-100 remove-service">
                Remove Service
            </button>
        </div>
        `;

        $('#serviceItemsContainer').append(html);
        serviceIndex++;
    });

    $(document).on('click', '.remove-service', function () {
        $(this).closest('.service-item').remove();
    });
</script>
@endsection