
@extends('front.default.partials.app')
@section('content')

  <!--================Home Banner Area =================-->
<style>
  .home_banner_area {
    background-color: #000; /* fallback bg color */
    transition: background-image 0.5s ease-in-out;
  }
  .swiper-slide {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    font-size: 14px;
    min-height: 150px;
  }
  .swiper-slide div {
    font-size: 18px;
    color: #000;
  }
  .swiper-slide strong {
    display: block;
    margin-top: 15px;
    font-weight: 600;
  }
  .swiper-button-next, .swiper-button-prev {
    color: #000;
    background: #fff;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    box-shadow: 0 0 5px rgba(0,0,0,0.2);
  }
  .swiper-pagination-bullet {
    background: #888;
  }
  .swiper-pagination-bullet-active {
    background: #000;
  }
  .marquee-container {
    background: #000;
    overflow: hidden;
    white-space: nowrap;
    border-top: 2px solid gold; /* Optional top border like in image */
  }
  .marquee-content {
    display: inline-block;
    animation: marquee 15s linear infinite;
    color: #fff;
    font-size: 1.2rem;
    font-weight: bold;
  }
  .marquee-content span {
    display: inline-block;
    margin-right: 50px;
  }
  .marquee-content i {
    font-style: italic;
    font-weight: normal;
  }
  @keyframes marquee {
    0% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
  }


@media (max-width: 438px) {
    .carousel-item.home_banner_area {
          width: 425px !important;
        height: 261px !important;
    }

}

</style>
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

  <!-- Start feature Area -->
  <section class="feature-area section_gap_bottom_custom">
    <div class="container">
      <div class="row">
        <div class="col-lg-3 col-md-6">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-money"></i>
              <h3>Money back gurantee</h3>
            </a>
            <p>Shall open divide a one</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-truck"></i>
              <h3>Free Delivery</h3>
            </a>
            <p>Shall open divide a one</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-support"></i>
              <h3>Alway support</h3>
            </a>
            <p>Shall open divide a one</p>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="single-feature">
            <a href="#" class="title">
              <i class="flaticon-blockchain"></i>
              <h3>Secure payment</h3>
            </a>
            <p>Shall open divide a one</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End feature Area -->

  <!--================ Feature Product Area =================-->
  <section class="feature_product_area section_gap_bottom_custom">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="main_title">
            <h2><span>{{$setting->heading_0 ?? ''}}</span></h2>
            <p>{{$setting->intro_0 ?? ''}}</p>
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
                <a href="{{ route('product.details', $product->slug) }}">
                  <i class="ti-eye"></i>
                </a>
                @guest
                  <a href="javascript:void(0);" class="auth-required" data-action="wishlist" data-id="{{ $product->id }}">
                    <i class="ti-heart"></i>
                  </a>
                @else
                  <a href="javascript:void(0);" class="add-to-wishlist" data-id="{{ $product->id }}">
                    <i class="ti-heart"></i>
                  </a>
                @endguest
                @guest
                  <a href="javascript:void(0);" class="auth-required" data-action="cart" data-id="{{ $product->id }}" data-qty="1">
                    <i class="ti-shopping-cart"></i>
                  </a>
                @else
                  <a href="javascript:void(0);" class="add-to-cart" data-id="{{ $product->id }}" data-qty="1">
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

  <!--================ Inspired Product Area =================-->
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
              <img class="img-fluid w-100 " style="height: 250px; object-fit: cover;"
                   src="{{ asset('storage/' . $product->galleries->first()->image) }}"
                   alt="{{ $product->name }}" />
              <div class="p_icon">
                <a href="{{ route('product.details', $product->slug) }}">
                  <i class="ti-eye"></i>
                </a>
                @guest
                  <a href="javascript:void(0);" class="auth-required" data-action="wishlist" data-id="{{ $product->id }}">
                    <i class="ti-heart"></i>
                  </a>
                @else
                  <a href="javascript:void(0);" class="add-to-wishlist" data-id="{{ $product->id }}">
                    <i class="ti-heart"></i>
                  </a>
                @endguest
                @guest
                  <a href="javascript:void(0);" class="auth-required" data-action="cart" data-id="{{ $product->id }}" data-qty="1">
                    <i class="ti-shopping-cart"></i>
                  </a>
                @else
                  <a href="javascript:void(0);" class="add-to-cart" data-id="{{ $product->id }}" data-qty="1">
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

  <!--================ Start Customer Reviews Area =================-->
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

  <section style="background: #f9f9f9; padding: 60px 0;">
    <div class="container text-center">
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
        <!-- Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Navigation arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
      </div>
    </div>
  </section>

  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script>
    new Swiper(".mySwiper", {
      slidesPerView: 3,
      spaceBetween: 30,
      loop: true,
      autoplay: {
        delay: 2000, // 3-second delay between slides
        disableOnInteraction: false, // Continue autoplay after user interaction
      },
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
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

  <!--================ Start Blog Area =================-->
  <section class="blog-area section-gap">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="main_title">
            <h2><span>{{$setting->heading_3 ?? ''}}</span></h2>
            <p>{{$setting->intro_3 ?? ''}}</p>
          </div>
        </div>
      </div>
      <div class="row">
        @foreach($mostPopularBlog as $blog)
        <div class="col-lg-4 col-md-6">
          <div class="single-blog">
            <div class="thumb">
              <img class="img-fluid" src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->name }}">
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
  <!-- Include jQuery and SweetAlert -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  $(document).ready(function() {
      // Set CSRF header globally
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      // Handle auth-required clicks for unauthenticated users
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

      // Handle wishlist for authenticated users
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

      // Handle cart for authenticated users
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