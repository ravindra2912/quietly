@extends('admin.layouts.main')
@section('content')
@section('title', 'Edit Faq')

@push('style')
<!-- summernote -->

@endpush

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Create Faq</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">Faqs list</a></li>
          <li class="breadcrumb-item active">Create Faq</li>
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
            Create Faq
          </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <form action="{{ route('admin.faq.store') }}" data-action="redirect" class="row formaction">
            @csrf
            <input type="hidden" name="_method" value="POST">

            <div class="col-md-12">
              <div class="form-group">
                <label>Question <span class="error">*</span></label>
                <input type="text" class="form-control" name="question" placeholder="Question" />
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label>Answer <span class="error">*</span></label>
                <textarea class="form-control" name="answer" placeholder="Answer"></textarea>
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

<script>
  $('.avtar_input').on('change', function(event) {
    var input = event.target;
    var image = $('.avtar_img');
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        image.attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  })
</script>

@endpush
@endsection