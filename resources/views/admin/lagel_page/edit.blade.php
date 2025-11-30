@extends('admin.layouts.main')
@section('content')
@section('title', 'Edit Legal Page')

  @push('style')
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/summernote/summernote-bs4.min.css') }}">
  @endpush

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Lagel Pages</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.lagel-pages') }}">Lagel Pages</a></li>
            <li class="breadcrumb-item active">Lagel Pages</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->


  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-outline card-info">
          <div class="card-header">
            <h3 class="card-title">
              Edit Legale page
            </h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form action="{{ route('admin.lagel-pages.update', $legalData->id) }}" data-action="back"
              class="row formaction">
              @csrf
              <div class="col-md-6">
                <div class="form-group">
                  <label>Page Type <span class="error">*</span></label>
                  <select class="form-control" name="type">
                    <option value="">Select Page Type</option>
                    @foreach (config('const.legal_page_type') as $type)
                      <option value="{{ $type }}" {{ $legalData->page_type == $type ? 'selected' : '' }}>
                        {{ preg_replace('/(?<!\ )[A-Z]/', ' $0', $type) }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Description <span class="error">*</span></label>
                  <textarea id="summernote" name="description">{{ $legalData->description }} </textarea>
                </div>
              </div>
              <div class="col-sm-12 text-right">
                <button class="btn btn-danger" type="button" onclick="history.back()">Back</button>
                <button class="btn btn-primary btn_action" type="submit">
                  <span id="loader" class="spinner-border spinner-border-sm d-none" role="status"
                    aria-hidden="true"></span>
                  <span id="buttonText">Submit</span>
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
    <!-- Summernote -->
    <script src="{{ asset('assets/admin/plugins/summernote/summernote-bs4.min.js') }}"></script>


    <script>
      $(function () {
        // Summernote
        $('#summernote').summernote({
          dialogsInBody: true,
          height: 300,
        })

      })
    </script>
  @endpush
@endsection