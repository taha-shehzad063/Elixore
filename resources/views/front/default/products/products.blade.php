<div class="row" id="product-grid">
    @forelse($products as $product)
        <div class="product-col mb-4">
            <div class="single-product ">
                <div class="product-img">
                    @php
                        $imageUrl = 'https://placehold.co/600x400/EFEFEF/AAAAAA?text=No+Image';
                        if ($product->galleries->isNotEmpty()) {
                            $imageUrl = asset('storage/' . $product->galleries->first()->image);
                        }
                    @endphp
                    <img
                        class="card-img"
                        src="{{ $imageUrl }}"
                        style="height: auto; width: 100%; object-fit: cover;"
                        alt="{{ $product->name }}"
                        onerror="this.onerror=null;this.src='https://placehold.co/600x400/EFEFEF/AAAAAA?text=Image+Error';"
                    />
                    <div class="p_icon product-icons">
                        <a href="{{ route('product.details', $product->slug) }}">
                            <i class="ti-eye"></i>
                        </a>
                        <a class="wishlist-btn" href="#" data-url="{{ route('wishlist.add') }}" data-id="{{ $product->id }}" ><i class="ti-heart"></i></a>
                        <a class="cart-btn" data-id="{{ $product->id }}" href="{{ route('cart.add') }}"><i class="ti-shopping-cart "></i></a>
                    </div>
                </div>
                <div class="product-btm">
                    <a href="{{ route('product.details', $product->slug) }}" class="d-block">
                        <h4>{{ $product->name }}</h4>
                    </a>
                    <p class="product-desc d-none">{{ Str::limit(strip_tags($product->description), 100) }}       </p>
                        <a href="{{ route('product.details', $product->slug) }}" class="btn btn-sm icon-action d-none" title="View Product">
                        <i class="ti-eye"></i>
                    </a>
                                                <a href="#" data-url="{{ route('wishlist.add') }}" data-id="{{ $product->id }}" class="btn wishlist-btn btn-sm icon-action" title="Add to Wishlist">
                                    <i class="ti-heart"></i>
                                </a>

                    <a href="{{ route('cart.add') }}"data-id="{{ $product->id }}" class="btn cart-btn btn-sm icon-action d-none" title="Add to Cart">
                        <i class="ti-shopping-cart"></i>
                    </a>
                        
             
                   
                    <div class="mt-3">
                        <span class="mr-4">${{ number_format($product->price, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="row d-sm-none d-md-none">
<div class="product-img col-6" style"max-width: 100%; !important,flex:none!important">
                    @php
                        $imageUrl = 'https://placehold.co/600x400/EFEFEF/AAAAAA?text=No+Image';
                        if ($product->galleries->isNotEmpty()) {
                            $imageUrl = asset('storage/' . $product->galleries->first()->image);
                        }
                    @endphp
                    <img
                        class="card-img"
                        src="{{ $imageUrl }}"
                        style="height: 150px; width: 100%; object-fit: cover;"
                        alt="{{ $product->name }}"
                        onerror="this.onerror=null;this.src='https://placehold.co/600x400/EFEFEF/AAAAAA?text=Image+Error';"
                    />
                    <div class="p_icon product-icons">
                        <a href="{{ route('product.details', $product->slug) }}">
                            <i class="ti-eye"></i>
                        </a>
                        <a class="wishlist-btn" href="#" data-url="{{ route('wishlist.add') }}" data-id="{{ $product->id }}" ><i class="ti-heart"></i></a>
                        <a class="cart-btn" data-id="{{ $product->id }}" href="{{ route('cart.add') }}"><i class="ti-shopping-cart "></i></a>
                    </div>
                </div>
                <div class="product-btm col-5">
                    <a href="{{ route('product.details', $product->slug) }}" class="d-block">
                        <h4>{{ $product->name }}</h4>
                    </a>
                    <p class="product-desc d-none">{{ Str::limit(strip_tags($product->description), 100) }}       </p>
                        <a href="{{ route('product.details', $product->slug) }}" class="btn btn-sm icon-action d-none" title="View Product">
                        <i class="ti-eye"></i>
                    </a>
                                                <a href="#" data-url="{{ route('wishlist.add') }}" data-id="{{ $product->id }}" class="btn wishlist-btn btn-sm icon-action" title="Add to Wishlist">
                                    <i class="ti-heart"></i>
                                </a>

                    <a href="{{ route('cart.add') }}"data-id="{{ $product->id }}" class="btn cart-btn btn-sm icon-action d-none" title="Add to Cart">
                        <i class="ti-shopping-cart"></i>
                    </a>
                        
             
                   
                    <div class="mt-3">
                        <span class="mr-4">${{ number_format($product->price, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center">
            <p>No products found matching your criteria.</p>
        </div>
    @endforelse
</div>

<div class="row justify-content-center mt-4">
    <div class="col-auto">
        <nav aria-label="Page navigation">
            <div class="pagination-wrapper">
                <span class="pagination-info" aria-live="polite">
                    Page {{ $products->currentPage() }} of {{ $products->lastPage() }}
                </span>
                <ul class="pagination aliexpress-pagination">
                    @if ($products->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link" aria-hidden="true">
                                <i class="ti-angle-left"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                                <i class="ti-angle-left"></i>
                            </a>
                        </li>
                    @endif

                    @php
                        $currentPage = $products->currentPage();
                        $lastPage = $products->lastPage();
                        $startPage = max(1, $currentPage - 1);
                        $endPage = min($lastPage, $currentPage + 1);

                        // Adjust startPage if near the end to always show 3 pages if possible
                        if ($endPage - $startPage < 2 && $lastPage >= 3) {
                            $startPage = max(1, $endPage - 2);
                        }
                    @endphp

                    @if ($startPage > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->url(1) }}">1</a>
                        </li>
                        @if ($startPage > 2)
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link ellipsis">...</span>
                            </li>
                        @endif
                    @endif

                    @foreach (range($startPage, $endPage) as $page)
                        <li class="page-item {{ $currentPage == $page ? 'active' : '' }}" aria-current="{{ $currentPage == $page ? 'page' : '' }}">
                            <a class="page-link" href="{{ $products->url($page) }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    @if ($endPage < $lastPage)
                        @if ($endPage < $lastPage - 1)
                            <li class="page-item disabled" aria-disabled="true">
                                <span class="page-link ellipsis">...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->url($lastPage) }}">{{ $lastPage }}</a>
                        </li>
                    @endif

                    @if ($products->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">
                                <i class="ti-angle-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled" aria-disabled="true">
                            <span class="page-link" aria-hidden="true">
                                <i class="ti-angle-right"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
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
        color: #71cd14;
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