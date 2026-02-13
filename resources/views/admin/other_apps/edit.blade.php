@extends('admin.layouts.main')
@section('content')
@section('title', 'Edit Other App')

@push('style')
<style>
    .avtar_img {
        height: 160px;
        width: 160px;
        object-fit: cover;
        border-radius: 10px;
        border: 3px solid #fff;
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
    }

    .avtar {
        position: relative;
        width: fit-content;
        margin: 0 auto;
    }

    .avtar label {
        position: absolute;
        bottom: -10px;
        right: -10px;
        background: var(--primary-color);
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .avtar label:hover {
        transform: scale(1.1);
        background: var(--bs-primary-dark);
    }

    .avtar_input {
        display: none;
    }
</style>
@endpush

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Other App</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.other-apps.index') }}" class="text-decoration-none">Other Apps list</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Other App</li>
            </ol>
        </nav>
    </div>
</div>

<section class="content">
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white">
            <h5 class="m-0 font-weight-bold text-primary">Edit App Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.other-apps.update', $otherApp->id) }}" data-action="redirect" class="formaction"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="text-center mb-4">
                            <div class="avtar">
                                <img src="{{ getImage($otherApp->image) }}" class="avtar_img" id="preview_image" />
                                <label for="image" title="Change Image"><i class="bi bi-pencil-fill"></i></label>
                                <input type="file" name="image" class="avtar_input" id="image"
                                    accept="image/png, image/webp, image/jpeg" />
                            </div>
                            <div class="mt-2 text-muted small">Recommended size: 500x500px</div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ $otherApp->name }}" placeholder="App Name" required />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" name="status" required>
                                        <option value="active" {{ $otherApp->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="in-active" {{ $otherApp->status == 'in-active' ? 'selected' : '' }}>In-active</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-secondary" type="button" onclick="history.back()">Back</button>
                    <button class="btn btn-primary btn_action" type="submit">
                        <span id="loader" class="spinner-border spinner-border-sm d-none" role="status"
                            aria-hidden="true"></span>
                        <span id="buttonText">Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@push('js')
<script>
    $('#image').on('change', function(event) {
        var input = event.target;
        var image = $('#preview_image');
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