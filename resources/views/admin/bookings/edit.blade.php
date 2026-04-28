@extends('layouts.admin')

@section('page-title', 'Edit')
@section('breadcrumb-parent', 'Booking #' . $booking->id)
@section('breadcrumb-parent-url', route('bookings.show', $booking->id))

@section('page-header', true)
@section('page-header-title-showpage', 'Edit Booking #' . $booking->id)
@section('page-header-subtitle', 'Update, manage, or reschedule this booking')

@section('content')

    <form method="POST" action="{{ route('bookings.update', $booking->id) }}" id="bookingEditForm">
        @csrf
        @method('PUT')

        <div class="row g-4">

            {{-- LEFT --}}
            <div class="col-lg-8">

                <div class="card shadow-sm border">
                    <div class="card-header py-3 bg-white border-bottom">
                        <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                            Booking Information
                        </h6>
                    </div>

                    <div class="card-body">

                        {{-- CLIENT --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Client</label>
                            <input type="text" class="form-control" value="{{ optional($booking->client)->name }}"
                                disabled>
                        </div>

                        {{-- DATE --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Booking Date</label>
                            <input type="date" name="booking_date" class="form-control"
                                value="{{ $booking->booking_date }}">
                        </div>

                        {{-- TIME --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    Start Time
                                </label>
                                <input type="time" name="start_time" class="form-control"
                                    value="{{ $booking->start_time }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    End Time
                                </label>
                                <input type="time" name="end_time" class="form-control" value="{{ $booking->end_time }}">
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Status</label>

                            <select name="status" class="form-select">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed
                                </option>
                                <option value="active" {{ $booking->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>
                        </div>

                        {{-- TOTAL --}}
                        <div class="mb-0">
                            <label class="form-label fw-semibold">
                                Total Amount
                            </label>
                            <input type="text" class="form-control"
                                value="₱{{ number_format($booking->total_amount, 2) }}" disabled>
                        </div>

                    </div>
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="col-lg-4">

                <div class="card shadow-sm border">
                    <div class="card-header py-3 bg-white border-bottom text-center">
                        <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                            Manage Services
                        </h6>
                    </div>

                    <div class="card-body">

                        <div id="serviceItemsContainer">

                            @foreach ($booking->items as $index => $item)
                                <div class="service-item border p-3 mb-3">

                                    <input type="hidden" name="existing_items[{{ $index }}][id]"
                                        value="{{ $item->id }}">

                                    {{-- SERVICE --}}
                                    <div class="mb-2">
                                        <label class="small fw-bold">
                                            Service
                                        </label>

                                        <select name="existing_items[{{ $index }}][service_id]" class="form-select">

                                            @foreach ($services as $service)
                                                <option value="{{ $service->id }}"
                                                    {{ $item->service_id == $service->id ? 'selected' : '' }}>
                                                    {{ $service->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- THERAPIST --}}
                                    <div class="mb-2">
                                        <label class="small fw-bold">
                                            Therapist
                                        </label>

                                        <select name="existing_items[{{ $index }}][therapist_id]"
                                            class="form-select">
                                            <option value="">Unassigned</option>

                                            @foreach ($therapists as $therapist)
                                                <option value="{{ $therapist->id }}"
                                                    {{ $item->therapist_id == $therapist->id ? 'selected' : '' }}>
                                                    {{ $therapist->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 remove-service">
                                        Remove Service
                                    </button>

                                </div>
                            @endforeach

                        </div>

                        <button type="button" id="addServiceBtn" class="btn btn-outline-primary w-100">
                            + Add New Service
                        </button>

                    </div>
                </div>

            </div>

        </div>

        {{-- GLOBAL ACTION BUTTONS --}}
        <div class="card shadow-sm border mt-4">
            <div class="card-body">

                <div class="d-flex flex-column flex-md-row gap-2 justify-content-end">

                    <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-outline-secondary px-4 shadow-sm">
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
        $(function() {

            let serviceIndex = {{ $booking->items->count() }};

            $('#addServiceBtn').click(function() {

                let html = `
            <div class="service-item border rounded p-3 mb-3">

                <div class="mb-2">
                    <label class="small fw-bold">Service</label>

                    <select name="new_items[${serviceIndex}][service_id]"
                            class="form-select">

                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-2">
                    <label class="small fw-bold">Therapist</label>

                    <select name="new_items[${serviceIndex}][therapist_id]"
                            class="form-select">

                        <option value="">Unassigned</option>

                        @foreach ($therapists as $therapist)
                            <option value="{{ $therapist->id }}">
                                {{ $therapist->name }}
                            </option>
                        @endforeach

                    </select>
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

            $(document).on('click', '.remove-service', function() {
                $(this).closest('.service-item').remove();
            });

        });
    </script>
@endsection
