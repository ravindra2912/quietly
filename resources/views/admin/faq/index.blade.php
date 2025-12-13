@extends('admin.layouts.main')
@section('content')
@section('title', 'FAQs List')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/jquery.dataTables.min.css') }}" />
@endpush

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="h4 mb-0">FAQs List</h2>
  <ol class="breadcrumb m-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">FAQs List</li>
  </ol>
</div>

<section class="content">
  <div class="row">
    <div class="col-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">FAQs List</h6>
          <a href="{{ route('admin.faq.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add FAQ</a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" cellspacing="0">
              <thead class="table-light">
                <tr>
                  <th>Question</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@push('js')
<!-- DataTables -->
<script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
<!-- Sweet Alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
  $(function() {
    var table = $('#data-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('admin.faq.index') }}",
      language: {
        search: "_INPUT_",
        searchPlaceholder: "Search faqs..."
      },
      columns: [{
          data: 'question',
          name: 'question'
        },
        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: false
        }
      ]
    });
  });

  // delete
  function destroy(url, id) {
    Swal.fire({
        title: 'Are you sure?',
        icon: 'error',
        html: "You want to delete this FAQ?",
        allowOutsideClick: false,
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        customClass: {
          confirmButton: 'btn btn-danger me-2',
          cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
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
                $('#data-table').DataTable().ajax.reload();
              } else {
                toastr.error(result.message);
              }
              $('.btn_delete-' + id + ' #buttonText').removeClass('d-none');
              $('.btn_delete-' + id + ' #loader').addClass('d-none');
              $('.btn_delete-' + id).prop('disabled', false);
            },
            error: function(e) {
              toastr.error('Something went wrong');
              console.log(e);
              $('.btn_delete-' + id + ' #buttonText').removeClass('d-none');
              $('.btn_delete-' + id + ' #loader').addClass('d-none');
              $('.btn_delete-' + id).prop('disabled', false);
            }
          });
        }
      })
  }
</script>
@endpush
@endsection