@extends('front.default.partials.app')

@section('content')
<style>
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        z-index: 1000;
    }
    .loading-bar {
        width: 100%;
        height: 5px;
        background: #ddd;
        position: relative;
        overflow: hidden;
    }
    .loading-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: #71cd14;
        animation: slide 1.5s infinite;
    }
    @keyframes slide {
        0% { left: -100%; }
        50% { left: 0; }
        100% { left: 100%; }
    }
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }
    .pagination .page-item .page-link {
        color: #71cd14;
        border: 1px solid #71cd14;
        margin: 0 3px;
        border-radius: 4px;
        transition: background 0.2s, color 0.2s;
    }
    .pagination .page-item.active .page-link,
    .pagination .page-item .page-link:hover {
        background: #71cd14;
        color: #fff;
        border-color: #71cd14;
    }
    .pagination .page-item.disabled .page-link {
        color: #ccc;
        background: #f9f9f9;
        border-color: #eee;
    }
    .main_btn {
        background: #71cd14;
        color: #fff;
        border: 1px solid #71cd14;
        transition: all 0.3s ease;
    }
    .main_btn:hover {
        background: #fff;
        color: #71cd14;
        border-color: #71cd14;
    }
    .search_widget .form-control {
        border-color: #71cd14;
    }
    .search_widget .input-group-append .btn {
        background: #71cd14;
        color: #fff;
        border: 1px solid #71cd14;
    }
    .search_widget .input-group-append .btn:hover {
        background: #fff;
        color: #71cd14;
    }
    .tag_cloud_widget .list .tag-link {
        display: inline-block;
        padding: 5px 10px;
        margin: 3px;
        border: 1px solid #71cd14;
        color: #71cd14;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    .tag_cloud_widget .list .tag-link:hover {
        background: #71cd14;
        color: #fff;
    }
</style>

<!--================ Start Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="container">
            <div class="banner_content d-md-flex justify-content-between align-items-center">
                <div class="mb-3 mb-md-0">
                    <h2>Blog</h2>
                    <p>Very us move be blessed multiply night</p>
                </div>
                <div class="page_link">
                    <a href="{{ route('main') }}">Home</a>
                    <a href="{{ route('blog') }}">Blog</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================ End Banner Area =================-->

<!--================ Blog Area =================-->
<section class="blog_area section_gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mb-5 mb-lg-0">
                <div class="blog_left_sidebar position-relative" id="blog-content">
                    <div class="loading-overlay" id="loading-overlay">
                        <div class="loading-bar"></div>
                    </div>
                    @include('front.default.blog_products.blog_items')
                </div>
            </div>

            <div class="col-lg-4">
                <div class="blog_right_sidebar">
                    <!-- Search -->
                    <aside class="single_sidebar_widget search_widget">
                        <form id="blog-search-form" action="{{ route('blog') }}" method="GET">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="keyword" id="blog-search-keyword" placeholder="Search Keyword">
                                    <div class="input-group-append">
                                        <button class="btn" type="submit"><i class="ti-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            <button class="main_btn rounded-0 w-100" type="submit">Search</button>
                        </form>
                    </aside>

                    <!-- Recent Posts -->
                    <aside class="single_sidebar_widget popular_post_widget ">
                        <h3 class="widget_title no-dark">Recent Posts</h3>
                        @foreach($latestBlogs as $blog)
                            <div class="media post_item no-dark">
                                <img src="{{ asset($blog->image) }}" alt="{{ $blog->name }}"
                                    style="width: 80px; height: 60px; object-fit: cover;"
                                    onerror="this.onerror=null;this.src='https://placehold.co/80x60/EFEFEF/AAAAAA?text=Image+Error';">
                                <div class="media-body">
                                    <a href="{{ route('blogs.details', $blog->slug) }}">
                                        <h3>{{ Str::limit($blog->name, 35) }}</h3>
                                    </a>
                                    <p>
                                        @if ($blog->created_at->gt(\Carbon\Carbon::now()->subMonth()))
                                            {{ $blog->created_at->diffForHumans() }}
                                        @else
                                            {{ $blog->created_at->format('F d, Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </aside>

                    <!-- Tags -->
                    <aside class="single_sidebar_widget tag_cloud_widget">
                        <h4 class="widget_title no-dark">Tag Clouds</h4>
                        <ul class="list">
                            @foreach($tags as $tag)
                                <li>
                                    <a href="{{ route('blog', ['tag' => $tag->name]) }}" class="tag-link no-dark" data-name="{{ $tag->name }}">{{ $tag->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================ End Blog Area =================-->

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
$(document).ready(function () {
    // CSRF Token Setup
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (!csrfToken) {
        console.error('CSRF token not found. Ensure <meta name="csrf-token"> is present in the layout.');
    }

    // Function to load blogs via AJAX
    function loadBlogs(url, data = {}) {
        $.ajax({
            url: url,
            type: 'GET',
            data: data,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            beforeSend: function () {
                $('#loading-overlay').show();
                $('#blog-content').css('opacity', '0.5');
            },
            success: function (response) {
                $('#blog-content').html(response);
                $('#loading-overlay').hide();
                $('#blog-content').css('opacity', '1');
                window.history.pushState({}, '', url);
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error, xhr.responseText);
                $('#loading-overlay').hide();
                $('#blog-content').css('opacity', '1');
                $('#blog-content').html('<p class="text-center">Error loading blogs. Please try again.</p>');
            }
        });
    }

    // AJAX Pagination
    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) loadBlogs(url);
    });

    // AJAX Tag Click
    $(document).on('click', '.tag-link', function (e) {
        e.preventDefault();
        const tag = $(this).data('name');
        const url = "{{ route('blog') }}?tag=" + encodeURIComponent(tag);
        loadBlogs(url);
    });

    // AJAX Search
    $('#blog-search-form').on('submit', function (e) {
        e.preventDefault();
        const keyword = $('#blog-search-keyword').val().trim();
        const url = "{{ route('blog') }}?keyword=" + encodeURIComponent(keyword);
        loadBlogs(url);
    });

    // Debug: Check jQuery
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded.');
    } else {
    }
});
</script>
