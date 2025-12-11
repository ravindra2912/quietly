@extends('admin.layouts.main')
@section('content')
@section('title', 'Plan Purchases')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/dist/css/jquery.dataTables.css') }}" />
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
<script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    $(function() {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.plan-purchase.index') }}",
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