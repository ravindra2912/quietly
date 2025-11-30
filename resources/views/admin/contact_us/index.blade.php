@extends('admin.layouts.main')
@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/dist/css/jquery.dataTables.css') }}" />
@endpush

@section('content')
@section('title', 'Contact Us List')

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Contact Us List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Contact Us List</li>
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
                            <h3 class="card-title">Contact Us List</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered table-striped data-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Date</th>
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
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Contact Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Name:</strong> <span id="view_name"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Email:</strong> <span id="view_email"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Phone:</strong> <span id="view_phone"></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Date:</strong> <span id="view_date"></span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>Subject:</strong> <span id="view_subject"></span>
                        </div>
                        <div class="col-12 mb-3">
                            <strong>Message:</strong>
                            <p id="view_message" class="border p-2 rounded bg-light"></p>
                        </div>
                        <div class="col-12">
                            <form id="statusForm">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="new">New</option>
                                        <option value="read">Read</option>
                                        <option value="replied">Replied</option>
                                    </select>
                                </div>
                                <input type="hidden" id="contact_id" name="id">
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <!-- Sweet Alert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script type="text/javascript">
            $(function () {
                var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.contact-us.index') }}",
                    columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'subject',
                        name: 'subject'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function (data, type, row) {
                            var badgeClass = 'secondary';
                            if (data == 'new') badgeClass = 'primary';
                            else if (data == 'read') badgeClass = 'info';
                            else if (data == 'replied') badgeClass = 'success';
                            return '<span class="badge badge-' + badgeClass + '">' + data.toUpperCase() + '</span>';
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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

            // View Record
            $('body').on('click', '.view_record', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.contact-us.index') }}" + '/' + id, function (data) {
                    if (data.success) {
                        $('#viewModal').modal('show');
                        $('#view_name').text(data.data.name);
                        $('#view_email').text(data.data.email);
                        $('#view_phone').text(data.data.phone);
                        $('#view_subject').text(data.data.subject);
                        $('#view_message').text(data.data.message);
                        $('#view_date').text(new Date(data.data.created_at).toLocaleString());
                        $('#status').val(data.data.status);
                        $('#contact_id').val(data.data.id);
                    } else {
                        toastr.error(data.message);
                    }
                });
            });

            // Update Status
            $('#statusForm').on('submit', function (e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('admin.contact-us.update-status') }}",
                    type: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function (result) {
                        if (result.success) {
                            toastr.success(result.message);
                            $('#viewModal').modal('hide');
                            $('.data-table').DataTable().ajax.reload();
                        } else {
                            toastr.error(result.message);
                        }
                    },
                    error: function (e) {
                        toastr.error('Something Wrong');
                        console.log(e);
                    }
                });
            });

            // delete record
            $('body').on('click', '.delete_record', function () {
                var url = $(this).data('url');
                Swal.fire({
                    title: 'Are you sure?',
                    icon: 'error',
                    html: "You want to delete this record?",
                    allowOutsideClick: false,
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel',
                })
                    .then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: url,
                                type: "DELETE",
                                dataType: "json",
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },
                                success: function (result) {
                                    if (result.success) {
                                        toastr.success(result.message);
                                        $('.data-table').DataTable().ajax.reload();
                                    } else {
                                        toastr.error(result.message);
                                    }
                                },
                                error: function (e) {
                                    toastr.error('Something Wrong');
                                    console.log(e);
                                }
                            });
                        }
                    })
            });
        </script>
    @endpush
@endsection