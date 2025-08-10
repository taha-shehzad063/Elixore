@extends('front.default.partials.app')
@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/home.css') }}" />

<!--================Home Banner Area =================-->
<style>
 
</style>

<!-- Include Recently Viewed Widget -->

    @include('front.default.recently_viewed')

@if(session('success'))
    <div class="alert alert-success rounded-pill px-4 py-2 text-center mb-3">
        {{ session('success') }}
    </div>
@endif

@if($banners->count())
<section class="mb-40">
  <div id="bannerCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-inner">
      @foreach($banners as $key => $banner)
      <div class="carousel-item {{ $key == 0 ? 'active' : '' }} home_banner_area"
           style="background-image: url('{{ asset('storage/' . $banner->image) }}'); background-size: cover; background-position: center;">
        <div class="banner_inner d-flex align-items-center">
          <div class="container">
            <div class="banner_content row">
              <div class="col-lg-12 text-white">
                <p class="sub text-uppercase">{{ $banner->sub_title ?? 'Men Collection' }}</p>
                <h3><span>{{ $banner->title ?? 'Show' }}</span> Your <br />Personal <span>Style</span></h3>
                <h4>{{ $banner->description ?? 'Fowl saw dry which a above together place.' }}</h4>
                @if($banner->button)
                  <a class="main_btn mt-40" href="#">{{ $banner->button }}</a>
                @else
                  <a class="main_btn mt-40" href="#">View Collection</a>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</section>
@endif

<div class="marquee-container">
    <div class="marquee-content">
        <span>UPTO 50% OFF → <i>Healthy Season SALE</i></span>
        <span>UPTO 50% OFF → <i>Healthy Season SALE</i></span>
        <span>UPTO 50% OFF → <i>Healthy Season SALE</i></span>
        <span>UPTO 50% OFF → <i>Healthy Season SALE</i></span>
    </div>
</div>

<!--================End Home Banner Area =================-->

