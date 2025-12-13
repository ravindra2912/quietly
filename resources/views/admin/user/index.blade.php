@extends('admin.layouts.main')
@section('title', 'Users List')

@push('style')
<link rel="stylesheet" href="{{ asset('assets/admin/css/jquery.dataTables.min.css') }}" />
@endpush

@section('content')

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Users List</h1>
  <div class="btn-toolbar mb-2 mb-md-0">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Users</li>
      </ol>
    </nav>
  </div>
</div>

<div class="card shadow mb-4">
  <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
    <h5 class="m-0 font-weight-bold text-primary">All Users</h5>
    <a href="{{ route('admin.user.create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-person-plus-fill me-1"></i> Add User
    </a>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" cellspacing="0">
        <thead class="table-light">
          <tr>
            <th>Avatar</th>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Contact</th>
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
<!-- Sweet Alert (cdn for now) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
  var table = '';
  $(function() {
    table = $('#data-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('admin.user.index') }}",
      language: {
        search: "_INPUT_",
        searchPlaceholder: "Search users..."
      },
      columns: [{
          data: 'img',
          name: 'img',
          orderable: false,
          searchable: false,
          render: function(data) {
            return data ? data : '<div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white" style="width:30px;height:30px;"><i class="bi bi-person"></i></div>';
          }
        },
        {
          data: 'id',
          name: 'id'
        },
        {
          data: 'first_name',
          name: 'first_name'
        },
        {
          data: 'last_name',
          name: 'last_name'
        },
        {
          data: 'email',
          name: 'email'
        },
        {
          data: 'phone',
          name: 'contact'
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

  // delete user
  function destroy(url, id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to delete this user?",
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
                // toastr.success(result.message); // Assuming toastr is loaded
                table.ajax.reload();
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