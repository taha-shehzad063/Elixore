@php
    use App\Models\Category;
    use App\Models\Wishlist;
    use App\Models\Cart;
    use App\Models\Product;
    use Illuminate\Support\Facades\Auth;

    $categories = Category::all();
    $wishlistCount = Auth::check() ? Wishlist::where('user_id', Auth::id())->count() : 0;
    $cart = Auth::check() ? Cart::where('user_id', Auth::id())->where('status', 'active')->first() : null;
    $cartCount = $cart && $cart->items ? $cart->items->count() : 0;
@endphp
 @php
     $trending = Product::select('products.id', 'products.name', 'products.slug', 'product_galleries.image')
        ->leftJoin('reviews', 'products.id', '=', 'reviews.product_id')
        ->leftJoin('product_galleries', function($join) {
            $join->on('products.id', '=', 'product_galleries.product_id')
                ->whereRaw('product_galleries.id = (
                    SELECT pg.id FROM product_galleries pg
                    WHERE pg.product_id = products.id
                    LIMIT 1
                )');
        })
        ->selectRaw('COUNT(reviews.id) as reviews_count')
        ->groupBy('products.id', 'products.name', 'products.slug', 'product_galleries.image')
        ->orderByDesc('reviews_count')
        ->limit(5)
        ->get();
    @endphp
<style>
    .no-results img {
    opacity: 0.8;
}

:root {
    --accent-color: #71cd14;
}

.category-badge {
    background-color: var(--accent-color);
    color: #fff;
    border-radius: 20px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.25s ease;
}
.category-badge:hover {
    transform: translateY(-2px);
    opacity: 0.9;
}

.product-card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.25s ease;
    background: #fff;
}
.product-card img {
    height: 160px;
    object-fit: cover;
}
.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.15);
}

.animate-pop {
    animation: popIn 0.3s ease-in-out;
}
@keyframes popIn {
    0% { transform: scale(0.95); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}


</style>

<style>
/* Search Modal */
#searchModal .modal-content {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.dark-mode #searchModal .modal-content {
    background-color: #343a40;
    color: #fff;
}

.list-group-item {
    border: none;
    padding: 10px 15px;
    background-color: transparent;
    transition: background-color 0.2s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa !important; /* Explicit light hover */
}

.dark-mode .list-group-item:hover {
    background-color: #495057 !important; /* Explicit dark hover */
}

.list-group-item span {
    font-size: 0.95rem;
}

#trendingProducts h6 {
    font-size: 1rem;
    margin-bottom: 10px;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 5px;
    color: #6c757d;
}

.dark-mode #trendingProducts h6 {
    border-bottom-color: #495057;
    color: #adb5bd;
}

#searchInput {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 10px;
}

.dark-mode #searchInput {
    border-color: #495057;
    background-color: #212529;
    color: #fff;
}

#searchResultsList {
    max-height: 300px;
    overflow-y: auto;
}

/* Prevent body scroll when modal is open */
.modal-open {
    overflow: hidden;
}
.modal-backdrop{
    display:none;
}
</style>
<!-- Animate.css CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

<style>
/* User Dropdown */

</style>

