@extends('admin.layouts.main')
@section('content')
@section('title', 'Create Plan')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Create Plan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.plan.index') }}">Plans list</a></li>
                    <li class="breadcrumb-item active">Create Plan</li>
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
                    <h3 class="card-title">
                        Create Plan
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('admin.plan.store') }}" data-action="redirect" class="row formaction">
                        @csrf
                        <input type="hidden" name="_method" value="POST">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name <span class="error">*</span></label>
                                <input type="text" class="form-control" name="name" placeholder="Plan Name" />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Price <span class="error">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="price" placeholder="0.00" />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Duration (Months) <span class="error">*</span></label>
                                <input type="number" class="form-control" name="duration_in_month" placeholder="1" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Ad Free <span class="error">*</span></label>
                                <select class="form-control" name="is_ad_free">
                                    <option value="">Select</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Group Active Timing <span class="error">*</span></label>
                                <select class="form-control" name="group_active_timing">
                                    <option value="">Select</option>
                                    @for ($i = 1; $i <= 24; $i++)
                                        <option value="{{ $i }}">{{ $i }} Hour{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                        <option value="custom">Custom</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Active Multiple Groups <span class="error">*</span></label>
                                <select class="form-control" name="is_active_multiple_group">
                                    <option value="">Select</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Purchase Limit Per User <span class="error">*</span></label>
                                <select class="form-control" name="plan_purchase_limit_per_user" id="purchase_limit_type">
                                    <option value="">Select</option>
                                    <option value="unlimit">Unlimited</option>
                                    <option value="limited">Limited</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 d-none" id="purchase_limit_wrapper">
                            <div class="form-group">
                                <label>Purchase Limit <span class="error">*</span></label>
                                <input type="number" class="form-control" name="plan_purchase_limit" id="purchase_limit" placeholder="Enter limit" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status <span class="error">*</span></label>
                                <select class="form-control" name="status">
                                    <option value="">Select Status</option>
                                    @foreach (config('const.common_status') as $status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
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

@push('js')
<script>
    $(function() {
        // Toggle purchase limit field based on selection
        $('#purchase_limit_type').on('change', function() {
            if ($(this).val() === 'limited') {
                $('#purchase_limit_wrapper').removeClass('d-none');
                $('#purchase_limit').prop('disabled', false);
            } else {
                $('#purchase_limit_wrapper').addClass('d-none');
                $('#purchase_limit').prop('disabled', true).val('');
            }
        });
    });
</script>
@endpush
@endsection