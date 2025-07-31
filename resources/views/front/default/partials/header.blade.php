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

<style>
  .top-0-0{
    top:21px !important;
  }
  
</style>
<header class="header_area">
    <div class="main_menu">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light w-100">
                <!-- Logo -->
                <a class="navbar-brand logo_h" href="{{ route('main') }}">
                    <img class="logo_height" src="{{ asset('assets/img/logo.jpg') }}" alt="Logo">
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <div class="collapse navbar-collapse offset w-100" id="navbarSupportedContent">
                    <div class="row w-100 mr-0">
                        <div class="col-lg-7 pr-0">
                            <ul class="nav navbar-nav center_nav pull-right">
                                <li class="nav-item {{ request()->routeIs('main') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('main') }}">Home</a>
                                </li>

                                <li class="nav-item submenu dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                                        Collections
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach($categories as $category)
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('category.products', str_replace(' ', '-', $category->name)) }}">
                                                    {{ $category->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>

                              <li class="nav-item">
    <a href="{{ route('shop.index') }}" class="nav-link d-flex align-items-center gap-1">
        <span class="position-relative d-inline-flex align-items-center">
            <i class="ti-bag me-1"></i> Shop
            <span class="position-absolute top-0-0 start-100 translate-middle badge rounded-pill"
                  style="background-color:#71cd14; font-size: 10px;">
                New
            </span>
        </span>
    </a>
</li>


                                <li class="nav-item {{ request()->routeIs('blog') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('blog') }}">Blog</a>
                                </li>

                                <li class="nav-item submenu dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                                        Pages
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item"><a class="nav-link" href="#">Tracking</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#">Elements</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ url('contact.html') }}">Contact</a>
                                </li>
                            </ul>
                        </div>

                        <div class="col-lg-5 pr-0">
                            <ul class="nav navbar-nav navbar-right right_nav pull-right">
                                <li class="nav-item">
                                    <a href="#" class="icons">
                                        <i class="ti-search" aria-hidden="true"></i>
                                    </a>
                                </li>

                               

                                <li class="nav-item">
                                    @if(Auth::check())
                                        <div class="user-dropdown dropdown">
                                            <div class="user-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-person user-icon"></i>
                                                <div>
                                                    <div class="text-muted" style="font-size: 12px;">Hello,</div>
                                                    <div class="user-name text-primary">{{ Auth::user()->name }}</div>
                                                </div>
                                            </div>
                                            <ul class="dropdown-menu" style="min-width: 8rem !important;">
                                                <li><a class="dropdown-item" href="#">Dashboard</a></li>
                                                <li>
                                                    <form action="{{ route('user.logout') }}" method="POST" id="logoutForm">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">Logout</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @else
                                        <a href="{{ route('user.login') }}" class="icons">
                                            <i class="ti-user" aria-hidden="true"></i>
                                        </a>
                                    @endif
                                </li>
 <li class="nav-item position-relative">
                                    <a href="{{ route('cart') }}" class="icons position-relative">
                                        <i class="ti-shopping-cart"></i>
                                        @if($cartCount > 0)
                                            <span class="position-absolute top-0-0 start-100 translate-middle badge rounded-pill" style="background:#71cd14; font-size: 10px;">
                                                {{ $cartCount }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                                <li class="nav-item position-relative">
                                    <a href="{{ route('wishlist') }}" class="icons position-relative">
                                        <i class="ti-heart" aria-hidden="true"></i>
                                        @if($wishlistCount > 0)
                                            <span class="position-absolute top-0-0 start-100 translate-middle badge rounded-pill" style="background:#71cd14; font-size: 10px;">
                                                {{ $wishlistCount }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </nav>
        </div>
    </div>
</header>
