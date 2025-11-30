@extends('admin.layouts.main')
@section('content')
@section('title', 'Create Blog')

    @push('style')
        <!-- summernote -->
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/summernote/summernote-bs4.min.css') }}">
        <style>
            .avtar_img {
                height: 160px;
                width: 160px;
                object-fit: contain;
                border-radius: 20px;
            }

            .avtar {
                border: 1px solid #ced4da;
                border-radius: 10px;
                width: fit-content;
                padding: 10px;
                text-align: center;
                position: relative;
                margin: 0 auto;
            }

            .avtar label {
                position: absolute;
                bottom: -10px;
                right: -10px;
                background: var(--primary);
                color: white;
                padding: 8px;
                border-radius: 50%;
                cursor: pointer;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
                transition: all 0.3s ease;
            }

            .avtar label:hover {
                transform: scale(1.1);
            }

            .avtar_input {
                opacity: 0;
                height: 0px;
                position: absolute;
            }
        </style>
    @endpush

    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Blog</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.blog.index') }}">Blogs list</a></li>
                        <li class="breadcrumb-item active">Create Blog</li>
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
                            Create Blog
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('admin.blog.store') }}" data-action="redirect" class="row formaction"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="_method" value="POST">

                            <div class="col-md-12 row">
                                <div class="col-md-4" style="justify-items: center;">
                                    <div class="avtar">
                                        <img src="{{ getImage('') }}" class="avtar_img" />
                                        <label for="image" title="Change Image"><i class="far fa-edit"></i></label>
                                    </div>
                                    <input type="file" name="image" class="avtar_input" id="image"
                                        accept="image/png, image/webp, image/jpeg" />
                                </div>

                                <div class="col-md-8 row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Title <span class="error">*</span></label>
                                            <input type="text" class="form-control" name="title" placeholder="Title" />
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Short Description</label>
                                            <textarea class="form-control" name="short_description"
                                                placeholder="Short Description" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Content <span class="error">*</span></label>
                                    <textarea class="form-control" id="summernote" name="content" placeholder="Content"
                                        rows="10"></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Status <span class="error">*</span></label>
                                    <select class="form-control" name="status">
                                        <option value="">Select Status</option>
                                        @foreach (config('const.blog_status') as $status)
                                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
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

            $('.avtar_input').on('change', function (event) {
                var input = event.target;
                var image = $('.avtar_img');
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        image.attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            })
        </script>
    @endpush
@endsection