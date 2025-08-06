@php
    use App\Models\Category;
    use App\Models\Wishlist;
    use App\Models\Cart;
    use Illuminate\Support\Facades\Auth;

    $categories = Category::all();
    $wishlistCount = Auth::check() ? Wishlist::where('user_id', Auth::id())->count() : 0;
    $cart = Auth::check() ? Cart::where('user_id', Auth::id())->where('status', 'active')->first() : null;
    $cartCount = $cart && $cart->items ? $cart->items->count() : 0;
@endphp

<!-- Animate.css CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">


<header class="header_area">
    <div class="main_menu">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light custom-navbar">

                <!-- Mobile Top Nav Bar -->
                <div class="d-flex d-lg-none w-100 align-items-center py-2">
                    <!-- Left: Sidebar Toggle -->
                    <button class="navbar-toggler border-0 bg-transparent ms-2 offcanvas-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                        <i class="bi bi-list" style="font-size: 24px;"></i>
                    </button>

                    <!-- Center: Logo -->
                    <a class="navbar-brand mx-auto" href="{{ route('main') }}">
                        <img src="{{ asset('assets/img/logo.jpg') }}" alt="Logo" height="35">
                    </a>

                    <!-- Right: Icons -->
                    <div class="d-flex align-items-center me-2 mobile-icons">
                        <a href="{{ route('cart') }}" class="nav-icon position-relative me-2 cart-icon">
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
                <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileSidebar">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title">Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <!-- Search in Sidebar -->
                        <div class="sidebar-search">
                            <form class="d-flex">
                                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i></button>
                            </form>
                        </div>

                        <!-- User in Sidebar -->
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
                                    <a href="#" class="btn btn-sm btn-outline-secondary me-1">Dashboard</a>
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

                        <!-- Navigation Links -->
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
                    <!-- Left Links -->
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

                    <!-- Centered Logo for Desktop -->
                    <div class="nav-center">
                        <a class="navbar-brand mx-auto" href="{{ route('main') }}">
                            <img src="{{ asset('assets/img/logo.jpg') }}" class="logo_height" alt="Logo" height="50">
                        </a>
                    </div>

                    <!-- Right Side Icons -->
                    <div class="nav-right">
                        <a href="#" class="nav-icon"><i class="bi bi-search"></i></a>

                        @if(Auth::check())
                            <div class="dropdown user-dropdown">
                                <div class="dropdown-toggle user-info" data-bs-toggle="dropdown">
                                    <i class="bi bi-person"></i>
                                    <div>
                                        <small>Hello,</small><br>
                                        <strong>{{ Auth::user()->name }}</strong>
                                    </div>
                                </div>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Dashboard</a></li>
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
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>

<script>
$(document).ready(function() {
    // Close dropdown when clicking outside
    $(document).click(function(e) {
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
                if (response.cartCount > 0) {
                    $('#desktopCartCount').text(response.cartCount).show();
                } else {
                    $('#desktopCartCount').text('').hide();
                }
                
                if (response.wishlistCount > 0) {
                    $('#desktopWishlistCount').text(response.wishlistCount).show();
                } else {
                    $('#desktopWishlistCount').text('').hide();
                }
                
                // Update mobile counts
                if (response.cartCount > 0) {
                    $('#mobileCartCount').text(response.cartCount).show();
                } else {
                    $('#mobileCartCount').text('').hide();
                }
                
                if (response.wishlistCount > 0) {
                    $('#mobileWishlistCount').text(response.wishlistCount).show();
                } else {
                    $('#mobileWishlistCount').text('').hide();
                }
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
    
    // Update every second (1000ms)
    setInterval(updateCounts, 1000);
    
    // Also update when dropdown is opened
    $('.dropdown-toggle').on('shown.bs.dropdown', function() {
        updateCounts();
    });
});
</script>