<header class="header_area">
    <div class="main_menu">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light custom-navbar">
                <!-- Mobile Top Nav Bar -->
                <div class="d-flex d-lg-none w-100 align-items-center py-2">
                    <button class="navbar-toggler border-0 bg-transparent ms-2 offcanvas-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                        <i class="bi bi-list" style="font-size: 24px;"></i>
                    </button>
                    <a class="navbar-brand mx-auto" href="{{ route('main') }}">
                        <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" height="35">
                    </a>
                    <div class="d-flex align-items-center me-2 mobile-icons">
                        <a href="{{ route('cart') }}" class="nav-icon position-relative cart-icon">
                            <i class="bi bi-cart"></i>
                            <span class="badge cart-badge" id="mobileCartCount">{{ $cartCount > 0 ? $cartCount : '' }}</span>
                        </a>
                        <a href="{{ route('wishlist') }}" class="nav-icon position-relative wishlist-icon">
                            <i class="bi bi-heart"></i>
                            <span class="badge wishlist-badge" id="mobileWishlistCount">{{ $wishlistCount > 0 ? $wishlistCount : '' }}</span>
                        </a>
                    </div>
                </div>

                <!-- Mobile Sidebar -->
                <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="mobileSidebarLabel">Menu</h5>
                        <button type="button " class="btn-close btn-danger" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="row mb-3">
                            <div class="col-5">
                                <span class="switch-label">Dark Mode</span>
                            </div>
                            <div class="col-7">
                                <label class="switch">
                                    <input type="checkbox" id="toggleDarkMode">
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                        <div class="sidebar-search">
                            <form class="d-flex">
                                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
                            </form>
                        </div>
                        <div class="sidebar-user">
                            @if(Auth::check())
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person fs-4 me-2"></i>
                                    <div>
                                        <small>Hello,</small><br>
                                        <strong>{{ Auth::user()->name }}</strong>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary me-1">My Orders</a>
                                    <form action="{{ route('user.logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Logout</button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ route('user.login') }}" class="btn btn-theme w-100">
                                    <i class="bi bi-person me-1"></i> Login / Register
                                </a>
                            @endif
                        </div>
                        <ul class="navbar-nav">
                            <li class="nav-item {{ request()->routeIs('main') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('main') }}">Home</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle nav-link-badge" href="#" id="mobileCollectionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Collections <span class="badge hot-badge">Hot</span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="mobileCollectionsDropdown">
                                    @foreach($categories as $category)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('category.products', str_replace(' ', '-', $category->name)) }}">
                                                {{ $category->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="nav-item {{ request()->routeIs('shop.index') ? 'active' : '' }}">
                                <a class="nav-link nav-link-badge" href="{{ route('shop.index') }}">
                                    Shop <span class="badge new-badge">New</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('blog') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('blog') }}">Blog</a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="collapse navbar-collapse animate__animated animate__fadeInDown d-none d-lg-flex" id="navbarContent">
                    <div class="nav-left">
                        <ul class="navbar-nav">
                            <li class="nav-item {{ request()->routeIs('main') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('main') }}">Home</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle nav-link-badge" href="#" id="collectionsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Collections <span class="badge hot-badge">Hot</span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="collectionsDropdown">
                                    @foreach($categories as $category)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('category.products', str_replace(' ', '-', $category->name)) }}">
                                                {{ $category->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="nav-item {{ request()->routeIs('shop.index') ? 'active' : '' }}">
                                <a class="nav-link nav-link-badge" href="{{ route('shop.index') }}">
                                    Shop <span class="badge new-badge">New</span>
                                </a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('blog') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('blog') }}">Blog</a>
                            </li>
                            <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                            </li>
                        </ul>
                    </div>
                    <div class="nav-center">
                        <a class="navbar-brand mx-auto" href="{{ route('main') }}">
                            <img src="{{ asset('assets/img/logo.jpg') }}" class="logo_height" alt="Logo" height="50">
                        </a>
                    </div>
                    <div class="nav-right">
<!-- Search Icon -->
<a href="#" class="nav-icon" data-bs-toggle="modal" data-bs-target="#searchModal">
    <i class="bi bi-search"></i>
</a>

