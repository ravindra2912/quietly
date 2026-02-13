@extends('admin.layouts.main')
@section('title', 'Contacts')

@push('style')
<link rel="stylesheet" href="{{ asset('assets/admin/css/jquery.dataTables.min.css') }}?v={{ filemtime(public_path('assets/admin/css/jquery.dataTables.min.css')) }}" />
<link rel="stylesheet" href="{{ asset('assets/admin/css/datatable-custom.css') }}?v={{ filemtime(public_path('assets/admin/css/datatable-custom.css')) }}" />
@endpush

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Contacts List</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Contacts</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
        <h5 class="m-0 font-weight-bold text-primary">All Contacts</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-center">Read</th>
                        <th>Created At</th>
                        <th class="text-center">Action</th>
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
<script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}?v={{ filemtime(public_path('assets/admin/js/jquery.dataTables.min.js')) }}"></script>
<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    $(function() {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.contact.index') }}",
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search contacts...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ contacts",
                infoEmpty: "Showing 0 to 0 of 0 contacts",
                infoFiltered: "(filtered from _MAX_ total contacts)",
                zeroRecords: "No matching contacts found",
                emptyTable: "No contacts available"
            },
            responsive: true,
            autoWidth: false,
            columns: [
                {
                    data: 'name_with_read',
                    name: 'name',
                    className: 'fw-bold'
                },
                {
                    data: 'email',
                    name: 'email',
                    render: function(data) {
                        return '<a href="mailto:' + data + '">' + data + '</a>';
                    }
                },
                {
                    data: 'type',
                    name: 'type',
                    className: 'text-center'
                },
                {
                    data: 'status',
                    name: 'status',
                    className: 'text-center'
                },
                {
                    data: 'is_read',
                    name: 'is_read',
                    className: 'text-center',
                    orderable: false
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    className: 'text-muted'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            order: [[5, 'desc']]
        });
    });
</script>

@endpush
