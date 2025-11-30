@extends('front.layouts.main', ['seo' => [
'title' => $blog->title,
'description' => Str::limit($blog->short_description, 160),
'keywords' => '',
'city' => '',
'state' => '',
'position' => ''
]
])

@section('content')

<section class="py-5">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="mb-4 text-center">
                    <div class="mb-3">
                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">{{ $blog->published_at->format('M d, Y') }}</span>
                    </div>
                    <h1 class="fw-bold display-5 mb-4">{{ $blog->title }}</h1>
                </div>

                @if($blog->image)
                <div class="mb-5">
                    <img src="{{ getImage($blog->image) }}" class="img-fluid rounded-4 shadow-sm w-100" alt="{{ $blog->title }}">
                </div>
                @endif

                <div class="blog-content fs-5 text-muted mb-5">
                    {!! $blog->content !!}
                </div>

                <hr class="my-5">

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-dark"><i class="bi bi-arrow-left me-2"></i> Back to Blog</a>
                    
                    <div class="d-flex gap-2">
                        <span class="text-muted me-2">Share:</span>
                        <a href="#" class="text-muted"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-muted"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-muted"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($latestBlogs->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h3 class="fw-bold">More from our blog</h3>
        </div>
        <div class="row g-4">
            @foreach ($latestBlogs as $latestBlog)
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    @if($latestBlog->image)
                    <img src="{{ getImage($latestBlog->image) }}" class="card-img-top" alt="{{ $latestBlog->title }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="mb-2">
                            <small class="text-muted">{{ $latestBlog->published_at->format('M d, Y') }}</small>
                        </div>
                        <h5 class="fw-semibold mb-3">
                            <a href="{{ route('blog.show', $latestBlog->slug) }}" class="text-decoration-none text-dark">{{ $latestBlog->title }}</a>
                        </h5>
                        <a href="{{ route('blog.show', $latestBlog->slug) }}" class="fw-semibold text-primary text-decoration-none mt-auto">Read more <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
