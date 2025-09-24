
@php
    use App\Models\Category;
    use App\Models\Wishlist;
    use App\Models\Cart;
    use App\Models\Product;
    use Illuminate\Support\Facades\Auth;

    $categories = Category::with('subCategories')->get();
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


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}" />
    <style>
        :root {
            --accent-color: #71cd14;
        }

        .custom-navbar {
            position: relative;
            z-index: 1000;
        }

        /* Main dropdown styling */
      /* Show main dropdown on hover */
.nav-item.dropdown:hover > .dropdown-menu {
    display: block;
    margin-top: 0; /* Fix Bootstrap offset */
}

/* Submenu styling */
.dropdown-submenu {
    position: relative;
}

.dropdown-submenu > .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -1px;
    display: none;
    position: absolute;
}

/* Show submenu on hover */
.dropdown-submenu:hover > .dropdown-menu {
    display: block;
}

        .arrow-icon {
            cursor: pointer;
            padding: 0 10px;
            font-size: 12px;
        }

        /* User dropdown select styling */
        .user-select {
            display: flex;
            align-items: center;
            border: none;
            background: transparent;
            color: #000;
            font-size: 0.95rem;
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }

        .user-select:hover {
            background-color: #f8f9fa;
        }

        .user-select option {
            background: #fff;
            color: #000;
        }

        body.dark-mode .user-select {
            color: #fff;
            background: transparent;
        }

        body.dark-mode .user-select:hover {
            background-color: #495057;
        }

        body.dark-mode .user-select option {
            background: #343a40 !important;
            color: #fff !important;
        }
      
          
       
        /* Dark mode compatibility */
        body.dark-mode .dropdown-menu,
        body.dark-mode .submenu {
            background: #333;
            border-color: #555;
        }

        body.dark-mode .dropdown-item {
            color: #fff;
        }

        body.dark-mode .dropdown-item:hover {
            background: #444;
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
            background-color: #f8f9fa !important;
        }

        .dark-mode .list-group-item:hover {
            background-color: #495057 !important;
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

        .modal-open {
            overflow: hidden;
        }

        .modal-backdrop {
            display: none;
        }

        .sidebar-search .form-control {
            border-radius: 4px;
            padding: 8px 12px;
        }

        .sidebar-search .btn {
            padding: 8px 12px;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .dropdown-item:hover {
            color: var(--accent-color) !important;
        }
.dark-mode .cross,
.dark-mode .cross * {
  filter: invert(1); /* makes the X white */
    opacity: 1;  
}
        .nav-icon:hover i {
            color: var(--accent-color) !important;
        }

        .navbar .btn:hover {
            background-color: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            color: #fff !important;
        }

        .no-results img {
            opacity: 0.8;
        }
.widthdeop {
    width: 124px !important;

}
/* default link */
.custom-nav-link {
    transition: color 0.3s ease;
    color:black;
}

/* hover */
.custom-nav-link:hover {
    color: #71cd14 !important;
}
.dropdown-item:hover {
    color: #71cd14 !important;
}

/* active */
.custom-nav-link.active,
.nav-item.active .custom-nav-link {
    color: #71cd14 !important;
    font-weight: bold;
}
.dropdown-item.active,
.dropdown-item.active .dropdown-item {
    color: #71cd14 !important;
    font-weight: bold;
}

.set{
        position: relative;
    right: 20px;
    bottom: 18px;
}


    </style>
</head>
<body>
    <div class="marquee-container">
        <div class="marquee-content">
            <span>UPTO 50% OFF → <i>Healthy Season SALE</i></span>
            <span>UPTO 50% OFF → <i>Healthy Season SALE</i></span>
            <span>UPTO 50% OFF → <i>Healthy Season SALE</i></span>
            <span>UPTO 50% OFF → <i>Healthy Season SALE</i></span>
        </div>
    </div>

    <header class="header_area">
        <div class="main_menu">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-lg navbar-light custom-navbar">
                    <!-- Mobile Top Nav Bar -->
                    <div class="d-flex d-lg-none w-100 align-items-center py-lg-2 py-0">
                        <button class="navbar-toggler border-0 bg-transparent ms-2 offcanvas-btn" 
                                type="button" 
                                data-bs-toggle="offcanvas" 
                                data-bs-target="#mobileSidebar" 
                                aria-controls="mobileSidebar">
                            <i class="bi bi-list" style="font-size: 24px;"></i>
                        </button>
 <div class="sidebar-search ">
                                <form class="d-flex">
                                    <button class="btn btn-outline-success" type="button" data-bs-toggle="modal" data-bs-target="#searchModal">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </form>
                            </div>
                        <a class="navbar-brand mx-auto" href="{{ route('main') }}">
                            <img class="size-img" src="{{ asset('assets/img/logo.png') }}" alt="Logo" height="35">
                        </a>

                        <div class="d-flex align-items-center me-2 mobile-icons ms-auto">
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
                            <button type="button" class="btn-close btn-danger cross" data-bs-dismiss="offcanvas" aria-label="Close"></button>
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
                           
                            <div class="sidebar-user">
                                @if(Auth::check())
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person fs-4 me-2"></i>
                                        <div>
                                            <small class="no-dark">Hello,</small><br>
                                            <strong class="no-dark">{{ Auth::user()->name }}</strong>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary me-1 no-dark">My Orders</a>
                                        <a href="{{ route('change.password.form') }}" class="btn btn-sm btn-outline-secondary me-1 no-dark">Change Password</a>
                                        <form action="{{ route('user.logout') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger no-dark" type="submit">Logout</button>
                                        </form>
                                    </div>
                                @else
                                    <a href="{{ route('user.login') }}" class="btn btn-theme w-100">
                                        <i class="bi bi-person me-1"></i> Login / Register
                                    </a>
                                @endif
                            </div>
                            <ul class="navbar-nav" style="max-height: 70vh; overflow-y: auto;">
                                <li class="nav-item {{ request()->routeIs('main') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('main') }}">Home</a>
                                </li>
                                  @foreach($categories as $category)
    <li class="nav-item dropdown position-relative">
        <a class="nav-link nav-link-custom dropdown-toggle" 
           href="#"
           id="dropdown-{{ $category->id }}"
           aria-expanded="false">
            {{ $category->name }}
            
            @if($loop->first)
                <span class="badge bg-danger set ">Hot</span>
            @elseif($loop->iteration == 2)
                <span class="badge bg-success set">Sale</span>
            @endif
        </a>

        @if($category->subCategories->count() > 0)
            <ul class="dropdown-menu shadow-sm" 
                aria-labelledby="dropdown-{{ $category->id }}" 
                style="max-height: 60vh; overflow-y: auto; width: 200px;">
                @foreach($category->subCategories as $sub)
                    <li>
                        <a class="dropdown-item text-dark" 
                           href="{{ route('subcategory.products', [str_replace(' ', '-', $category->name), str_replace(' ', '-', $sub->name)]) }}">
                            {{ $sub->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </li>
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropdownToggles = document.querySelectorAll('.nav-link-custom.dropdown-toggle');
    
    
    dropdownToggles.forEach((toggle, index) => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent event bubbling
            
            const currentDropdown = this.nextElementSibling;
            if (!currentDropdown) {
                console.error('No dropdown menu found for toggle:', this.textContent.trim(), 'ID:', this.id); // Debug: Catch null dropdown
                return;
            }
            
            const isCurrentlyOpen = currentDropdown.classList.contains('show');
            console.log(`Toggle ${index + 1} clicked:`, this.textContent.trim(), 'ID:', this.id, 'Open:', isCurrentlyOpen); // Debug: Log click details
            
            // Close all dropdowns
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
                menu.style.display = 'none'; // Explicitly hide to counter Bootstrap
            });
            
            // Toggle current dropdown
            if (!isCurrentlyOpen) {
                currentDropdown.classList.add('show');
                currentDropdown.style.display = 'block'; // Explicitly show
            } else {
            }
            
            // Update aria-expanded attribute
            this.setAttribute('aria-expanded', !isCurrentlyOpen);
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
                menu.style.display = 'none'; // Explicitly hide
            });
        }
    });
    
    // Disable Bootstrap's dropdown behavior
    document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
        toggle.removeAttribute('data-bs-toggle'); // Ensure Bootstrap doesn't interfere
    });
});
</script>






                                <li class="nav-item {{ request()->routeIs('shop.index') ? 'active' : '' }}">
                                    <a class="nav-link nav-link-badge" href="{{ route('shop.index') }}">
                                        Shop <span class="badge new-badge">New</span>
                                    </a>
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
                                    <a class="nav-link fw-bold text-dark fs-5" href="{{ route('main') }}">Home</a>
                                </li>
                                     </ul>
                                                        @foreach($categories as $category)
                                        <li class="nav-item dropdown widthdeop">
                                                <a class="nav-link nav-link-badge fw-bold  custom-nav-link " 
                                                href="{{ route('category.products', str_replace(' ', '-', $category->name)) }}"
                                                id="collectionsDropdown-{{ $category->id }}">
                                                    {{ $category->name }}
                                                    
                                                    @if($loop->first)
                                                        <span class="badge hot-badge">Hot</span>
                                                    @elseif($loop->iteration == 2)
                                                        <span class="badge hot-badge">Sale</span>
                                                    @endif
                                                </a>

                                                {{-- Show dropdown only if subcategories exist --}}
                                                @if($category->subCategories->count() > 0)
                                                    <ul class="dropdown-menu" aria-labelledby="collectionsDropdown-{{ $category->id }}" style="overflow-y: auto;">
                                                        @foreach($category->subCategories as $sub)
                                                            <li>
                                                                <a class="dropdown-item" 
                                                                href="{{ route('subcategory.products', [str_replace(' ', '-', $category->name), str_replace(' ', '-', $sub->name)]) }}">
                                                                    {{ $sub->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach




                                <li class="nav-item {{ request()->routeIs('shop.index') ? 'active' : '' }} ">
                                    <a class="nav-link nav-link-badge fw-bold  custom-nav-link " href="{{ route('shop.index') }}">
                                        Shop <span class="badge new-badge">New</span>
                                    </a>
                                </li>
                             
                                <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }} ">
                                    <a class="nav-link fw-bold  custom-nav-link " href="{{ route('contact') }}">Contact</a>
                                </li>
                       
                        </div>
                        <div class="nav-center">
                            <a class="navbar-brand mx-auto" href="{{ route('main') }}">
                                <img src="{{ asset('assets/img/logo.png') }}" class="logo_height" alt="Logo" height="50">
                            </a>
                        </div>
                        <div class="nav-right">
                            <a href="#" class="nav-icon" data-bs-toggle="modal" data-bs-target="#searchModal">
                                <i class="bi bi-search"></i>
                            </a>
                            @if(Auth::check())
                                <div class="user-select-wrapper ">
                                    <select class="user-select " onchange="handleUserSelect(this)">
                                        <option class="no-dark" value="" disabled selected>Hello, {{ Auth::user()->name }}</option>
                                        <option class="no-dark" value="{{ route('orders.index') }}">My Orders</option>
                                        <option class="no-dark" value="{{ route('change.password.form') }}">Change Password</option>
                                        <option class="no-dark" value="logout">Logout</option>
                                    </select>
                                    <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
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

        <!-- Search Modal -->
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
                        <input type="text" id="searchInput" 
                               class="form-control form-control-lg mb-4 border-success shadow-sm"
                               placeholder="Type to search products..." autocomplete="off">
                      
                        <div id="trendingProducts" class="mb-4">
                            <h6 class="text-muted mb-3">🔥 Trending Products</h6>
                            <div class="row g-3">
                      @foreach($trending as $product)
    <div class="col-12 col-md-4">
        <a href="{{ route('product.details', ['slug' => $product->slug]) }}" class="card product-card h-100 text-decoration-none">
            <img src="{{ str_starts_with($product->image, 'http') ? str_replace('publicadmin', 'public/admin', $product->image) : asset($product->image) }}"
                 class="card-img-top" alt="{{ $product->name }}" loading="lazy">
            <div class="card-body p-2">
                <h6 class="card-title text-dark mb-1">{{ $product->name }}</h6>
            </div>
        </a>
    </div>