<!-- Search Modal -->

                        @if(Auth::check())
                            <div class="dropdown user-dropdown">
                                <div class="dropdown-toggle user-info" data-bs-toggle="dropdown">
                                    <i class="bi bi-person user-icon"></i>
                                    <div>
                                        <small>Hello,</small><br>
                                        <strong class="user-name">{{ Auth::user()->name }}</strong>
                                    </div>
                                </div>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('orders.index') }}">My Orders</a></li>
                                    <li>
                                        <form action="{{ route('user.logout') }}" method="POST">
                                            @csrf
                                            <button class="dropdown-item" type="submit">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('user.login') }}" class="nav-icon"><i class="bi bi-person"></i></a>
                        @endif
                        <a href="{{ route('cart') }}" class="nav-icon position-relative cart-icon">
                            <i class="bi bi-cart"></i>
                            <span class="badge cart-badge" id="desktopCartCount">{{ $cartCount > 0 ? $cartCount : '' }}</span>
                        </a>
                        <a href="{{ route('wishlist') }}" class="nav-icon position-relative wishlist-icon">
                            <i class="bi bi-heart"></i>
                            <span class="badge wishlist-badge" id="desktopWishlistCount">{{ $wishlistCount > 0 ? $wishlistCount : '' }}</span>
                        </a>
                        <label class="switch ms-2">
                            <input type="checkbox" id="toggleDarkMode1">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>
            </nav>
        </div>
    </div>
   
<div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-3 shadow-lg rounded-4 animate-pop">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-success">
                    🔍 Search Products
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Search Input -->
                <input type="text" id="searchInput" 
                    class="form-control form-control-lg mb-4 border-success shadow-sm"
                    placeholder="Type to search products..." autocomplete="off">

                <!-- Category Badges -->
                <div id="searchSuggestions" class="mb-4">
                    <h6 class="text-muted mb-2">Browse by Category</h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($categories as $cat)
                            <span class="badge category-badge px-3 py-2"
                                  data-category="{{ $cat->id }}"
                                  data-name="{{ $cat->name }}">
                                {{ $cat->name }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Trending -->
                <div id="trendingProducts" class="mb-4">
                    <h6 class="text-muted mb-3">🔥 Trending Products</h6>
                    <div class="row g-3">
                        @foreach($trending as $product)
                            <div class="col-6 col-md-4">
                                <a href="{{ url('/product-details/' . $product->slug) }}"
                                   class="card product-card h-100 text-decoration-none">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         class="card-img-top" alt="{{ $product->name }}">
                                    <div class="card-body p-2">
                                        <h6 class="card-title text-dark mb-1">{{ $product->name }}</h6>
                                       
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Search Results -->
                <div id="searchResultsList" class="row g-3"></div>

                <!-- Load More -->
                <div class="text-center mt-3">
                    <button id="loadMoreBtn" class="btn btn-outline-success d-none">Load More</button>
                </div>
            </div>
        </div>
    </div>
</div>


</header>

<script>
$(document).ready(function() {
    // Dark Mode Toggle
    let darkModeToggles = $('#toggleDarkMode, #toggleDarkMode1');

    // Initialize dark mode based on localStorage
    if (localStorage.getItem('darkMode') === 'enabled') {
        $('body').addClass('dark-mode');
        darkModeToggles.prop('checked', true);
    }

    // Handle dark mode toggle change
    darkModeToggles.on('change', function() {
        $('body').toggleClass('dark-mode');
        localStorage.setItem('darkMode', $('body').hasClass('dark-mode') ? 'enabled' : 'disabled');
        // Sync both toggles
        darkModeToggles.prop('checked', $('body').hasClass('dark-mode'));
    });

    // Prevent body scroll when offcanvas is open
    $('#mobileSidebar').on('show.bs.offcanvas', function() {
        $('body').addClass('offcanvas-active');
    });

    $('#mobileSidebar').on('hide.bs.offcanvas', function() {
        $('body').removeClass('offcanvas-active');
    });

    // Scroll offcanvas to top when opened
    $('#mobileSidebar').on('shown.bs.offcanvas', function() {
        $(this).find('.offcanvas-body').scrollTop(0);
    });

    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('.dropdown-menu').hide();
        }
    });

    // Initialize dropdown toggle
    $('.dropdown-toggle').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $dropdown = $(this).parent().find('.dropdown-menu');
        $('.dropdown-menu').not($dropdown).hide();
        $dropdown.toggle();
    });

    // Function to update cart and wishlist counts
    function updateCounts() {
        $.ajax({
            url: '{{ route("get.cart.wishlist.counts") }}',
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('.cart-icon, .wishlist-icon').append('<span class="ajax-spinner"></span>');
            },
            success: function(response) {
                // Update desktop counts
                $('#desktopCartCount').text(response.cartCount > 0 ? response.cartCount : '').toggle(response.cartCount > 0);
                $('#desktopWishlistCount').text(response.wishlistCount > 0 ? response.wishlistCount : '').toggle(response.wishlistCount > 0);
                // Update mobile counts
                $('#mobileCartCount').text(response.cartCount > 0 ? response.cartCount : '').toggle(response.cartCount > 0);
                $('#mobileWishlistCount').text(response.wishlistCount > 0 ? response.wishlistCount : '').toggle(response.wishlistCount > 0);
            },
            complete: function() {
                $('.ajax-spinner').remove();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching counts:', error);
            }
        });
    }

    // Initial load
    updateCounts();

    // Update every 10 seconds
    setInterval(updateCounts, 10000);

    // Update when dropdown is opened
    $('.dropdown-toggle').on('shown.bs.dropdown', function() {
        updateCounts();
    });
});



