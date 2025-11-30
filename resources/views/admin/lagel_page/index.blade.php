
@extends('admin.layouts.main')
@section('content')
@section('title', 'Legal Page')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/dist/css/jquery.dataTables.css') }}"/>
@endpush


    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Lagel Pages</li>
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
                <h3 class="card-title">Responsive Hover Table</h3>

                <!-- <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div> -->
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive">
                <table class="table table-hover text-nowrap" id="data-table">
                  <thead>
                    <tr>
                      <th>Page type</th>
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
  <script type="text/javascript">

$(function () {
  var table = $('#data-table').DataTable({

      processing: true,
      serverSide: true,
      ajax: "{{ route('admin.lagel-pages') }}",
      columns: [
          {data: 'page_type', name: 'page_type', orderable: false, searchable: false},
          {data: 'action', name: 'action', orderable: false, searchable: false},
      ]
  });

    

});

</script>
  @endpush
    @endsection
