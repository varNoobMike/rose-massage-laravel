@extends('layouts.user')

@section('page-title', 'Book')

@section('breadcrumb', true)
@section('breadcrumb-parent', 'Bookings')
@section('breadcrumb-parent-url', route('bookings.index'))

@section('page-header', true)
@section('page-header-title', 'Book Therapy')
@section('page-header-subtitle', 'Schedule massage therapy')


@section('content')

    <div class="container px-lg-5">
        <div class="row g-4">

            {{-- GLOBAL ERRORS --}}
            @if ($errors->any())
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Please fix the following:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif



            {{-- LEFT FORM --}}
            <div class="col-lg-7">

                <div class="card shadow-sm border">
                    <div class="card-body p-4">

                        <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">
                            @csrf

                            {{-- DATE / TIME --}}
                            <div class="row g-3 mb-3">

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Date</label>

                                    <input type="date" id="dateInput" name="booking_date"
                                        min="{{ now()->format('Y-m-d') }}"
                                        class="form-control @error('booking_date') is-invalid @enderror"
                                        value="{{ old('booking_date') }}" required>

                                    @error('booking_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Time</label>

                                    <input type="time" id="timeInput" name="start_time"
                                        class="form-control @error('start_time') is-invalid @enderror"
                                        value="{{ old('start_time') }}" required>

                                    @error('start_time')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>

                            {{-- SERVICE SELECT --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Service</label>

                                <select id="serviceSelect" class="form-select @error('services') is-invalid @enderror">
                                    <option value="">Select service</option>

                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}" data-name="{{ $service->name }}"
                                            data-price="{{ $service->price }}"
                                            data-duration="{{ $service->duration_minutes }}"
                                            {{ request('service') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }}
                                            ({{ $service->duration_minutes }} min •
                                            ₱{{ number_format($service->price, 2) }})
                                        </option>
                                    @endforeach
                                </select>

                                @error('services')
                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="button" id="addServiceBtn" class="btn btn-primary w-100 mb-3">
                                + Add Service
                            </button>

                            {{-- STRUCTURED INPUTS --}}
                            <div id="servicesContainer"></div>

                            {{-- NOTES --}}
                            <div class="mb-3">
                                <label class="form-label fw-bold">Notes</label>

                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>

                                @error('notes')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" id="submitBtn" class="btn btn-success w-100 fw-bold" disabled>
                                Confirm Booking
                            </button>

                        </form>

                    </div>
                </div>

            </div>

            {{-- RIGHT SUMMARY --}}
            <div class="col-lg-5">

                <div class="card shadow-sm border sticky-top" style="top:100px;">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">Booking Summary</h5>

                        <div class="mb-2">
                            <span class="text-muted">Date:</span>
                            <span class="fw-bold" id="summaryDate">—</span>
                        </div>

                        <div class="mb-2">
                            <span class="text-muted">Time:</span>
                            <span class="fw-bold" id="summaryTime">—</span>
                        </div>

                        <hr>

                        <div id="cartList" class="text-muted small">
                            <div class="text-center py-3">No services selected</div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-primary" id="summaryTotal">₱0.00</span>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <span class="text-muted">Duration</span>
                            <span class="fw-bold" id="summaryDuration">0 min</span>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection

@section('page-scripts')
    <script>
        $(function() {

            let cart = [];

            function render() {

                let total = 0;
                let duration = 0;

                let html = '';

                if (cart.length === 0) {
                    html = `<div class="text-center py-3 text-muted">No services selected</div>`;
                }

                $('#servicesContainer').html('');

                cart.forEach((item, i) => {

                    total += item.price;
                    duration += item.duration;

                    html += `
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        <div class="fw-semibold">${item.name}</div>
                        <small class="text-muted">${item.duration} min</small>
                    </div>

                    <div class="text-end">
                        <div>₱${item.price.toFixed(2)}</div>
                        <button type="button" class="btn btn-sm btn-link text-danger remove" data-index="${i}">
                            remove
                        </button>
                    </div>
                </div>
            `;

                    $('#servicesContainer').append(`
                <input type="hidden" name="services[${i}][id]" value="${item.id}">
            `);

                });

                $('#cartList').html(html);

                $('#summaryTotal').text(
                    '₱' + total.toLocaleString('en-PH', {
                        minimumFractionDigits: 2
                    })
                );

                $('#summaryDuration').text(duration + ' min');

                let valid =
                    cart.length > 0 &&
                    $('#dateInput').val() &&
                    $('#timeInput').val();

                $('#submitBtn').prop('disabled', !valid);
            }

            // ADD SERVICE
            $('#addServiceBtn').click(function() {

                let opt = $('#serviceSelect option:selected');

                if (!opt.val()) return;

                let id = parseInt(opt.val());

                if (cart.find(x => x.id == id)) {
                    alert('Already added');
                    return;
                }

                cart.push({
                    id: id,
                    name: opt.data('name'),
                    price: parseFloat(opt.data('price')),
                    duration: parseInt(opt.data('duration'))
                });

                $('#serviceSelect').val('');

                render();
            });

            // REMOVE SERVICE
            $(document).on('click', '.remove', function() {
                cart.splice($(this).data('index'), 1);
                render();
            });

            // DATE/TIME
            $('#dateInput, #timeInput').on('change', function() {

                $('#summaryDate').text($('#dateInput').val() || '—');
                $('#summaryTime').text($('#timeInput').val() || '—');

                render();
            });

            // FINAL SUBMIT VALIDATION
            $('#bookingForm').submit(function(e) {

                if (cart.length === 0) {
                    e.preventDefault();
                    alert('Please add at least one service.');
                    return;
                }

                if (!$('#dateInput').val() || !$('#timeInput').val()) {
                    e.preventDefault();
                    alert('Please select date and time.');
                    return;
                }

            });

        });
    </script>
@endsection
