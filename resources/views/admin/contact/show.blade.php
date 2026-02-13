@extends('admin.layouts.main')
@section('title', 'View Contact - ' . $contact->name)

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Contact Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.contact.index') }}" class="text-decoration-none">Contacts</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $contact->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold text-primary">Message Details</h5>
                <span class="badge bg-{{ $contact->getTypeBadgeColor() }}">
                    {{ $contact->type }}
                </span>
            </div>
            <div class="card-body">
                <!-- Sender Information -->
                <div class="mb-4">
                    <h6 class="text-muted text-uppercase font-weight-bold mb-3">Sender Information</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Name</label>
                            <p class="form-control-plaintext">{{ $contact->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext">
                                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                            </p>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Message Content -->
                <div class="mb-4">
                    <h6 class="text-muted text-uppercase font-weight-bold mb-3">Message</h6>
                    <div class="bg-light p-3 rounded border">
                        <p class="mb-0" style="white-space: pre-wrap; word-break: break-word;">{{ $contact->description }}</p>
                    </div>
                </div>

                <hr>

                <!-- Metadata -->
                <div class="row text-muted small">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Submitted On</label>
                        <p>{{ $contact->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Last Updated</label>
                        <p>{{ $contact->updated_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Status Update Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h5 class="m-0 font-weight-bold text-primary">Update Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.contact.update', $contact->id) }}" class="formaction" data-action="message">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">

                    <div class="mb-3">
                        <label for="status" class="form-label fw-bold">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ $contact->status === $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-2">
                            Current Status: <strong class="badge bg-{{ $contact->getStatusBadgeColor() }}">
                                {{ ucfirst(str_replace('_', ' ', $contact->status)) }}
                            </strong>
                        </small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contact Information Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h5 class="m-0 font-weight-bold text-primary">Quick Info</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Type</label>
                    <p class="form-control-plaintext">
                        <span class="badge bg-{{ $contact->getTypeBadgeColor() }}">
                            {{ $contact->type }}
                        </span>
                    </p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <p class="form-control-plaintext">
                        <span class="badge bg-{{ $contact->getStatusBadgeColor() }}">
                            {{ ucfirst(str_replace('_', ' ', $contact->status)) }}
                        </span>
                    </p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Read Status</label>
                    <p class="form-control-plaintext">
                        @if($contact->is_read)
                        <span class="badge bg-success">
                            <i class="bi bi-check-circle me-1"></i> Read
                        </span>
                        @else
                        <span class="badge bg-warning">
                            <i class="bi bi-exclamation-circle me-1"></i> Unread
                        </span>
                        @endif
                    </p>
                </div>

                <div class="mb-0">
                    <label class="form-label fw-bold">ID</label>
                    <p class="form-control-plaintext text-muted small">#{{ $contact->id }}</p>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="d-grid gap-2">
            <a href="{{ route('admin.contact.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i> Back to Contacts
            </a>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endpush