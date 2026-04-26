@extends('layouts.admin')

@section('page-title', 'Announcement #' . $announcement->id)
@section('breadcrumb-parent', 'Announcements')
@section('breadcrumb-parent-url', route('announcements.index'))

@section('page-header', true)
@section('page-header-title-showpage', 'Announcement #' . $announcement->id)
@section('page-header-subtitle', 'Review and manage this announcement')
@section('page-header-actions')
    <a href="{{ route('announcements.edit', $announcement->id) }}" class="btn btn-primary px-4 shadow-sm">
        <i class="bi bi-pencil-square me-2"></i> Edit
    </a>
@endsection

@section('content')
    <div class="row g-4">

        <!-- LEFT SIDE -->
        <div class="col-12 col-lg-8">

            <div class="card shadow-sm border">
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-uppercase small text-muted">
                            Announcement Information
                        </h6>

                        <span class="badge bg-light text-primary border px-3 py-2">
                            ID: #{{ str_pad($announcement->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0 align-middle">
                            <tbody>

                                <!-- TITLE -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase"
                                        style="width:30%;">
                                        Title
                                    </td>
                                    <td class="py-4 pe-4 fw-bold text-dark">
                                        {{ $announcement->title }}
                                    </td>
                                </tr>

                                <!-- MESSAGE -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Message
                                    </td>
                                    <td class="py-4 pe-4 text-dark">
                                        {{ $announcement->message }}
                                    </td>
                                </tr>

                                <!-- TYPE -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        Type
                                    </td>
                                    <td class="py-4 pe-4">
                                        <span class="badge bg-{{ $announcement->type }}">
                                            {{ ucfirst($announcement->type) }}
                                        </span>
                                    </td>
                                </tr>

                                <!-- LINK -->
                                <tr class="border-bottom border-light">
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        External Link
                                    </td>
                                    <td class="py-4 pe-4">
                                        @if($announcement->link_url)
                                            <a href="{{ $announcement->link_url }}"
                                               target="_blank"
                                               class="text-decoration-none fw-bold">
                                                {{ $announcement->link_url }}
                                            </a>
                                        @else
                                            <span class="text-muted">No link attached</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- SYSTEM LOGS -->
                                <tr>
                                    <td class="ps-4 py-4 text-muted small fw-bold text-uppercase">
                                        System Logs
                                    </td>
                                    <td class="py-4 pe-4 text-muted small">
                                        <div class="mb-1">
                                            <i class="bi bi-calendar-check me-2 opacity-50"></i>
                                            Created:
                                            <strong>
                                                {{ $announcement->created_at->format('M d, Y') }}
                                            </strong>
                                        </div>

                                        <div>
                                            <i class="bi bi-arrow-repeat me-2 opacity-50"></i>
                                            Last Update:
                                            <strong>
                                                {{ $announcement->updated_at->diffForHumans() }}
                                            </strong>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="col-12 col-lg-4">

            <!-- ACTIVE STATUS -->
            <div class="card shadow-sm border mb-4 text-center">
                <div class="card-body p-4">

                    <small class="text-uppercase text-muted fw-bold mb-3 d-block">
                        Announcement Status
                    </small>

                    <div class="bg-{{ $announcement->is_active ? 'success' : 'secondary' }} bg-opacity-10 
                                text-{{ $announcement->is_active ? 'success' : 'secondary' }} 
                                rounded-pill py-2 px-4 d-inline-flex align-items-center mb-2">

                        <i class="bi bi-{{ $announcement->is_active ? 'check-circle-fill' : 'pause-circle-fill' }} me-2"></i>

                        <span class="fw-bold text-uppercase">
                            {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <p class="text-muted small mb-0">
                        {{ $announcement->is_active 
                            ? 'Currently visible to users'
                            : 'Currently hidden from users' }}
                    </p>
                </div>
            </div>

            <!-- SCHEDULE -->
            <div class="card shadow-sm border">
                <div class="card-header bg-white py-3 border-bottom text-center">
                    <h6 class="mb-0 fw-bold small text-muted text-uppercase">
                        Schedule Information
                    </h6>
                </div>

                <div class="card-body p-4">

                    <div class="mb-3">
                        <small class="text-muted text-uppercase fw-bold d-block">
                            Starts At
                        </small>

                        <div class="fw-bold">
                            {{ $announcement->starts_at 
                                ? \Carbon\Carbon::parse($announcement->starts_at)->format('M d, Y h:i A')
                                : 'Not scheduled' }}
                        </div>
                    </div>

                    <hr>

                    <div>
                        <small class="text-muted text-uppercase fw-bold d-block">
                            Ends At
                        </small>

                        <div class="fw-bold">
                            {{ $announcement->ends_at 
                                ? \Carbon\Carbon::parse($announcement->ends_at)->format('M d, Y h:i A')
                                : 'No expiration' }}
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection