@extends('admin.layouts.main')
@section('content')
@section('title', 'Plan Purchases')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/dist/css/jquery.dataTables.css') }}" />
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2/css/select2.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" />
<!-- Daterangepicker -->
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/daterangepicker/daterangepicker.css') }}" />

@endpush

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Plan Purchases</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Plan Purchases</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Plan Purchases List</h3>
                        <div class="float-right">
                            <a href="{{ route('admin.plan-purchase.create') }}" class="btn btn-primary"><i
                                    class="fas fa-plus"></i> Add</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="filter_user">User</label>
                                <select class="form-control" id="filter_user">
                                    <option value="">All Users</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_plan">Plan</label>
                                <select class="form-control" id="filter_plan">
                                    <option value="">All Plans</option>
                                    @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_status">Status</label>
                                <select class="form-control" id="filter_status">
                                    <option value="">All Status</option>
                                    @foreach (config('const.plan_purchase_status') as $status)
                                    <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_date_range">Date Range</label>
                                <input type="text" class="form-control" id="filter_date_range" placeholder="Select date range" readonly />
                            </div>
                        </div>
                        <!-- /.filters -->
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover text-nowrap w-100" id="data-table">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Plan</th>
                                    <th>Price</th>
                                    <th>Duration</th>
                                    <th>Dates</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

@push('js')
<script src="{{ asset('assets/admin/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- Select2 -->
<script src="{{ asset('assets/admin/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    $(function() {
        // Initialize Select2 for user filter with AJAX
        $('#filter_user').select2({
            theme: 'bootstrap4',
            placeholder: 'Search users...',
            allowClear: true,
            width: '100%',
            ajax: {
                url: "{{ route('admin.plan-purchase.search-users') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            minimumInputLength: 2 // Require at least 2 characters before searching
        });

        // Initialize Date Range Picker
        $('#filter_date_range').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'DD MMM YYYY'
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        });

        // Update input when date range is selected
        $('#filter_date_range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD MMM YYYY') + ' - ' + picker.endDate.format('DD MMM YYYY'));
            table.draw();
        });

        // Clear input when cancelled
        $('#filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            table.draw();
        });
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.plan-purchase.index') }}",
                data: function(d) {
                    d.user_id = $('#filter_user').val();
                    d.plan_id = $('#filter_plan').val();
                    d.status = $('#filter_status').val();

                    // Add date range parameters
                    var dateRange = $('#filter_date_range').val();
                    if (dateRange) {
                        var dates = dateRange.split(' - ');
                        d.start_date = moment(dates[0], 'DD MMM YYYY').format('YYYY-MM-DD');
                        d.end_date = moment(dates[1], 'DD MMM YYYY').format('YYYY-MM-DD');
                    }
                }
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            columns: [{
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'plan_name',
                    name: 'plan_name'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'duration_in_month',
                    name: 'duration_in_month'
                },
                {
                    data: 'dates',
                    name: 'dates',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        // Filter event handlers
        $('#filter_user, #filter_plan, #filter_status').on('change', function() {
            table.draw();
        });
    });

    // delete plan purchase
    function destroy(url, id) {
        Swal.fire({
                title: 'Are you sure?',
                icon: 'error',
                html: "You want to delete this plan purchase?",
                allowOutsideClick: false,
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
            })
            .then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            '_method': 'DELETE'
                        },
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        beforeSend: function() {
                            $('.btn_delete-' + id + ' #buttonText').addClass('d-none');
                            $('.btn_delete-' + id + ' #loader').removeClass('d-none');
                            $('.btn_delete-' + id).prop('disabled', true);
                        },
                        success: function(result) {
                            if (result.success) {
                                toastr.success(result.message);
                                location.reload()
                            } else {
                                toastr.error(result.message);
                            }
                            $('.btn_action-' + id + ' #buttonText').removeClass('d-none');
                            $('.btn_action-' + id + ' #loader').addClass('d-none');
                            $('.btn_action-' + id).prop('disabled', false);
                        },
                        error: function(e) {
                            toastr.error('Somthing Wrong');
                            console.log(e);
                            $('.btn_action-' + id + ' #buttonText').removeClass('d-none');
                            $('.btn_action-' + id + ' #loader').addClass('d-none');
                            $('.btn_action-' + id).prop('disabled', false);
                        }
                    });
                }
            })
    }
</script>
@endpush
@endsection