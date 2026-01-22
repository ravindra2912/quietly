@extends('admin.layouts.main')
@section('content')
@section('title', 'Edit Plan')

<!-- Content Header (Page header) -->
<!-- Content Header (Page header) -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Plan</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.plan.index') }}" class="text-decoration-none">Plans list</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Plan</li>
            </ol>
        </nav>
    </div>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-white">
                    <h5 class="m-0 font-weight-bold text-primary">Edit Plan</h5>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('admin.plan.update', $plan->id) }}" data-action="redirect" class="formaction">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ $plan->name }}" placeholder="Plan Name" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Price <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" name="price" value="{{ $plan->price }}" placeholder="0.00" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Price in Dollar</label>
                                    <input type="number" step="0.01" class="form-control" name="price_in_dollar" value="{{ $plan->price_in_dollar }}" placeholder="0.00" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Duration (Months) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="duration_in_month" value="{{ $plan->duration_in_month }}" placeholder="1" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Ad Free <span class="text-danger">*</span></label>
                                    <select class="form-select" name="is_ad_free">
                                        <option value="">Select</option>
                                        <option value="1" {{ $plan->is_ad_free ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ !$plan->is_ad_free ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Group Active Timing <span class="text-danger">*</span></label>
                                    <select class="form-select" name="group_active_timing">
                                        <option value="">Select</option>
                                        @for ($i = 1; $i <= 24; $i++)
                                            <option value="{{ $i }}" {{ $plan->group_active_timing == $i ? 'selected' : '' }}>{{ $i }} Hour{{ $i > 1 ? 's' : '' }}</option>
                                            @endfor
                                            <option value="custom" {{ $plan->group_active_timing == 'custom' ? 'selected' : '' }}>Custom</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Active Multiple Groups <span class="text-danger">*</span></label>
                                    <select class="form-select" name="is_active_multiple_group">
                                        <option value="">Select</option>
                                        <option value="1" {{ $plan->is_active_multiple_group ? 'selected' : '' }}>Yes</option>
                                        <option value="0" {{ !$plan->is_active_multiple_group ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Purchase Limit Per User <span class="text-danger">*</span></label>
                                    <select class="form-select" name="plan_purchase_limit_per_user" id="purchase_limit_type">
                                        <option value="">Select</option>
                                        <option value="unlimit" {{ $plan->plan_purchase_limit_per_user == 'unlimit' ? 'selected' : '' }}>Unlimited</option>
                                        <option value="limited" {{ $plan->plan_purchase_limit_per_user == 'limited' ? 'selected' : '' }}>Limited</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 {{ $plan->plan_purchase_limit_per_user != 'limited' ? 'd-none' : '' }}" id="purchase_limit_wrapper">
                                <div class="mb-3">
                                    <label class="form-label">Purchase Limit <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="plan_purchase_limit" id="purchase_limit" value="{{ $plan->plan_purchase_limit }}" placeholder="Enter limit" />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" name="status">
                                        <option value="">Select Status</option>
                                        @foreach (config('const.common_status') as $status)
                                        <option value="{{ $status }}" {{ $plan->status == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button class="btn btn-secondary" type="button" onclick="history.back()">Back</button>
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