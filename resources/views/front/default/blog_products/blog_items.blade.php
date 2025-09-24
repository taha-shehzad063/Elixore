<div class="row">
    @forelse ($blogs as $blog)
        <article class="blog_item">
            <div class="blog_item_img">
                <img class="card-img rounded-0" src="{{ asset($blog->image) }}" alt="{{ $blog->name }}"
                    onerror="this.onerror=null;this.src='https://placehold.co/600x400/EFEFEF/AAAAAA?text=Image+Error';">
                <a href="{{ route('blogs.details', $blog->slug) }}" class="blog_item_date">
                    <h3>{{ $blog->created_at->format('d') }}</h3>
                    <p>{{ $blog->created_at->format('M') }}</p>
                </a>
            </div>
            <div class="blog_details">
                <a class="d-inline-block" href="{{ route('blogs.details', $blog->slug) }}">
                    <h2>{{ $blog->name }}</h2>
                </a>
<p>{{ Str::limit(strip_tags(preg_replace('/<img[^>]*>/i', '', $blog->description)), 120, ' [read more]') }}</p>
                <ul class="blog-info-link">
                    <li><i class="ti-user"></i> {{ $blog->tags->pluck('name')->implode(', ') ?: 'Uncategorized' }}</li>
                    <li><i class="ti-comments"></i> {{ $blog->comments->count() }} Comments</li>
                </ul>
            </div>
        </article>
    @empty
        <div class="col-12 text-center">
            <p>No blogs found matching your criteria.</p>
        </div>
    @endforelse
</div>

<div class="blog-pagination justify-content-center d-flex mt-4">
    <nav aria-label="Blog pagination">
        <div class="pagination-wrapper">
            <span class="pagination-info " aria-live="polite">
                Page {{ $blogs->currentPage() }} of {{ $blogs->lastPage() }}
            </span>
            <ul class="pagination aliexpress-pagination no-dark2">
                @if ($blogs->onFirstPage())
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" aria-hidden="true">
                            <i class="ti-angle-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $blogs->previousPageUrl() }}" aria-label="Previous">
                            <i class="ti-angle-left"></i>
                        </a>
                    </li>
                @endif

                @php
                    $currentPage = $blogs->currentPage();
                    $lastPage = $blogs->lastPage();
                    $startPage = max(1, $currentPage - 1);
                    $endPage = min($lastPage, $currentPage + 1);

                    // Adjust startPage if near the end to always show 3 pages if possible
                    if ($endPage - $startPage < 2 && $lastPage >= 3) {
                        $startPage = max(1, $endPage - 2);
                    }
                @endphp

                @if ($startPage > 1)
                    <li class="page-item">
                        <a class="page-link " href="{{ $blogs->url(1) }}">1</a>
                    </li>
                    @if ($startPage > 2)
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link ">...</span>
                        </li>
                    @endif
                @endif

                @foreach (range($startPage, $endPage) as $page)
                    <li class="page-item {{ $currentPage == $page ? 'active' : '' }}" aria-current="{{ $currentPage == $page ? 'page' : '' }}">
                        <a class="page-link " href="{{ $blogs->url($page) }}">{{ $page }}</a>
                    </li>
                @endforeach

                @if ($endPage < $lastPage)
                    @if ($endPage < $lastPage - 1)
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link ">...</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link " href="{{ $blogs->url($lastPage) }}">{{ $lastPage }}</a>
                    </li>
                @endif

                @if ($blogs->hasMorePages())
                    <li class="page-item">
                        <a class="page-link " href="{{ $blogs->nextPageUrl() }}" aria-label="Next">
                            <i class="ti-angle-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link " aria-hidden="true">
                            <i class="ti-angle-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
</div>

<style>
    .pagination-wrapper {
        display: flex;
        align-items: center;
        gap: 20px;
        justify-content: center;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    }

    .pagination-info {
        font-size: 14px;
        color: #555;
        font-weight: 500;
    }

    .aliexpress-pagination {
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 0;
    }

    .aliexpress-pagination .page-item {
        display: inline-flex;
    }

    .aliexpress-pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2px solid #71cd14;
        background-color: #fff;
        color: black;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        line-height: 1;
    }

    .aliexpress-pagination .page-item.active .page-link {
        background-color: #71cd14;
        color: #fff;
        border-color: #71cd14;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
    }

    .aliexpress-pagination .page-link:hover:not(.disabled .page-link) {
        background-color: #71cd14;
        color: #fff;
        border-color: #71cd14;
        transform: scale(1.1);
    }

    .aliexpress-pagination .page-item.disabled .page-link {
        background-color: #f8f8f8;
        border-color: #ddd;
        color: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    .aliexpress-pagination .page-link i {
        font-size: 14px;
    }

    .aliexpress-pagination .page-item.disabled .page-link.ellipsis {
        border: none;
        background: none;
        color: #555;
        font-size: 16px;
        font-weight: 400;
        cursor: default;
    }

    /* Focus styles for accessibility */
    .aliexpress-pagination .page-link:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(113, 205, 20, 0.3);
        border-color: #5abb10;
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .pagination-wrapper {
            flex-direction: column;
            gap: 10px;
        }

        .pagination-info {
            font-size: 13px;
        }

        .aliexpress-pagination {
            gap: 4px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .aliexpress-pagination .page-link {
            width: 34px;
            height: 34px;
            font-size: 13px;
        }

        .aliexpress-pagination .page-link i {
            font-size: 12px;
        }

        .aliexpress-pagination .page-item.disabled .page-link.ellipsis {
            font-size: 14px;
        }
    }
</style>