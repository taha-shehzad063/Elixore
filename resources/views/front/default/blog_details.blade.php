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
    .social-icons a {
        color: #71cd14;
        margin: 0 5px;
        transition: all 0.3s ease;
    }
    .social-icons a:hover {
        color: #fff;
        background: #71cd14;
        border-radius: 50%;
    }
    .blog-info-link a {
        color: #71cd14;
    }
    .blog-info-link a:hover {
        color: #555;
    }
    .comment-form .form-control {
        border-color: #71cd14;
    }
    .reply-btn {
        cursor: pointer;
        color: #71cd14;
        font-size: 14px;
        margin-left: 10px;
    }
    .reply-btn:hover {
        color: #555;
    }
    .comment-list .reply-form {
        display: none;
        margin-left: 50px;
        margin-top: 20px;
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

  
    .excert img,
    .excert video {
        height: 500px !important;
        width: 500px !important;
        max-width: 100% !important;
        object-fit: cover !important;
        display: block !important;
        margin-bottom: 15px !important;
    }




</style>

<!--================ Start Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="container">
            <div class="banner_content d-md-flex justify-content-between align-items-center">
                <div class="mb-3 mb-md-0">
                    <h2>Blog Details</h2>
                    <p>Very us move be blessed multiply night</p>
                </div>
                <div class="page_link">
                    <a href="{{ route('main') }}">Home</a>
                    <a href="{{ route('blog') }}">Blog</a>
                    <a href="{{ route('blogs.details', $blogs->slug) }}">Blog Details</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================ End Banner Area =================-->

<!--================ Single Post Area =================-->
<section class="blog_area single-post-area section_gap">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 posts-list">
                <div class="single-post">
                    <div class="feature-img">
                        <img class="img-fluid" src="{{ asset($blogs->image) }}" alt="{{ $blogs->name }}"
                            onerror="this.onerror=null;this.src='https://placehold.co/600x400/EFEFEF/AAAAAA?text=Image+Error';">
                    </div>
                    <div class="blog_details">
                        <h2>{{ $blogs->name }}</h2>
                        <ul class="blog-info-link mt-3 mb-4">
                            <li><a href="#"><i class="ti-user"></i> {{ $blogs->tags->pluck('name')->implode(', ') ?: 'Uncategorized' }}</a></li>
                            <li><a href="#"><i class="ti-comments"></i> <span class="comment-count-number">{{ $totalComments }}</span> Comments</a></li>
                        </ul>
    <div class="excert">{!! $blogs->description !!}</div>
                    </div>
                </div>
                <div class="navigation-top">
                    <div class="d-sm-flex justify-content-between text-center">
                        <p class="like-info"><span class="align-middle"><i class="ti-heart"></i></span> <span id="like-count">{{ $likeCount }}</span> people like this</p>
                        <button id="like-button" class="btn main_btn" data-blog-id="{{ $blogs->id }}" {{ $isLiked ? 'disabled' : '' }}>{{ $isLiked ? 'Liked' : 'Like' }}</button>
                        <div class="col-sm-4 text-center my-2 my-sm-0">
                            <p class="comment-count"><span class="align-middle"><i class="ti-comment"></i></span> <span class="comment-count-number">{{ $totalComments }}</span> Comments</p>
                        </div>
                        <ul class="social-icons">
                            <li><a href="#"><i class="ti-facebook"></i></a></li>
                            <li><a href="#"><i class="ti-twitter-alt"></i></a></li>
                            <li><a href="#"><i class="ti-dribbble"></i></a></li>
                            <li><a href="#"><i class="ti-wordpress"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="blog-author ">
                    <div class="media align-items-center no-dark">
                        <img src="{{ asset('user/images.png') }}" alt="Author" onerror="this.onerror=null;this.src='https://placehold.co/80x80/EFEFEF/AAAAAA?text=Author';">
                        <div class="media-body">
                            <a href="#"><h4>Harvard Milan</h4></a>
                            <p>Second divided from form fish beast made. Every of seas all gathered use saying you're, he our dominion twon Second divided from</p>
                        </div>
                    </div>
                </div>
                <div class="comments-area" id="comments-area">
                    <h4><span class="comment-count-number">{{ $totalComments }}</span> Comments</h4>
                    @foreach($comments as $comment)
                        @include('front.default.comment', ['comment' => $comment, 'level' => 0])
                    @endforeach
                  <div class="comment-pagination d-flex justify-content-center mt-4">
    @if ($comments->lastPage() > 1)
        <nav>
            <ul class="pagination no-darkp">
                {{-- Previous Page --}}
                <li class="page-item {{ $comments->onFirstPage() ? 'disabled' : '' }}">
                    <a class="page-link comment-page-link" href="{{ $comments->previousPageUrl() }}" aria-label="Previous">
                        &laquo;
                    </a>
                </li>

                {{-- Page Numbers --}}
                @foreach (range(1, $comments->lastPage()) as $page)
                    <li class="page-item {{ $page == $comments->currentPage() ? 'active' : '' }}">
                        <a class="page-link comment-page-link" href="{{ $comments->url($page) }}">{{ $page }}</a>
                    </li>
                @endforeach

                {{-- Next Page --}}
                <li class="page-item {{ $comments->hasMorePages() ? '' : 'disabled' }}">
                    <a class="page-link comment-page-link" href="{{ $comments->nextPageUrl() }}" aria-label="Next">
                        &raquo;
                    </a>
                </li>
            </ul>
        </nav>
    @endif
</div>

                </div>
                <div class="comment-form mt-5">
                    <h4>Leave a Comment</h4>
                    <form class="form-contact comment_form" action="{{ route('blog.comment') }}" method="POST">
                        @csrf
                        <input type="hidden" name="blog_id" value="{{ $blogs->id }}">
                        <input type="hidden" name="parent_id" id="parent_id" value="">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <textarea class="form-control w-100" name="comment" cols="30" rows="9" placeholder="Write Comment" required></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" name="name" type="text" placeholder="Name" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" name="email" type="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <input class="form-control" name="website" type="text" placeholder="Website">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="main_btn">Post Comment</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="blog_right_sidebar position-relative" id="sidebar-content">
                    <div class="loading-overlay" id="loading-overlay">
                        <div class="loading-bar"></div>
                    </div>
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
                    <aside class="single_sidebar_widget popular_post_widget">
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
                                        @if (\Carbon\Carbon::parse($blog->created_at)->gt(\Carbon\Carbon::now()->subMonth()))
                                            {{ \Carbon\Carbon::parse($blog->created_at)->diffForHumans() }}
                                        @else
                                            {{ \Carbon\Carbon::parse($blog->created_at)->format('F d, Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </aside>
                    <aside class="single_sidebar_widget tag_cloud_widget">
                        <h4 class="widget_title">Tag Clouds</h4>
                        <ul class="list">
                            @foreach($tags as $tag)
                                <li>
                                    <a href="{{ route('blog', ['tag' => $tag->name]) }}" class="tag-link no-dark" data-name="{{ $tag->name }}">{{ $tag->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                    @include('front.default.suggested_blogs')
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

<!--================ End Single Post Area =================-->

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
$(document).ready(function () {
    // CSRF Token Setup
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (!csrfToken) {
        console.error('CSRF token not found. Ensure <meta name="csrf-token"> is present in the layout.');
    }

    let totalComments = {{ $totalComments }};
    const blogDetailsUrl = '{{ route('blogs.details', $blogs->slug) }}';

    // Function to load blogs via AJAX (for search and tag filtering)
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
                $('#sidebar-content').css('opacity', '0.5');
            },
            success: function (response) {
                window.location.href = url; // Redirect to blog page
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error, xhr.responseText);
                $('#loading-overlay').hide();
                $('#sidebar-content').css('opacity', '1');
                alert('Failed to load blogs.');
            }
        });
    }

    // Function to load suggested blogs via AJAX
    function loadSuggestedBlogs(url) {
        $.ajax({
            url: url,
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            beforeSend: function () {
                $('#loading-overlay').show();
                $('#sidebar-content').css('opacity', '0.5');
            },
            success: function (response) {
                $('.suggested_post_widget').replaceWith(response);
                $('#loading-overlay').hide();
                $('#sidebar-content').css('opacity', '1');
                bindSuggestedPagination();
            },
            error: function (xhr, status, error) {
                console.error('Suggested blogs AJAX error:', status, error, xhr.responseText);
                $('#loading-overlay').hide();
                $('#sidebar-content').css('opacity', '1');
                alert('Failed to load suggested blogs.');
            }
        });
    }

    // AJAX Search
    $('#blog-search-form').on('submit', function (e) {
        e.preventDefault();
        const keyword = $('#blog-search-keyword').val().trim();
        const url = "{{ route('blog') }}?keyword=" + encodeURIComponent(keyword);
        loadBlogs(url);
    });

    // AJAX Tag Click
    $(document).on('click', '.tag-link', function (e) {
        e.preventDefault();
        const tag = $(this).data('name');
        const url = "{{ route('blog') }}?tag=" + encodeURIComponent(tag);
        loadBlogs(url);
    });

    // AJAX Comment Submission
    $('.comment_form').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        const url = form.attr('action');
        const data = form.serialize();
        const isReply = form.find('input[name="parent_id"]').val() !== '';
        const parentId = form.find('input[name="parent_id"]').val();
        const commentArea = $('#comments-area');

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            beforeSend: function () {
                $('#loading-overlay').show();
                commentArea.css('opacity', '0.5');
            },
            success: function (response) {
                if (response.success) {
                    const comment = response.comment;
                    if (isReply) {
                        const parent = $(`#comment-${parentId}`);
                        const parentMargin = parseInt(parent.css('margin-left')) || 0;
                        const childMargin = parentMargin + 50;
                        const commentHtml = `
                            <div class="comment-list" id="comment-${comment.id}" style="margin-left: ${childMargin}px;">
                                <div class="single-comment justify-content-between d-flex">
                                    <div class="user justify-content-between d-flex">
                                        <div class="thumb">
                                            <img src="{{ asset('user/images.png') }}" alt="Author"
                                                onerror="this.onerror=null;this.src='https://placehold.co/50x50/EFEFEF/AAAAAA?text=Author';">
                                        </div>
                                        <div class="desc">
                                            <p class="comment">${comment.comment}</p>
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <h5><a href="#">${comment.author_name}</a></h5>
                                                    <p class="date">${comment.created_at}</p>
                                                    <a href="#" class="reply-btn" data-comment-id="${comment.id}">Reply</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="replies" id="replies-${comment.id}"></div>
                                <div class="comment-form reply-form" id="reply-form-${comment.id}">
                                    <h5 class="">Reply to ${comment.author_name}</h5>
                                    <form class="form-contact comment_form" action="{{ route('blog.comment') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="blog_id" value="{{ $blogs->id }}">
                                        <input type="hidden" name="parent_id" value="${comment.id}">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <textarea class="form-control w-100" name="comment" cols="30" rows="5" placeholder="Write Reply" required></textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="name" type="text" placeholder="Name" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input class="form-control" name="email" type="email" placeholder="Email">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <input class="form-control" name="website" type="text" placeholder="Website">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="main_btn">Submit Reply</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        `;
                        $(`#replies-${parentId}`).append(commentHtml);
                        bindReplyButtons();
                    } else {
                        location.href = blogDetailsUrl;
                    }
                    form[0].reset();
                    totalComments++;
                    updateCommentCount();
                    $('#loading-overlay').hide();
                    commentArea.css('opacity', '1');
                }
            },
            error: function (xhr, status, error) {
                console.error('Comment AJAX error:', status, error, xhr.responseText);
                $('#loading-overlay').hide();
                commentArea.css('opacity', '1');
                alert('Failed to post comment.');
            }
        });
    });

    // AJAX Like Submission
    $('#like-button').on('click', function (e) {
        e.preventDefault();
        const button = $(this);
        const blogId = button.data('blog-id');
        $.ajax({
            url: `/blogs/${blogId}/like`,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                if (response.success) {
                    $('#like-count').text(response.like_count);
                    button.text('Liked').prop('disabled', true);
                }
            },
            error: function (xhr, status, error) {
                console.error('Like AJAX error:', status, error, xhr.responseText);
                alert('Failed to like the post.');
            }
        });
    });

    // AJAX Suggested Blogs Pagination
    function bindSuggestedPagination() {
        $('.suggested-page-link').off('click').on('click', function (e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (url) loadSuggestedBlogs(url);
        });
    }
