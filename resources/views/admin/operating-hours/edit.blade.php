@extends('layouts.admin')

@section('page-title', 'Edit Schedule')

@section('breadcrumb-parent', 'Schedule #' . $operatingHour->id)
@section('breadcrumb-parent-url', route('settings.operating-hours.show', $operatingHour->id))

@section('page-header', true)
@section('page-header-title-showpage', 'Edit Schedule #' . $operatingHour->id)
@section('page-header-subtitle', 'Update operating schedule')

@section('content')

    <form action="{{ route('settings.operating-hours.update', $operatingHour->id) }}" method="POST" id="editScheduleForm">
        @csrf
        @method('PUT')

        @php
            $isClosed = old('is_closed', $operatingHour->is_closed);
        @endphp

        <div class="row g-4">

            {{-- LEFT --}}
            <div class="col-12 col-lg-8">

                <div class="card shadow-sm border">

                    <div class="card-header bg-white py-3 border-bottom">

                        <div class="d-flex justify-content-between align-items-center">

                            <h6 class="mb-0 fw-bold text-uppercase small text-muted tracking-wider">
                                Schedule Information
                            </h6>

                            <span class="badge bg-light text-primary border px-3 py-2">
                                ID: #{{ str_pad($operatingHour->id, 4, '0', STR_PAD_LEFT) }}
                            </span>

                        </div>

                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-borderless mb-0 align-middle">

                                <tbody>

                                    {{-- DAY --}}
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase" style="width:30%;">
                                            Day of Week
                                        </td>
                                        <td class="py-4 pe-4">

                                            <select name="day_of_week"
                                                class="form-select border-2 @error('day_of_week') is-invalid @enderror"
                                                @disabled(true)>

                                                @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                    <option value="{{ $day }}"
                                                        {{ old('day_of_week', $operatingHour->day_of_week) === $day ? 'selected' : '' }}>
                                                        {{ $day }}
                                                    </option>
                                                @endforeach

                                            </select>

                                            @error('day_of_week')
                                                <div class="text-danger small fw-bold mt-1">{{ $message }}</div>
                                            @enderror

                                        </td>
                                    </tr>

                                    {{-- IS CLOSED --}}
                                    <tr class="border-bottom border-light">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                            Status
                                        </td>
                                        <td class="py-4 pe-4">

                                            <select name="is_closed" id="isClosed" class="form-select border-2 fw-bold">

                                                <option value="0" {{ !$isClosed ? 'selected' : '' }}>
                                                    Open
                                                </option>

                                                <option value="1" {{ $isClosed ? 'selected' : '' }}>
                                                    Closed
                                                </option>

                                            </select>

                                        </td>
                                    </tr>

                                    {{-- START TIME --}}
                                    <tr
                                        class="border-bottom border-light time-row {{ $operatingHour->is_closed ? 'd-none' : '' }}">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                            Opening Time
                                        </td>
                                        <td class="py-4 pe-4">

                                            <input type="time" name="start_time"
                                                value="{{ old('start_time', \Carbon\Carbon::parse($operatingHour->start_time)->format('h:i')) }}"
                                                class="form-control border-2">

                                        </td>
                                    </tr>

                                    {{-- END TIME --}}
                                    <tr
                                        class="border-bottom border-light time-row {{ $operatingHour->is_closed ? 'd-none' : '' }}">
                                        <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                            Closing Time
                                        </td>
                                        <td class="py-4 pe-4">

                                            <input type="time" name="end_time"
                                                value="{{ old('end_time', \Carbon\Carbon::parse($operatingHour->end_time)->format('h:i')) }}"
                                                class="form-control border-2">

                                        </td>
                                    </tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            {{-- RIGHT --}}
            <div class="col-12 col-lg-4">

                {{-- STATUS CARD --}}
                <div class="card shadow-sm border mb-4 text-center">

                    <div class="card-body p-4">

                        <small class="text-uppercase text-muted fw-bold mb-3 d-block tracking-wider">
                            Current Status
                        </small>

                        <div id="statusBadge"
                            class="rounded-pill py-2 px-4 d-inline-flex align-items-center mb-2
                         {{ $isClosed ? 'bg-danger bg-opacity-10 text-danger' : 'bg-success bg-opacity-10 text-success' }}">

                            <i class="bi {{ $isClosed ? 'bi-x-circle-fill' : 'bi-check-circle-fill' }} me-2"></i>

                            <span class="fw-bold text-uppercase">
                                {{ $isClosed ? 'Closed' : 'Open' }}
                            </span>

                        </div>

                        <p class="text-muted small mb-0">
                            Toggle to enable or disable operating schedule
                        </p>

                    </div>

                </div>

            </div>

        </div>

        {{-- ACTIONS --}}
        <div class="card shadow-sm border mt-4">
            <div class="card-body">

                <div class="d-flex flex-column flex-md-row gap-2 justify-content-end">

                    <a href="{{ route('settings.operating-hours.edit', $operatingHour->id) }}"
                        class="btn btn-outline-secondary px-4 shadow-sm">
                        Reset
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
        $(document).ready(function() {

            function toggleTimeFields() {

                let isClosed = $('#isClosed').val() === '1';

                if (isClosed) {
                    $('.time-row').fadeOut()
                        .addClass('d-none');
                } else {
                    $('.time-row').fadeIn()
                        .removeClass('d-none');
                }

            }

            $('#isClosed').on('change', function() {
                toggleTimeFields();
            });

            toggleTimeFields();

        });
    </script>
@endsection
