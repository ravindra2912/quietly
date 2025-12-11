@extends('admin.layouts.main')
@section('content')
@section('title', 'Edit Plan')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Plan</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.plan.index') }}">Plans list</a></li>
                    <li class="breadcrumb-item active">Edit Plan</li>
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
                        Edit Plan
                    </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('admin.plan.update', $plan->id) }}" data-action="redirect" class="row formaction">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name <span class="error">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ $plan->name }}" placeholder="Plan Name" />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Price <span class="error">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="price" value="{{ $plan->price }}" placeholder="0.00" />
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Duration (Months) <span class="error">*</span></label>
                                <input type="number" class="form-control" name="duration_in_month" value="{{ $plan->duration_in_month }}" placeholder="1" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Ad Free <span class="error">*</span></label>
                                <select class="form-control" name="is_ad_free">
                                    <option value="">Select</option>
                                    <option value="1" {{ $plan->is_ad_free ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ !$plan->is_ad_free ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Group Active Timing <span class="error">*</span></label>
                                <select class="form-control" name="group_active_timing">
                                    <option value="">Select</option>
                                    @for ($i = 1; $i <= 24; $i++)
                                        <option value="{{ $i }}" {{ $plan->group_active_timing == $i ? 'selected' : '' }}>{{ $i }} Hour{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                        <option value="custom" {{ $plan->group_active_timing == 'custom' ? 'selected' : '' }}>Custom</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Active Multiple Groups <span class="error">*</span></label>
                                <select class="form-control" name="is_active_multiple_group">
                                    <option value="">Select</option>
                                    <option value="1" {{ $plan->is_active_multiple_group ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ !$plan->is_active_multiple_group ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Purchase Limit Per User <span class="error">*</span></label>
                                <select class="form-control" name="plan_purchase_limit_per_user" id="purchase_limit_type">
                                    <option value="">Select</option>
                                    <option value="unlimit" {{ $plan->plan_purchase_limit_per_user == 'unlimit' ? 'selected' : '' }}>Unlimited</option>
                                    <option value="limited" {{ $plan->plan_purchase_limit_per_user == 'limited' ? 'selected' : '' }}>Limited</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 {{ $plan->plan_purchase_limit_per_user != 'limited' ? 'd-none' : '' }}" id="purchase_limit_wrapper">
                            <div class="form-group">
                                <label>Purchase Limit <span class="error">*</span></label>
                                <input type="number" class="form-control" name="plan_purchase_limit" id="purchase_limit" value="{{ $plan->plan_purchase_limit }}" placeholder="Enter limit" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Status <span class="error">*</span></label>
                                <select class="form-control" name="status">
                                    <option value="">Select Status</option>
                                    @foreach (config('const.common_status') as $status)
                                    <option value="{{ $status }}" {{ $plan->status == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
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

@push('js')
<script>
    $(function() {
        // Toggle purchase limit field based on selection
        function togglePurchaseLimit() {
            if ($('#purchase_limit_type').val() === 'limited') {
                $('#purchase_limit_wrapper').removeClass('d-none');
                $('#purchase_limit').prop('disabled', false);
            } else {
                $('#purchase_limit_wrapper').addClass('d-none');
                $('#purchase_limit').prop('disabled', true).val('');
            }
        }

        // Initialize on page load
        togglePurchaseLimit();

        // Handle change event
        $('#purchase_limit_type').on('change', togglePurchaseLimit);
    });
</script>
@endpush
@endsection