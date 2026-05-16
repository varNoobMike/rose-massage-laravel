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
                        <strong>Please review your booking:</strong>

                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif

            {{-- LEFT COLUMN --}}
            <div class="col-lg-7">

                {{-- MOBILE QUICK SUMMARY --}}
                <div class="d-lg-none mb-3">

                    <div class="card shadow-sm border">
                        <div class="card-body py-3">

                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <div class="fw-bold">Booking Summary</div>
                                    <small class="text-muted" id="mobileServiceCount">
                                        0 services
                                    </small>
                                </div>

                                <div class="text-end">
                                    <div class="fw-bold text-primary" id="mobileTotal">
                                        ₱0.00
                                    </div>

                                    <small class="text-muted" id="mobileDuration">
                                        0 min
                                    </small>
                                </div>

                            </div>

                            {{-- COLLAPSE BUTTON --}}
                            <button type="button" class="btn btn-primary btn-sm w-100 mt-3 shadow-sm"
                                data-bs-toggle="collapse" data-bs-target="#mobileSummaryCollapse">

                                <i class="bi bi-card-list me-1"></i>
                                View Full Summary

                            </button>

                            {{-- COLLAPSE CONTENT --}}
                            <div class="collapse mt-3" id="mobileSummaryCollapse">

                                <div class="border-top pt-3">

                                    <div class="mb-2">
                                        <span class="text-muted">Date:</span>
                                        <span class="fw-bold" id="summaryDateMobile">—</span>
                                    </div>

                                    <div class="mb-2">
                                        <span class="text-muted">Start Time:</span>
                                        <span class="fw-bold" id="summaryTimeMobile">—</span>
                                    </div>

                                    <div class="mb-3">
                                        <span class="text-muted">End Time:</span>
                                        <span class="fw-bold" id="summaryEndMobile">—</span>
                                    </div>

                                    <hr>

                                    <div id="mobileCartList" class="text-muted small text-center py-3">
                                        No services selected
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>

                {{-- FORM CARD --}}
                <div class="card shadow-sm border">
                    <div class="card-body p-4">

                        <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm">

                            @csrf

                            {{-- Container for full service metadata hidden inputs --}}
                            <div id="servicesHiddenInputsContainer"></div>

                            {{-- DATE / TIME --}}
                            <div class="row g-3 mb-3">

                                {{-- DATE --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-bold">
                                        Date
                                    </label>

                                    <input type="date" id="dateInput" name="booking_date"
                                        min="{{ now()->format('Y-m-d') }}"
                                        class="form-control @error('booking_date') is-invalid @enderror"
                                        value="{{ old('booking_date') }}">

                                    @error('booking_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- TIME --}}
                                <div class="col-md-6">

                                    <label class="form-label fw-bold">
                                        Start Time
                                    </label>

                                    <input type="time" id="timeInput" name="start_time"
                                        class="form-control @error('start_time') is-invalid @enderror"
                                        value="{{ old('start_time') }}">

                                    @error('start_time')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- OPERATING HOURS --}}
                                <div class="col-12">

                                    <div class="alert alert-light border py-2 mb-0">

                                        <small class="text-muted d-flex justify-content-between align-items-center">

                                            <span>
                                                <i class="bi bi-clock me-1"></i>
                                                Bookings are only available within operating hours.
                                            </span>

                                            <a href="#" class="btn btn-sm btn-link p-0" data-bs-toggle="collapse"
                                                data-bs-target="#operatingHoursCollapse">
                                                View schedule
                                            </a>

                                        </small>

                                        {{-- COLLAPSE --}}
                                        <div class="collapse mt-3" id="operatingHoursCollapse">

                                            <div class="table-responsive">

                                                <table class="table table-sm table-hover align-middle mb-0">

                                                    <thead>
                                                        <tr>
                                                            <th>Day</th>
                                                            <th>Opening</th>
                                                            <th>Closing</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>

                                                        @forelse($operatingHours ?? [] as $hour)
                                                            <tr>

                                                                <td class="fw-semibold">
                                                                    {{ $hour->day_of_week }}
                                                                </td>

                                                                <td>
                                                                    {{ !$hour->start_time || $hour->is_closed ? '—' : \Carbon\Carbon::parse($hour->start_time)->format('h:i A') }}
                                                                </td>

                                                                <td>
                                                                    {{ !$hour->end_time || $hour->is_closed ? '—' : \Carbon\Carbon::parse($hour->end_time)->format('h:i A') }}
                                                                </td>

                                                                <td>
                                                                    @if ($hour->is_closed)
                                                                        <span class="badge bg-danger">
                                                                            Closed
                                                                        </span>
                                                                    @else
                                                                        <span class="badge bg-success">
                                                                            Open
                                                                        </span>
                                                                    @endif
                                                                </td>

                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center text-muted">
                                                                    No operating hours set
                                                                </td>
                                                            </tr>
                                                        @endforelse

                                                    </tbody>

                                                </table>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            {{-- SERVICE --}}
                            <div class="mb-3">

                                <label class="form-label fw-bold">
                                    Service
                                </label>

                                <select id="serviceSelect" class="form-select @error('services') is-invalid @enderror">

                                    <option value="">
                                        Select service
                                    </option>

                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}" data-name="{{ $service->name }}"
                                            data-price="{{ $service->price }}"
                                            data-duration="{{ $service->duration_minutes }}">

                                            {{ $service->name }}
                                            ({{ $service->duration_minutes }} min •
                                            ₱{{ number_format($service->price, 2) }})
                                        </option>
                                    @endforeach

                                </select>
                                
                                @error('services')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- ADD --}}
                            <button type="button" id="addServiceBtn" class="btn btn-primary w-100 mb-3">
                                + Add Service
                            </button>

                            {{-- NOTES --}}
                            <div class="mb-3">

                                <label class="form-label fw-bold">
                                    Notes
                                </label>

                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>

                                @error('notes')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- SUBMIT --}}
                            <button type="submit" class="btn btn-success w-100 fw-bold">
                                Confirm Booking
                            </button>

                        </form>

                    </div>
                </div>

            </div>

            {{-- RIGHT SUMMARY COLUMN (DESKTOP) --}}
            <div class="col-lg-5 d-none d-lg-block">

                <div class="card shadow-sm border">

                    <div class="card-body">

                        <h5 class="fw-bold mb-3">
                            Booking Summary
                        </h5>

                        <div class="mb-2">
                            <span class="text-muted">Date:</span>
                            <span class="fw-bold" id="summaryDate">—</span>
                        </div>

                        <div class="mb-2">
                            <span class="text-muted">Start Time:</span>
                            <span class="fw-bold" id="summaryTime">—</span>
                        </div>

                        <div class="mb-2">
                            <span class="text-muted">End Time:</span>
                            <span class="fw-bold" id="summaryEnd">—</span>
                        </div>

                        <hr>

                        <div id="cartList" class="text-muted small text-center py-3">
                            No services selected
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold text-primary" id="summaryTotal">
                                ₱0.00
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mt-2">
                            <span class="text-muted">Duration</span>
                            <span class="fw-bold" id="summaryDuration">
                                0 min
                            </span>
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

            // ====================================
            // CART STATE (SAFE RESTORE & MULTI-TYPE CHECK)
            // ====================================
            let rawOldCart = @json(old('services', []));
            
            // Validation errors sometimes turn standard arrays into indexed JSON objects. Fix this cleanly:
            let cart = Array.isArray(rawOldCart) ? rawOldCart : Object.values(rawOldCart);

            cart = cart.map(item => ({
                id: parseInt(item.id),
                name: item.name ?? '',
                price: parseFloat(item.price ?? 0),
                duration: parseInt(item.duration ?? 0)
            }));

            // ========================
            // HELPERS
            // ========================
            function timeToMinutes(time) {
                if (!time) return null;
                let [h, m] = time.split(':').map(Number);
                return h * 60 + m;
            }

            function minutesToTime(mins) {
                let h = Math.floor(mins / 60) % 24;
                let m = mins % 60;
                return `${String(h).padStart(2,'0')}:${String(m).padStart(2,'0')}`;
            }

            function formatTime12hr(time) {
                if (!time) return '—';
                let [h, m] = time.split(':');
                h = parseInt(h);
                let ampm = h >= 12 ? 'PM' : 'AM';
                h = h % 12 || 12;
                return `${h}:${m} ${ampm}`;
            }

            function formatDuration(minutes) {
                if (minutes < 60) return minutes + ' min';
                let h = Math.floor(minutes / 60);
                let m = minutes % 60;
                return m ? `${h} hr ${m} min` : `${h} hr`;
            }

            // ====================================
            // RENDER SYSTEM (SOURCE OF TRUTH)
            // ====================================
            function render() {
                let total = 0;
                let duration = 0;

                let start = $('#timeInput').val();
                let cursor = (start && cart.length > 0) ? timeToMinutes(start) : null;

                let html = '';
                $('#servicesHiddenInputsContainer').empty();

                cart.forEach((item, i) => {
                    total += item.price;
                    duration += item.duration;

                    let s = null;
                    let e = null;

                    if (cursor !== null) {
                        s = minutesToTime(cursor);
                        cursor += item.duration;
                        e = minutesToTime(cursor);
                    }

                    let itemTimeline = (s && e) ? `<br><small class="text-primary">${formatTime12hr(s)} - ${formatTime12hr(e)}</small>` : '';

                    html += `
                        <div class="d-flex justify-content-between mb-2 align-items-start">
                            <div>
                                <div class="fw-semibold text-start">${item.name}</div>
                                <div class="text-start"><small class="text-muted">${item.duration} min</small></div>
                                <div class="text-start">${itemTimeline}</div>
                            </div>
                            <div class="text-end text-nowrap">
                                ₱${item.price.toFixed(2)}
                                <br>
                                <button type="button" class="btn btn-link text-danger remove p-0 border-0" data-index="${i}">remove</button>
                            </div>
                        </div>
                    `;

                    // CRITICAL FIX: Save ALL metadata context back into hidden inputs so validation state retains item attributes
                    $('#servicesHiddenInputsContainer').append(`
                        <input type="hidden" name="services[${i}][id]" value="${item.id}">
                        <input type="hidden" name="services[${i}][name]" value="${item.name}">
                        <input type="hidden" name="services[${i}][price]" value="${item.price}">
                        <input type="hidden" name="services[${i}][duration]" value="${item.duration}">
                    `);
                });

                // PROD RULE: End time calculation only activates if services are explicitly selected
                let bookingEndTime = (cursor && cart.length > 0) ? minutesToTime(cursor) : null;
                let finalFormattedTotal = '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2 });
                let finalFormattedDuration = formatDuration(duration);
                let finalFormattedEndTime = bookingEndTime ? formatTime12hr(bookingEndTime) : '—';

                // Desktop Display Update
                $('#cartList').html(cart.length ? html : `<div class="text-muted py-3">No services selected</div>`);
                $('#summaryTotal').text(finalFormattedTotal);
                $('#summaryDuration').text(finalFormattedDuration);
                $('#summaryEnd').text(finalFormattedEndTime);

                // Mobile View Sync Update
                $('#mobileServiceCount').text(`${cart.length} service${cart.length === 1 ? '' : 's'}`);
                $('#mobileTotal').text(finalFormattedTotal);
                $('#mobileDuration').text(duration + ' min');
                $('#mobileCartList').html(cart.length ? html : `<div class="text-muted py-3">No services selected</div>`);
                $('#summaryEndMobile').text(finalFormattedEndTime);
                
                // Form input date/time changes updated clean across both device view selectors
                let pickedDate = $('#dateInput').val();
                let cleanFormattedDate = pickedDate ? new Date(pickedDate).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '—';
                let cleanFormattedTime = formatTime12hr(start);

                $('#summaryDate, #summaryDateMobile').text(cleanFormattedDate);
                $('#summaryTime, #summaryTimeMobile').text(cleanFormattedTime);
            }

            // ========================
            // EVENT ACTIONS
            // ========================
            $('#addServiceBtn').on('click', function() {
                let opt = $('#serviceSelect option:selected');
                if (!opt.val()) return;

                let id = parseInt(opt.val());

                if (cart.some(x => x.id === id)) {
                    alert('Service already added.');
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

            // Delegate single dynamic container tracking context clean across UI triggers
            $(document).on('click', '.remove', function() {
                cart.splice($(this).data('index'), 1);
                render();
            });

            $('#dateInput, #timeInput').on('input change', function() {
                render();
            });

            // Run structural render execution instantly on load
            render();
        });
    </script>

@endsection