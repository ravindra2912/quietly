@extends('front.layouts.main', [
    'seo' => [
        'title' => 'Blog',
        'description' => 'Latest updates and articles from Quietly',
        'keywords' => 'blog, updates, articles',
        'city' => '',
        'state' => '',
        'position' => ''
    ]
])

@section('content')

<section class="hero-gradient py-5">
    <div class="container py-4 text-center text-white">
        <h1 class="display-4 fw-bold mb-3">Our Blog</h1>
        <p class="lead mb-0 text-white-75">Insights, updates, and stories from the Quietly team.</p>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4" id="blog-data">
            @include('front.blog.data')
        </div>
        <div class="ajax-load text-center mt-5" style="display:none">
            <p><img src="{{ asset('assets/img/loader.gif') }}" width="50"> Loading More post...</p>
        </div>
    </div>
</section>

@endsection

@push('js')
<script>
    var page = 1;
    var noMoreData = false;
    var isLoading = false;

    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            if (!noMoreData && !isLoading) {
                page++;
                loadMoreData(page);
            }
        }
    });

    function loadMoreData(page){
        isLoading = true;
        $.ajax(
            {
                url: '?page=' + page,
                type: "get",
                beforeSend: function()
                {
                    $('.ajax-load').show();
                }
            })
            .done(function(data)
            {
                isLoading = false;
                if(data.html.trim() == ""){
                    $('.ajax-load').html("No more records found");
                    noMoreData = true;
                    return;
                }
                $('.ajax-load').hide();
                $("#blog-data").append(data.html);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                  isLoading = false;
                  alert('server not responding...');
            });
    }
</script>
@endpush
