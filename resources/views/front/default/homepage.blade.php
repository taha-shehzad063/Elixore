@extends('front.default.partials.app')
@section('content')

  <!--================Home Banner Area =================-->
<style>
  .home_banner_area {
    background-color: #000; /* fallback bg color */
    transition: background-image 0.5s ease-in-out;
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
      <img class="img-fluid w-100" 
            src="{{ asset('storage/' . $product->galleries->first()->image) }}"
           alt="{{ $product->name }}" />
      <div class="p_icon">
      <a href="{{ route('product.details', $product->slug) }}">
    <i class="ti-eye"></i>
</a>

<a href="javascript:void(0);" class="add-to-wishlist" data-id="{{ $product->id }}">
          <i class="ti-heart"></i>
        </a>
        <a href="javascript:void(0);" 
           class="add-to-cart" 
           data-id="{{ $product->id }}" 
           data-qty="1">
          <i class="ti-shopping-cart"></i>
        </a>
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
<section class="offer_area" style="background: url('{{ asset('storage/' . $collections->image) }}') no-repeat center; background-size: cover;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-lg-4 col-lg-6 text-center">
                <div class="offer_content text-white">
                    <h3 class="text-uppercase mb-4">{{ $collections->title }}</h3>
                    <h2 class="text-uppercase">{{ $collections->heading }}</h2>
                    @if($collections->button_url && $collections->button_text)
                        <a href="{{ $collections->button_url }}" class="main_btn mb-3 mt-4">{{ $collections->button_text }}</a>
                    @endif
<p class="text-muted">{{ $collections->sale_text }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
  <!--================ End Offer Area =================-->

  <!--================ New Product Area =================-->
 
  <!--================ End New Product Area =================-->

  <!--================ Inspired Product Area =================-->
  <section class="inspired_product_area section_gap_bottom_custom">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-12">
          <div class="main_title">
            <h2><span>{{$setting->heading_1 ?? ''}}</span></h2>
            <p>{{$setting->heading_2 ?? ''}}</p>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-3 col-md-6">
          <div class="single-product">
            <div class="product-img">
              <img class="img-fluid w-100" src="{{ asset('assets/img/product/inspired-product/i1.jpg') }}" alt="" />
              <div class="p_icon">
                <a href="#">
                  <i class="ti-eye"></i>
                </a>
                <a href="#">
                  <i class="ti-heart"></i>
                </a>
                <a href="#">
                  <i class="ti-shopping-cart"></i>
                </a>
              </div>
            </div>
            <div class="product-btm">
              <a href="#" class="d-block">
                <h4>Latest men’s sneaker</h4>
              </a>
              <div class="mt-3">
                <span class="mr-4">$25.00</span>
                <del>$35.00</del>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="single-product">
            <div class="product-img">
              <img class="img-fluid w-100" src="{{ asset('assets/img/product/inspired-product/i2.jpg') }}" alt="" />
              <div class="p_icon">
                <a href="#">
                  <i class="ti-eye"></i>
                </a>
                <a href="#">
                  <i class="ti-heart"></i>
                </a>
                <a href="#">
                  <i class="ti-shopping-cart"></i>
                </a>
              </div>
            </div>
            <div class="product-btm">
              <a href="#" class="d-block">
                <h4>Latest men’s sneaker</h4>
              </a>
              <div class="mt-3">
                <span class="mr-4">$25.00</span>
                <del>$35.00</del>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="single-product">
            <div class="product-img">
              <img class="img-fluid w-100" src="{{ asset('assets/img/product/inspired-product/i3.jpg') }}" alt="" />
              <div class="p_icon">
                <a href="#">
                  <i class="ti-eye"></i>
                </a>
                <a href="#">
                  <i class="ti-heart"></i>
                </a>
                <a href="#">
                  <i class="ti-shopping-cart"></i>
                </a>
              </div>
            </div>
            <div class="product-btm">
              <a href="#" class="d-block">
                <h4>Latest men’s sneaker</h4>
              </a>
              <div class="mt-3">
                <span class="mr-4">$25.00</span>
                <del>$35.00</del>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="single-product">
            <div class="product-img">
              <img class="img-fluid w-100" src="{{ asset('assets/img/product/inspired-product/i4.jpg') }}" alt="" />
              <div class="p_icon">
                <a href="#">
                  <i class="ti-eye"></i>
                </a>
                <a href="#">
                  <i class="ti-heart"></i>
                </a>
                <a href="#">
                  <i class="ti-shopping-cart"></i>
                </a>
              </div>
            </div>
            <div class="product-btm">
              <a href="#" class="d-block">
                <h4>Latest men’s sneaker</h4>
              </a>
              <div class="mt-3">
                <span class="mr-4">$25.00</span>
                <del>$35.00</del>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
          <div class="single-product">
            <div class="product-img">
              <img class="img-fluid w-100" src="{{ asset('assets/img/product/inspired-product/i5.jpg') }}" alt="" />
              <div class="p_icon">
                <a href="#">
                  <i class="ti-eye"></i>
                </a>
                <a href="#">
                  <i class="ti-heart"></i>
                </a>
                <a href="#">
                  <i class="ti-shopping-cart"></i>
                </a>
              </div>
            </div>
            <div class="product-btm">
              <a href="#" class="d-block">
                <h4>Latest men’s sneaker</h4>
              </a>
              <div class="mt-3">
                <span class="mr-4">$25.00</span>
                <del>$35.00</del>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="single-product">
            <div class="product-img">
              <img class="img-fluid w-100" src="{{ asset('assets/img/product/inspired-product/i6.jpg') }}" alt="" />
              <div class="p_icon">
                <a href="#">
                  <i class="ti-eye"></i>
                </a>
                <a href="#">
                  <i class="ti-heart"></i>
                </a>
                <a href="#">
                  <i class="ti-shopping-cart"></i>
                </a>
              </div>
            </div>
            <div class="product-btm">
              <a href="#" class="d-block">
                <h4>Latest men’s sneaker</h4>
              </a>
              <div class="mt-3">
                <span class="mr-4">$25.00</span>
                <del>$35.00</del>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="single-product">
            <div class="product-img">
              <img class="img-fluid w-100" src="{{ asset('assets/img/product/inspired-product/i7.jpg') }}" alt="" />
              <div class="p_icon">
                <a href="#">
                  <i class="ti-eye"></i>
                </a>
                <a href="#">
                  <i class="ti-heart"></i>
                </a>
                <a href="#">
                  <i class="ti-shopping-cart"></i>
                </a>
              </div>
            </div>
            <div class="product-btm">
              <a href="#" class="d-block">
                <h4>Latest men’s sneaker</h4>
              </a>
              <div class="mt-3">
                <span class="mr-4">$25.00</span>
                <del>$35.00</del>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="single-product">
            <div class="product-img">
              <img class="img-fluid w-100" src="{{ asset('assets/img/product/inspired-product/i8.jpg') }}" alt="" />
              <div class="p_icon">
                <a href="#">
                  <i class="ti-eye"></i>
                </a>
                <a href="#">
                  <i class="ti-heart"></i>
                </a>
                <a href="#">
                  <i class="ti-shopping-cart"></i>
                </a>
              </div>
            </div>
            <div class="product-btm">
              <a href="#" class="d-block">
                <h4>Latest men’s sneaker</h4>
              </a>
              <div class="mt-3">
                <span class="mr-4">$25.00</span>
                <del>$35.00</del>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--================ End Inspired Product Area =================-->

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
    @foreach($latestThreeBlogs as $blog)
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

    $(document).on('click', '.add-to-wishlist', function(){
        var productId = $(this).data('id');
        $.ajax({
            url: '{{ route("wishlist.add") }}',
            type: 'POST',
            data: {
                product_id: productId
            },
            success: function(res){
                if(res.status){
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
                if (xhr.status === 401 && xhr.responseJSON?.redirect) {
                    // User not logged in, redirect to login page
                    window.location.href = xhr.responseJSON.redirect;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed!',
                        text: 'Something went wrong. Try again.',
                    });
                    console.error(xhr.responseText);
                }
            }
        });
    });
});
</script>
<script>
$(document).ready(function() {
    $('.add-to-cart').on('click', function() {
        let productId = $(this).data('id');
        let quantity = $(this).data('qty');

        $.ajax({
            url: '{{ route('cart.add') }}',
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