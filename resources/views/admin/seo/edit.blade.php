@extends('admin.layouts.main')
@section('content')
@section('title', 'Edit Seo')

@push('style')
<!-- summernote -->

@endpush

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Create Seo</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.seo.index') }}">Seo list</a></li>
          <li class="breadcrumb-item active">Edit Seo</li>
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
            Edit Seo
          </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <form action="{{ route('admin.seo.update', $seo->id) }}" data-action="redirect" class="row formaction">
            @csrf
            <input type="hidden" name="_method" value="PATCH">

            <div class="col-md-12">
              <div class="form-group">
                <label>URL <span class="error">*</span></label>
                <input type="text" class="form-control" name="site_url" value="{{ $seo->site_url }}" placeholder="URL" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Meta Title <span class="error">*</span></label>
                <input type="text" class="form-control" name="meta_title" value="{{ $seo->meta_title }}" placeholder="Meta Title" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Meta Description <span class="error">*</span></label>
                <textarea class="form-control" name="meta_description" placeholder="Meta Description">{{ $seo->meta_description }}</textarea>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Meta Keywords <span class="error">*</span></label>
                <textarea class="form-control" name="meta_keywords" placeholder="Meta Keywords">{{ $seo->meta_keywords }}</textarea>
              </div>
            </div>

            <div class="col-sm-12 text-right">
              <button class="btn btn-danger" type="button" onclick="history.back()">Back</button>
              <button class="btn btn-primary btn_action" type="submit">
                <span id="loader" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
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


@endpush
@endsection