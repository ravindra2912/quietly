@extends('admin.layouts.main')
@section('content')
@section('title', 'Create Plan Purchase')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Create Plan Purchase</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.plan-purchase.index') }}">Plan Purchases</a></li>
                    <li class="breadcrumb-item active">Create</li>
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
                    <h3 class="card-title">Create Plan Purchase</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('admin.plan-purchase.store') }}" data-action="redirect" class="row formaction">
                        @csrf
                        <input type="hidden" name="_method" value="POST">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>User <span class="error">*</span></label>
                                <select class="form-control" name="user_id">
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Plan <span class="error">*</span></label>
                                <select class="form-control" name="plan_id">
                                    <option value="">Select Plan</option>
                                    @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} - ${{ $plan->price }} ({{ $plan->duration_in_month }} months)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Start Date <span class="error">*</span></label>
                                <input type="datetime-local" class="form-control" name="start_at" value="{{ now()->format('Y-m-d\TH:i') }}" />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status <span class="error">*</span></label>
                                <select class="form-control" name="status">
                                    <option value="">Select Status</option>
                                    @foreach (config('const.plan_purchase_status') as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12 text-right">
                            <button class="btn btn-danger" type="button" onclick="history.back()">Back</button>
                            <button class="btn btn-primary btn_action" type="submit">
                                <span id="loader" class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                                <span id="buttonText">Submit</span>
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