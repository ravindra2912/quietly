@extends('admin.layouts.main')
@section('title', 'Plans List')

@push('style')
<link rel="stylesheet" href="{{ asset('assets/admin/css/jquery.dataTables.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/admin/css/datatable-custom.css') }}" />
@endpush

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Plans List</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Plans</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
        <h5 class="m-0 font-weight-bold text-primary">All Plans</h5>
        <a href="{{ route('admin.plan.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Add Plan
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Duration (Months)</th>
                        <th>Ad Free</th>
                        <th>Multiple Groups</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    $(function() {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.plan.index') }}",
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search plans...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ plans",
                infoEmpty: "Showing 0 to 0 of 0 plans",
                infoFiltered: "(filtered from _MAX_ total plans)",
                zeroRecords: "No matching plans found",
                emptyTable: "No plans available"
            },
            responsive: true,
            autoWidth: false,
            columns: [{
                    data: 'name',
                    name: 'name',
                    className: 'fw-bold'
                },
                {
                    data: 'price',
                    name: 'price',
                    className: 'text-success fw-bold'
                },
                {
                    data: 'duration_in_month',
                    name: 'duration_in_month',
                    className: 'text-center'
                },
                {
                    data: 'is_ad_free',
                    name: 'is_ad_free',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'is_active_multiple_group',
                    name: 'is_active_multiple_group',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
            ]
        });
    });


    // delete plan
    function destroy(url, id) {
        Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this plan?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Delete'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    // Ajax Delete
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            '_method': 'DELETE',
                            '_token': "{{ csrf_token() }}"
                        },
                        success: function(result) {
                            if (result.success) {
                                // toastr.success(result.message);
                                $('#data-table').DataTable().ajax.reload();
                                Swal.fire('Deleted!', result.message, 'success');
                            } else {
                                Swal.fire('Error', result.message, 'error');
                            }
                        },
                        error: function(e) {
                            Swal.fire('Error', 'Something went wrong', 'error');
                        }
                    });
                }
            })
    }
</script>
@endpush