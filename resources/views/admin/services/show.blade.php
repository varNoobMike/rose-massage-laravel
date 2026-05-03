@extends('layouts.admin')

@section('page-title', 'Service #' . $service->id)
@section('breadcrumb-parent', 'Services')
@section('breadcrumb-parent-url', route('services.index'))

@section('page-header', true)
@section('page-header-title-showpage', 'Service #' . $service->id)
@section('page-header-subtitle', 'Review and manage this service')
@section('page-header-actions')
    @if (auth()->user()->role !== 'receptionist')
        <a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary px-4 shadow-sm">
            <i class="bi bi-pencil-square me-2"></i> Edit
        </a>
    @endif
@endsection

@section('content')

    <div class="row g-4">

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
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase" style="width: 30%;">
                                        Service Name</td>
                                    <td class="py-4 pe-4 fw-bold text-dark">{{ $service->name }}</td>
                                </tr>
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">Service Rate</td>
                                    <td class="py-4 pe-4">
                                        <span
                                            class="h4 mb-0 fw-bold text-primary font-monospace">₱{{ number_format($service->price, 2) }}</span>
                                    </td>
                                </tr>
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">Time Allocation</td>
                                    <td class="py-4 pe-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-clock-history text-primary me-2"></i>
                                            <span class="fw-bold text-dark">{{ $service->duration_minutes }} Minutes</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">System Logs</td>
                                    <td class="py-4 pe-4 text-muted small">
                                        <div class="mb-1"><i class="bi bi-calendar-check me-2 opacity-50"></i>Created:
                                            <strong>{{ $service->created_at->format('M d, Y') }}</strong>
                                        </div>
                                        <div><i class="bi bi-arrow-repeat me-2 opacity-50"></i>Last Update:
                                            <strong>{{ $service->updated_at->diffForHumans() }}</strong>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border mb-4 text-center">
                <div class="card-body p-4">
                    <small class="text-uppercase text-muted fw-bold mb-3 d-block tracking-wider">Visibility Status</small>
                    <div
                        class="bg-{{ $service->status === 'active' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $service->status === 'active' ? 'success' : 'secondary' }} rounded-pill py-2 px-4 d-inline-flex align-items-center mb-2">
                        <i
                            class="bi bi-{{ $service->status === 'active' ? 'check-circle-fill' : 'eye-slash-fill' }} me-2"></i>
                        <span class="fw-bold text-uppercase">{{ $service->status }}</span>
                    </div>
                    <p class="text-muted small mb-0">
                        {{ $service->status === 'active' ? 'Visible to online clients' : 'Hidden from booking menu' }}</p>
                </div>
            </div>

            <div class="card shadow-sm border overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom text-center">
                    <h6 class="mb-0 fw-bold small text-muted text-uppercase tracking-wider">Marketing Image</h6>
                </div>
                <div class="card-body p-3">
                    @if ($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}"
                            class="bg-light img-fluid w-100 object-fit-cover rounded-3" style="height: 200px;">
                    @else
                        <div class="bg-light text-center py-5 border border-dashed">
                            <i class="bi bi-image text-muted fs-1 opacity-25"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
