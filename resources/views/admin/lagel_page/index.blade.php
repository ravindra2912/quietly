@extends('admin.layouts.main')
@section('content')
@section('title', 'Legal Pages')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/jquery.dataTables.min.css') }}" />
@endpush

<div class="d-flex justify-content-between align-items-center mb-4">
  <h2 class="h4 mb-0">Legal Pages</h2>
  <ol class="breadcrumb m-0">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Legal Pages</li>
  </ol>
</div>

<section class="content">
  <div class="row">
    <div class="col-12">
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Legal Pages List</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <!-- Added table-striped, removed table-bordered redundancy if desired, usually kept in BS5 -->
            <table class="table table-bordered table-striped table-hover" id="data-table" width="100%" cellspacing="0">
              <thead class="table-light">
                <tr>
                  <th>Page Type</th>
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
<script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
  $(function() {
    var table = $('#data-table').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('admin.lagel-pages') }}",
      language: {
        search: "_INPUT_",
        searchPlaceholder: "Search pages..."
      },
      columns: [{
          data: 'page_type',
          name: 'page_type',
          orderable: false,
          searchable: false,
          render: function(data) {
            return data ? data.replace('_', ' ').toUpperCase() : 'N/A';
          }
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
</script>
@endpush
@endsection