</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    let searchInput = document.getElementById('searchInput');
    let searchResultsList = document.getElementById('searchResultsList');
    let trendingSection = document.getElementById('trendingProducts');
    let loadMoreBtn = document.getElementById('loadMoreBtn');

    let currentPage = 1;
    let currentQuery = '';
    let currentCategory = null;

    function fetchProducts(page = 1) {
        let url = `/search-products?page=${page}`;
        if (currentQuery) url += `&query=${encodeURIComponent(currentQuery)}`;
        if (currentCategory) url += `&category=${currentCategory}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (page === 1) searchResultsList.innerHTML = "";
                renderProducts(data.data);
                trendingSection.style.display = "none";
                loadMoreBtn.classList.toggle('d-none', !data.next_page_url);
                currentPage = page;
            });
    }

    function renderProducts(products) {
       if (products.length === 0 && currentPage === 1) {
    searchResultsList.innerHTML = `
        <div class="text-center w-100 py-5">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/after-login-no-product-in-cart-4006355-3309941.png" alt="No results" style="max-width: 200px;">
            <h5 class="mt-3 text-muted">No matching products found</h5>
            <p class="text-secondary">Try searching with a different keyword or browse our categories.</p>
        </div>
    `;
    return;
}

        products.forEach(product => {
            let col = document.createElement('div');
            col.className = "col-6 col-md-4";
            col.innerHTML = `
                <a href="/product-details/${product.slug}" class="card product-card h-100 text-decoration-none">
                    <img src="/storage/${product.image}" class="card-img-top" alt="${product.name}">
                    <div class="card-body p-2">
                        <h6 class="card-title text-dark mb-1">${product.name}</h6>
                    </div>
                </a>
            `;
            searchResultsList.appendChild(col);
        });
    }

    searchInput.addEventListener('keyup', function() {
        currentQuery = this.value.trim();
        currentCategory = null;
        if (currentQuery.length > 1) {
            fetchProducts(1);
        } else {
            searchResultsList.innerHTML = "";
            trendingSection.style.display = "block";
            loadMoreBtn.classList.add('d-none');
        }
    });

    document.querySelectorAll('.category-badge').forEach(badge => {
        badge.addEventListener('click', function() {
            currentCategory = this.dataset.category;
            currentQuery = this.dataset.name;
            searchInput.value = currentQuery;
            fetchProducts(1);
        });
    });

    loadMoreBtn.addEventListener('click', function() {
        fetchProducts(currentPage + 1);
    });
});


</script>

