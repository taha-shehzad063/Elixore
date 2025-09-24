<div class="row" id="product-grid">
    @forelse($products as $product)
        <div class="product-col mb-4">
            <div class="single-product ">
                <div class="product-img">
                  @php
    $imageUrl = 'https://placehold.co/600x400/EFEFEF/AAAAAA?text=No+Image';

    if ($product->galleries->isNotEmpty()) {
        $imagePath = $product->galleries->first()->image;

        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            // ✅ External URL
            $imageUrl = $imagePath;
        } else {
            $imageUrl = asset($imagePath); // fallback for local/public path
        }
    }
@endphp

<img
    class="card-img"
    src="{{ $imageUrl }}"
    style="height: 250px; width: 250px; object-fit: cover;"
    alt="{{ $product->name }}"
    onerror="this.onerror=null;this.src='https://placehold.co/600x400/EFEFEF/AAAAAA?text=Image+Error';"
/>

                    <div class="p_icon product-icons">
                        <a class="no-dark4" href="{{ route('product.details', $product->slug) }}">
                            <i class="ti-eye"></i>
                        </a>
                        <a class="wishlist-btn no-dark4" href="#" data-url="{{ route('wishlist.add') }}" data-id="{{ $product->id }}" ><i class="ti-heart"></i></a>
                        <a class="cart-btn no-dark4" data-id="{{ $product->id }}" href="{{ route('cart.add') }}"><i class="ti-shopping-cart "></i></a>
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
                <span class="mr-4">{{ number_format($product->price, 2) }}</span>
                @if ($product->discount_price)
                  <del>{{ number_format($product->discount_price, 2) }}</del>
                @endif
              </div>
                </div>
            </div>

            <div class="row d-sm-none d-md-none">
                <div class="product-img col-6" style"max-width: 100%; !important,flex:none!important">
                  @php
    $imageUrl = 'https://placehold.co/600x400/EFEFEF/AAAAAA?text=No+Image';

    if ($product->galleries->isNotEmpty()) {
        $imagePath = $product->galleries->first()->image;

        if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
            // ✅ External URL
            $imageUrl = $imagePath;
        } else {
            $imageUrl = asset($imagePath); // Local/public path
        }
    }
@endphp

                   <a href="{{ route('product.details', $product->slug) }}">
                 <img
                        class="card-img"
                        src="{{ $imageUrl }}"
                        style="height: 150px; width: 100%; object-fit: cover;"
                        alt="{{ $product->name }}"
                        onerror="this.onerror=null;this.src='https://placehold.co/600x400/EFEFEF/AAAAAA?text=Image+Error';"
                    /></a>
                 
                </div>
                <div class="product-btm col-5">
                    <a href="{{ route('product.details', $product->slug) }}" class="d-block">
                        <h4>{{ $product->name }}</h4>
                    </a>
                    <p class="product-desc d-none">{{ Str::limit(strip_tags($product->description), 100) }}       </p>
                   
                        
             
                   
                   <div class="mt-3">
                <span class="mr-4">{{ number_format($product->price, 2) }}</span>
                @if ($product->discount_price)
                  <del>{{ number_format($product->discount_price, 2) }}</del>
                @endif
              </div>
                       <div class="p_icon product-icons d-flex justify-content-center gap-2" style="margin-top:10px;">
    <!-- View -->
    <a href="{{ route('product.details', $product->slug) }}" 
       style="background-color:#71cd14; color:#fff; width:40px; height:40px; display:flex; align-items:center; justify-content:center; border-radius:50%; text-decoration:none; transition:all 0.3s ease;">
        <i class="ti-eye"></i>
    </a>

    <!-- Wishlist -->
    <a class="wishlist-btn" href="#" 
       data-url="{{ route('wishlist.add') }}" data-id="{{ $product->id }}"
       style="background-color:#71cd14; color:#fff; width:40px; height:40px; display:flex; align-items:center; justify-content:center; border-radius:50%; text-decoration:none; transition:all 0.3s ease;">
        <i class="ti-heart"></i>
    </a>

    <!-- Cart -->
    <a class="cart-btn" data-id="{{ $product->id }}" href="{{ route('cart.add') }}"
       style="background-color:#71cd14; color:#fff; width:40px; height:40px; display:flex; align-items:center; justify-content:center; border-radius:50%; text-decoration:none; transition:all 0.3s ease;">
        <i class="ti-shopping-cart"></i>
    </a>
</div>

<script>
    // Simple hover effect for inline styles
    document.querySelectorAll('.p_icon a').forEach(btn => {
        btn.addEventListener('mouseenter', () => btn.style.backgroundColor = '#5eaa11');
        btn.addEventListener('mouseleave', () => btn.style.backgroundColor = '#71cd14');
    });
</script>

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
                <h5 class="pagination-info" aria-live="polite">
                    Page {{ $products->currentPage() }} of {{ $products->lastPage() }}
                </h5>
                <ul class="pagination aliexpress-pagination">
                    @if ($products->onFirstPage())
                        <li class="page-item disabled" aria-disabled="true">
                            <h5 class="page-link" aria-hidden="true">
                                <i class="ti-angle-left"></i>
                            </h5>
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
                                <h5 class="page-link ellipsis">...</h5>
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
                                <h5 class="page-link ellipsis">...</h5>
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
                            <h5 class="page-link" aria-hidden="true">
                                <i class="ti-angle-right"></i>
                            </h5>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </div>
</div>