@endforeach
                            </div>
                        </div>
                        <div id="searchResultsList" class="row g-3"></div>
                        <div class="text-center mt-3">
                            <button id="loadMoreBtn" class="btn btn-outline-success d-none">Load More</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <script>
        // Submenu toggle function for Collections dropdown
    

        // Handle user select dropdown
        function handleUserSelect(select) {
            const value = select.value;
            if (value === 'logout') {
                document.getElementById('logout-form').submit();
            } else if (value) {
                window.location.href = value;
            }
            select.value = ''; // Reset to default option
        }

      document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggles = document.querySelectorAll('#toggleDarkMode, #toggleDarkMode1');
    const body = document.body;

    // Initialize dark mode based on localStorage
    if (localStorage.getItem('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
        darkModeToggles.forEach(toggle => toggle.checked = true);
    } else {
        body.classList.remove('dark-mode');
        darkModeToggles.forEach(toggle => toggle.checked = false);
    }

    // Handle toggle change
    darkModeToggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const isEnabled = this.checked;
            body.classList.toggle('dark-mode', isEnabled);
            localStorage.setItem('darkMode', isEnabled ? 'enabled' : 'disabled');
            darkModeToggles.forEach(t => t.checked = isEnabled);
        });
    });


            // Prevent body scroll when offcanvas is open
            $('#mobileSidebar').on('show.bs.offcanvas', function() {
                $('body').addClass('offcanvas-active');
            });
            $('#mobileSidebar').on('hide.bs.offcanvas', function() {
                $('body').removeClass('offcanvas-active');
            });
            $('#mobileSidebar').on('shown.bs.offcanvas', function() {
                $(this).find('.offcanvas-body').scrollTop(0);
            });

            // Ensure modal stays open when typing
            $('#searchModal').on('shown.bs.modal', function() {
                $('#searchInput').focus();
                $('#mobileSidebar').offcanvas('hide');
            });

            // Prevent modal from closing when clicking inside
            $('#searchModal .modal-content').on('click', function(e) {
                e.stopPropagation();
            });

            // Prevent Collections dropdown from closing
            $('.dropdown-menu').on('click', function(e) {
                e.stopPropagation();
            });

            // Update cart and wishlist counts
            function updateCounts() {
                $.ajax({
                    url: '{{ route("get.cart.wishlist.counts") }}',
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        $('.cart-icon, .wishlist-icon').append('<span class="ajax-spinner"></span>');
                    },
                    success: function(response) {
                        $('#desktopCartCount').text(response.cartCount > 0 ? response.cartCount : '').toggle(response.cartCount > 0);
                        $('#desktopWishlistCount').text(response.wishlistCount > 0 ? response.wishlistCount : '').toggle(response.wishlistCount > 0);
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

            updateCounts();
            setInterval(updateCounts, 10000);
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let searchInput = document.getElementById('searchInput');
            let searchResultsList = document.getElementById('searchResultsList');
            let trendingSection = document.getElementById('trendingProducts');
            let loadMoreBtn = document.getElementById('loadMoreBtn');
            let searchModal = document.getElementById('searchModal');

            let currentPage = 1;
            let currentQuery = '';
            let currentCategory = null;

            function fetchProducts(page = 1) {
                let route = `{{ route('products.search') }}?page=${page}`;
                
                if (currentQuery) route += `&query=${encodeURIComponent(currentQuery)}`;
                if (currentCategory) route += `&category=${encodeURIComponent(currentCategory)}`;

                fetch(route)
                    .then(res => res.json())
                    .then(data => {
                        if (page === 1) searchResultsList.innerHTML = "";
                        renderProducts(data.data);
                        trendingSection.style.display = (currentQuery || currentCategory) ? "none" : "block";
                        loadMoreBtn.classList.toggle('d-none', !data.next_page_url);
                        currentPage = page;
                    })
                    .catch(error => {
                        console.error('Error fetching products:', error);
                        searchResultsList.innerHTML = `
                            <div class="text-center w-100 py-5">
                                <h5 class="mt-3 text-muted">Error loading products</h5>
                                <p class="text-secondary">Please try again later.</p>
                            </div>
                        `;
                    });
            }
function renderProducts(products) {
    if (products.length === 0 && currentPage === 1) {
        searchResultsList.innerHTML = `
            <div class="text-center w-100 py-5">
                <img src="{{ asset('assets/img/no-results.png') }}" alt="No results" style="max-width: 200px;" loading="lazy">
                <h5 class="mt-3 text-muted">No matching products found</h5>
                <p class="text-secondary">Try searching with a different keyword or browse our categories.</p>
            </div>
        `;
        return;
    }

    products.forEach(product => {
        let col = document.createElement('div');
        col.className = "col-6 col-md-4";
        // Fix incorrect URLs and handle relative paths
        let imagePath = product.image;
        if (imagePath.startsWith('http')) {
            // Fix URLs with 'publicadmin' to 'public/admin'
            imagePath = imagePath.replace('publicadmin', 'public/admin');
        } else {
            // Remove leading slashes for relative paths and prepend asset base URL
            imagePath = '{{ asset('') }}' + imagePath.replace(/^\/+/, '');
        }
        col.innerHTML = `
            <a href="/product-details/${product.slug}" class="card product-card h-100 text-decoration-none">
                <img src="${imagePath}" class="card-img-top" alt="${product.name}" loading="lazy">
                <div class="card-body p-2">
                    <h6 class="card-title text-dark mb-1">${product.name}</h6>
                </div>
            </a>
        `;
        searchResultsList.appendChild(col);
    });
}

            // Debounce function to limit API calls
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Handle search input with debounce
            const debouncedSearch = debounce(function() {
                currentQuery = searchInput.value.trim();
                currentCategory = null;
                if (currentQuery.length > 1) {
                    fetchProducts(1);
                } else {
                    searchResultsList.innerHTML = "";
                    trendingSection.style.display = "block";
                    loadMoreBtn.classList.add('d-none');
                }
            }, 300);

            searchInput.addEventListener('input', function(e) {
                e.preventDefault();
                debouncedSearch();
            });

            // Prevent modal from closing when typing
            searchInput.addEventListener('keydown', function(e) {
                e.stopPropagation();
            });

            document.querySelectorAll('.category-badge').forEach(badge => {
                badge.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentCategory = this.dataset.category;
                    currentQuery = this.dataset.name;
                    searchInput.value = currentQuery;
                    fetchProducts(1);
                });
            });

            loadMoreBtn.addEventListener('click', function() {
                fetchProducts(currentPage + 1);
            });

            // Ensure modal stays focused on mobile
            searchModal.addEventListener('shown.bs.modal', function() {
                searchInput.focus();
            });
        });
    </script>
