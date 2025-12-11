@extends('admin.layouts.main')
@section('content')
@section('title', 'Edit Plan Purchase')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Plan Purchase</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.plan-purchase.index') }}">Plan Purchases</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">Edit Plan Purchase</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('admin.plan-purchase.update', $planPurchase->id) }}" data-action="redirect" class="row formaction">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>User</label>
                                <input type="text" class="form-control" value="{{ $planPurchase->user->first_name }} {{ $planPurchase->user->last_name }}" readonly />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Plan</label>
                                <input type="text" class="form-control" value="{{ $planPurchase->plan_info['name'] ?? 'N/A' }}" readonly />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Price</label>
                                <input type="text" class="form-control" value="${{ $planPurchase->price }}" readonly />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Duration</label>
                                <input type="text" class="form-control" value="{{ $planPurchase->duration_in_month }} months" readonly />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status <span class="error">*</span></label>
                                <select class="form-control" name="status">
                                    <option value="pending" {{ $planPurchase->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="active" {{ $planPurchase->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="in-active" {{ $planPurchase->status == 'in-active' ? 'selected' : '' }}>In-Active</option>
                                    <option value="expired" {{ $planPurchase->status == 'expired' ? 'selected' : '' }}>Expired</option>
                                    <option value="override" {{ $planPurchase->status == 'override' ? 'selected' : '' }}>Override</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Start Date <span class="error">*</span></label>
                                <input type="datetime-local" class="form-control" name="start_at" value="{{ $planPurchase->start_at->format('Y-m-d\TH:i') }}" />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Expiry Date <span class="error">*</span></label>
                                <input type="datetime-local" class="form-control" name="expired_at" value="{{ $planPurchase->expired_at->format('Y-m-d\TH:i') }}" />
                            </div>
                        </div>

                        <div class="col-sm-12 text-right">
                            <button class="btn btn-danger" type="button" onclick="history.back()">Back</button>
                            <button class="btn btn-primary btn_action" type="submit">
                                <span id="loader" class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                                <span id="buttonText">Update</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.col-->
    </div>
</section>
<!-- /.content -->
@endsection