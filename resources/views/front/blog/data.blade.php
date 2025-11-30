@foreach ($blogs as $blog)
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm">
            @if($blog->image)
                <img src="{{ getImage($blog->image) }}" class="card-img-top" alt="{{ $blog->title }}"
                    style="height: 200px; object-fit: cover;">
            @endif
            <div class="card-body p-4 d-flex flex-column">
                <div class="mb-2">
                    <small class="text-muted">{{ $blog->published_at->format('M d, Y') }}</small>
                </div>
                <h5 class="fw-semibold mb-3">
                    <a href="{{ route('blog.show', $blog->slug) }}"
                        class="text-decoration-none text-dark">{{ $blog->title }}</a>
                </h5>
                <p class="text-muted mb-4 flex-grow-1">{{ Str::limit($blog->short_description, 100) }}</p>
                <a href="{{ route('blog.show', $blog->slug) }}" class="fw-semibold text-primary text-decoration-none">Read
                    more <i class="bi bi-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
@endforeach