function loadComments(url) {
    $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function () {
            $('#loading-overlay').show();
            $('#comments-area').css('opacity', '0.5');
        },
        success: function (response) {
            const newCommentsHtml = $(response).find('#comments-area').html();
            $('#comments-area').html(newCommentsHtml);
            $('#loading-overlay').hide();
            $('#comments-area').css('opacity', '1');

            // Re-bind pagination clicks
            bindCommentPagination();
        },
        error: function (xhr) {
            $('#loading-overlay').hide();
            $('#comments-area').css('opacity', '1');
            console.error('Error loading comments:', xhr.responseText);
        }
    });
}

function bindCommentPagination() {
    $('.comment-page-link').off('click').on('click', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) loadComments(url);
    });
}

// Run on page load
bindCommentPagination();

    // Update comment count
    function updateCommentCount() {
        $('.comment-count-number').text(totalComments);
    }

    // Bind reply buttons
    function bindReplyButtons() {
        $('.reply-btn').off('click').on('click', function (e) {
            e.preventDefault();
            const commentId = $(this).data('comment-id');
            $('.reply-form').hide();
            $(`#reply-form-${commentId}`).show();
        });
    }

    // Initialize reply buttons and suggested pagination
    bindReplyButtons();
    bindSuggestedPagination();

    // Debug: Check jQuery
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded.');
    } else {
    }
});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.excert img, .excert video').forEach(el => {
            el.removeAttribute('style'); // remove conflicting inline styles
            el.style.height = '150px';
            el.style.width = '300px';
            el.style.objectFit = 'cover';
            el.style.display = 'block';
            el.style.marginBottom = '15px';
        });
    });
</script>
<script>
    
</script>