<!-- Start Feature Area -->
<style>
    /* Base styling */
    .single-feature {
        background: #fff;
        border-radius: 10px;
        padding: 25px 20px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease-in-out;
    }

    /* Icon style */
    .single-feature i {
        font-size: 40px;
        color: #8e2de2; /* Purple */
        display: block;
        margin-bottom: 15px;
        transition: transform 0.3s ease, color 0.3s ease;
    }

    /* Title text */
    .single-feature h3 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        transition: color 0.3s ease;
    }

    /* Description */
    .single-feature p {
        font-size: 14px;
        color: #666;
    }

    /* Hover effect */
    .single-feature:hover {
        background: linear-gradient(145deg, #8e2de2, #4a00e0);
        transform: translateY(-8px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .single-feature:hover i {
        transform: scale(1.2) rotate(10deg);
        color: #fff;
    }

    .single-feature:hover h3 {
        color: #fff;
    }

    .single-feature:hover p {
        color: #f0f0f0;
    }
</style>

<section class="feature-area section_gap_bottom_custom">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="single-feature ">
                    <a href="{{route('shop.index')}}" class="title">
                        <i class="flaticon-money"></i>
                        <h3 class="no-dark">Money back guarantee</h3>
                    </a>
                    <p class="no-dark">Shall open divide a one</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="single-feature">
                    <a href="{{route('shop.index')}}" class="title">
                        <i class="flaticon-truck"></i>
                        <h3 class="no-dark">Free Delivery</h3>
                    </a>
                    <p class="no-dark">Shall open divide a one</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="single-feature">
                    <a href="{{route('shop.index')}}" class="title">
                        <i class="flaticon-support"></i>
                        <h3 class="no-dark">Always support</h3>
                    </a>
                    <p class="no-dark">Shall open divide a one</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="single-feature">
                    <a href="{{route('shop.index')}}" class="title">
                        <i class="flaticon-blockchain"></i>
                        <h3 class="no-dark">Secure payment</h3>
                    </a>
                    <p class="no-dark">Shall open divide a one</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- End Feature Area -->

<!--================ Feature Product Area =================-->
<section class="feature_product_area section_gap_bottom_custom">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="main_title">
            <h2><span>{{ $setting->heading_0 ?? '' }}</span></h2>
            <p>{{ $setting->intro_0 ?? '' }}</p>
          </div>
        </div>
      </div>
      <div class="row">
        @foreach ($products as $product)
        <div class="col-lg-4 col-md-6">
          <div class="single-product">
            <div class="product-img">
              <img class="img-fluid w-100" style="height: 250px; object-fit: cover;"
                   src="{{ asset('storage/' . $product->galleries->first()->image) }}"
                   alt="{{ $product->name }}" />
              <div class="p_icon">
                <a class="no-dark4" href="{{ route('product.details', $product->slug) }}">
                  <i class="ti-eye"></i>
                </a>
                @guest
                  <a href="javascript:void(0);" class="auth-required no-dark4" data-action="wishlist" data-id="{{ $product->id }}">
                    <i class="ti-heart"></i>
                  </a>
                @else
                  <a href="javascript:void(0);" class="add-to-wishlist no-dark4" data-id="{{ $product->id }}">
                    <i class="ti-heart"></i>
                  </a>
                @endguest
                @guest
                  <a href="javascript:void(0);" class="auth-required no-dark4" data-action="cart" data-id="{{ $product->id }}" data-qty="1">
                    <i class="ti-shopping-cart"></i>
                  </a>
                @else
                  <a href="javascript:void(0);" class="add-to-cart no-dark4" data-id="{{ $product->id }}" data-qty="1">
                    <i class="ti-shopping-cart"></i>
                  </a>
                @endguest
              </div>
            </div>
            <div class="product-btm">
              <a href="#" class="d-block">
                <h4>{{ $product->title ?? $product->name }}</h4>
              </a>
              <div class="mt-3">
                <span class="mr-4">${{ number_format($product->price, 2) }}</span>
                @if ($product->discount_price)
                  <del>${{ number_format($product->discount_price, 2) }}</del>
                @endif
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
</section>
<!--================ End Feature Product Area =================-->

<!--================ Offer Area =================-->
@if($collections)
<section class="offer_area position-relative" style="background: url('{{ asset('storage/' . $collections->image) }}') no-repeat center; background-size: cover; height: 100vh;">
    <div class="overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.6); z-index: 1;"></div>
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-lg-8 text-center text-white position-relative" style="z-index: 2;">
                <h1 class="display-4 font-weight-bold mb-3">{{ $collections->title }}</h1>
                <h2 class="h3 mb-4">{{ $collections->heading }}</h2>
                <p class="mb-4">{{ $collections->sale_text }}</p>
                @if($collections->button_url && $collections->button_text)
                    <a href="{{ $collections->button_url }}" class="btn btn-warning btn-lg">{{ $collections->button_text }}</a>
                @endif
            </div>
        </div>
    </div>
</section>
@endif
<!--================ End Offer Area =================-->

<!--================best sell =================-->
<section class="inspired_product_area section_gap_bottom_custom mt-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="main_title">
            <h2><span>{{ $setting->heading_1 ?? '' }}</span></h2>
            <p>{{ $setting->heading_2 ?? '' }}</p>
          </div>
        </div>
      </div>
      <div class="row">
        @foreach ($bestSellers as $product)
        <div class="col-lg-4 col-md-6">
          <div class="single-product">
            <div class="product-img">
              <img class="img-fluid w-100" style="height: 250px; object-fit: cover;"
                   src="{{ asset('storage/' . $product->galleries->first()->image) }}"
                   alt="{{ $product->name }}" />
              <div class="p_icon">
                <a class="no-dark4" href="{{ route('product.details', $product->slug) }}">
                  <i class="ti-eye"></i>
                </a>
                @guest
                  <a href="javascript:void(0);" class="auth-required no-dark4" data-action="wishlist" data-id="{{ $product->id }}">
                    <i class="ti-heart"></i>
                  </a>
                @else
                  <a href="javascript:void(0);" class="add-to-wishlist no-dark4" data-id="{{ $product->id }}">
                    <i class="ti-heart"></i>
                  </a>
                @endguest
                @guest
                  <a href="javascript:void(0);" class="auth-required no-dark4" data-action="cart" data-id="{{ $product->id }}" data-qty="1">
                    <i class="ti-shopping-cart"></i>
                  </a>
                @else
                  <a href="javascript:void(0);" class="add-to-cart no-dark4" data-id="{{ $product->id }}" data-qty="1">
                    <i class="ti-shopping-cart"></i>
                  </a>
                @endguest
              </div>
            </div>
            <div class="product-btm">
              <a href="{{ route('product.details', $product->slug) }}" class="d-block">
                <h4>{{ $product->title ?? $product->name }}</h4>
              </a>
              <div class="mt-3">
                <span class="mr-4">${{ number_format($product->price, 2) }}</span>
                @if($product->discount_price)
                  <del>${{ number_format($product->discount_price, 2) }}</del>
                @endif
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
</section>
<!--================ End Inspired Product Area =================-->
<section class="why-section">
    <h2 class="text-light">Why Jungle Cart?</h2>
    <div class="why-container">
        
        <!-- Free Shipping -->
        <div class="why-item">
            <img src="https://cdn-icons-png.flaticon.com/512/1040/1040238.png" alt="Free Shipping">
            <p>Free Shipping On 3000+</p>
        </div>

        <!-- Weekly Flash Sales -->
        <div class="why-item">
            <img src="https://cdn-icons-png.flaticon.com/512/1827/1827504.png" alt="Flash Sales">
            <p>Weekly Flash Sales</p>
        </div>

        <!-- Annual Payment Discount -->
        <div class="why-item">
            <img src="https://cdn-icons-png.flaticon.com/512/747/747310.png" alt="Annual Payment">
            <p>Annual Payment Discount</p>
        </div>

        <!-- Cashback Reward Program -->
        <div class="why-item">
            <img src="https://cdn-icons-png.flaticon.com/512/992/992703.png" alt="Cashback">
            <p>Cashback Reward Program</p>
        </div>

    </div>
</section>
<section class="inspired_product_area section_gap_bottom_custom mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="main_title">
                    <h2><span>Most Popular Products</span></h2>
                </div>
            </div>
        </div>

        <!-- Marquee wrapper -->
        <marquee behavior="scroll" direction="left" scrollamount="6" onmouseover="this.stop();" onmouseout="this.start();">
            <div style="display: flex; gap: 20px;">
                @foreach ($mostpopular as $product)
                    <div class="single-product" style="min-width: 300px;">
                        <div class="product-img">
                            <img class="img-fluid w-100" style="height: 250px; object-fit: cover;"
                                src="{{ asset('storage/' . $product->galleries->first()->image) }}"
                                alt="{{ $product->name }}" />
                            <div class="p_icon">
                                <a class="no-dark4" href="{{ route('product.details', $product->slug) }}">
                                    <i class="ti-eye"></i>
                                </a>
                                @guest
                                    <a href="javascript:void(0);" class="auth-required no-dark4" data-action="wishlist" data-id="{{ $product->id }}">
                                        <i class="ti-heart"></i>
                                    </a>
                                @else
                                    <a href="javascript:void(0);" class="add-to-wishlist no-dark4" data-id="{{ $product->id }}">
                                        <i class="ti-heart"></i>
                                    </a>
                                @endguest
                                @guest
                                    <a href="javascript:void(0);" class="auth-required no-dark4" data-action="cart" data-id="{{ $product->id }}" data-qty="1">
                                        <i class="ti-shopping-cart"></i>
                                    </a>
                                @else
                                    <a href="javascript:void(0);" class="add-to-cart no-dark4" data-id="{{ $product->id }}" data-qty="1">
                                        <i class="ti-shopping-cart"></i>
                                    </a>
                                @endguest
                            </div>
                        </div>
                        <div class="product-btm">
                            <a href="{{ route('product.details', $product->slug) }}" class="d-block">
                                <h4>{{ $product->title ?? $product->name }}</h4>
                            </a>
                            <div class="mt-3">
                                <span class="mr-4">${{ number_format($product->price, 2) }}</span>
                                @if($product->discount_price)
                                    <del>${{ number_format($product->discount_price, 2) }}</del>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </marquee>
    </div>
</section>
<style>
/* Make Swiper pagination dots green */
.swiper-pagination-bullet {
    background: #71cd14 !important;
}
.swiper-pagination-bullet-active {
    background: #71cd14 !important;
}
</style>

<!--================ Start Customer Reviews Area =================-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<section style="padding: 60px 0;">
    <div class="container text-center no-dark5">
      <h2 style="margin-bottom: 40px;" class="fw-bold">What Our Customers Say</h2>
      <div class="swiper mySwiper">
        <div class="swiper-wrapper">
          @foreach ($reviews as $review)
          <div class="swiper-slide">
            <div>
              @for ($i = 1; $i <= floor($review->rating); $i++)
                ★
              @endfor
            </div>
            <p>{{ Str::limit($review->message, 150) }}</p>
            <strong>{{ $review->name }}</strong>
          </div>
          @endforeach
        </div>
        <div class="swiper-pagination "></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
      </div>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    new Swiper(".mySwiper", {
      slidesPerView: 3,
      spaceBetween: 30,
      loop: true,
      autoplay: {
        delay: 2000,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
        dynamicBullets: true, // Only shows a few bullets
        dynamicMainBullets: 10 // Limit visible bullets to 10
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      breakpoints: {
        0: {
          slidesPerView: 2,
        },
        768: {
          slidesPerView: 3,
        },
        992: {
          slidesPerView: 6,
        },
      },
    });
</script>
<!--================ End Customer Reviews Area =================-->
    @include('front.default.flow')

<!--================ Start Blog Area =================-->
<section class="blog-area section-gap">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="main_title">
            <h2><span>{{ $setting->heading_3 ?? '' }}</span></h2>
            <p>{{ $setting->intro_3 ?? '' }}</p>
          </div>
        </div>
      </div>
      <div class="row">
        @foreach($mostPopularBlog as $blog)
        <div class="col-lg-4 col-md-6">
          <div class="single-blog">
            <div class="thumb">
              <img style="height:250px;" class="img-fluid" src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->name }}">
            </div>
            <div class="short_details">
              <div class="meta-top d-flex">
                <a href="#">By Admin</a>
                <a href="#"><i class="ti-comments-smiley"></i>{{ $blog->comments->count() }} Comments</a>
              </div>
              <a class="d-block" href="{{ route('blogs.details', $blog->slug) }}">
                <h4>{{ Str::limit($blog->name, 70) }}</h4>
              </a>
              <div class="text-wrap">
                <p>{{ Str::limit(strip_tags($blog->description), 100) }}</p>
              </div>
              <a href="{{ route('blogs.details', $blog->slug) }}" class="blog_btn">
                Learn More <span class="ml-2 ti-arrow-right"></span>
              </a>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
</section>
<!--================ End Blog Area =================-->

<!--================ Start Scripts =================-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '.auth-required', function() {
        var action = $(this).data('action');
        Swal.fire({
            icon: 'warning',
            title: 'Login Required',
            text: 'Please log in to add this item to your ' + action + '.',
            confirmButtonText: 'Go to Login',
            showCancelButton: true,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("user.login") }}';
            }
        });
    });

    $(document).on('click', '.add-to-wishlist', function() {
        var productId = $(this).data('id');
        $.ajax({
            url: '{{ route("wishlist.add") }}',
            type: 'POST',
            data: {
                product_id: productId
            },
            success: function(res) {
                if (res.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Wishlist!',
                        text: 'This product has been added to your wishlist.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Notice',
                        text: res.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed!',
                    text: 'Something went wrong. Try again.',
                });
                console.error(xhr.responseText);
            }
        });
    });

    $(document).on('click', '.add-to-cart', function() {
        let productId = $(this).data('id');
        let quantity = $(this).data('qty');
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart!',
                        text: response.message,
                        confirmButtonColor: '#71cd14'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Something went wrong!'
                });
            }
        });
    });
});
</script>
@endsection