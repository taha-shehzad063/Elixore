@extends('front.default.partials.app')
@section('content')
<style>
.color-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid #fff;
    cursor: pointer;
    display: inline-block;
    transition: transform 0.2s;
}

.color-circle:hover {
    transform: scale(1.2);
}

.color-circle.selected {
    border: 4px solid #71b43c; /* your theme color */
}



    .product-description img,
    .product-description video {
        max-width: 500px;
        max-height: 500px;
        width: auto;
        height: auto;
        display: block;
        margin: 10px 0;
        object-fit: contain;
        border-radius: 8px;
    }
    @media (max-width: 575.98px) {
        .product-description img,
        .product-description video {
            max-width: 300px;
            max-height: 300px;
        }
    }
    .option-label input[type="radio"]:checked + span,
    .option-label.active {
        background: #222;
        color: #fff;
        border-color: #222;
    }
    /* Pagination Styling */
    .pagination {
        justify-content: center;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }
    .pagination .page-item .page-link {
        color: #71cd14;
        background-color: #fff;
        border: 1px solid #71cd14;
        border-radius: 50px;
        padding: 8px 16px;
        margin: 0 5px;
        font-size: 14px;
        transition: all 0.3s ease;
        min-width: 36px;
        text-align: center;
    }
    .pagination .page-item.active .page-link {
        background-color: #71cd14;
        color: #fff;
        border-color: #71cd14;
    }
    .pagination .page-item.disabled .page-link {
        color: #ccc;
        border-color: #ccc;
        background-color: #f8f9fa;
        cursor: not-allowed;
    }
    .pagination .page-item .page-link:hover:not(.disabled) {
        background-color: #5fbf10;
        color: #fff;
        border-color: #5fbf10;
    }
    .dark-mode .pagination .page-item .page-link {
        color: #71cd14;
        background-color: #2a2a2a;
        border-color: #71cd14;
    }
    .dark-mode .pagination .page-item.active .page-link {
        background-color: #71cd14;
        color: #fff;
        border-color: #71cd14;
    }
    .dark-mode .pagination .page-item.disabled .page-link {
        color: #666;
        border-color: #666;
        background-color: #1e1e1e;
    }
    .dark-mode .pagination .page-item .page-link:hover:not(.disabled) {
        background-color: #5fbf10;
        color: #fff;
        border-color: #5fbf10;
    }
    /* Star Rating Styling */
    .star-rating {
        display: inline-block;
        position: relative;
        height: 25px;
        line-height: 25px;
        font-size: 25px;
        cursor: pointer;
    }
    .star-rating .star {
        color: #ccc;
        display: inline-block;
        position: relative;
        z-index: 2;
    }
    .star-rating .star.filled {
        color: #71cd14;
    }
    .star-rating .star.half-filled::after {
        content: '\f123'; /* FontAwesome half-star */
        position: absolute;
        left: 0;
        top: 0;
        color: #71cd14;
        z-index: 1;
    }
    /* Modal Styling */
    #imageModal .modal-dialog {
        max-width: 100%;
        margin: 0;
        height: 100vh;
    }
    #imageModal .modal-content {
        background: transparent;
        border: none;
        height: 100%;
    }
    #imageModal .modal-body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        padding: 0;
    }
    #imageModal img {
        max-width: 100%;
        max-height: 100vh;
        object-fit: contain;
    }
    #imageModal .close {
        position: absolute;
        top: 15px;
        right: 15px;
        color: #fff;
        opacity: 0.8;
        font-size: 30px;
        z-index: 1050;
    }
    #imageModal .close:hover {
        opacity: 1;
    }
    .fa-star {
        color: #71cd14 !important;
    }
    .fa-star-half-o {
        color: #71cd14 !important;
    }
    /* ElevateZoom CSS */
    .zoomContainer {
        z-index: 1000;
    }
    .zoomWindow {
        background: #fff;
        border: 1px solid #ccc;
        z-index: 1001;
    }
    /* Carousel Indicators Styling (Bottom Right) */
    .carousel-indicators {
        position: absolute;
        bottom: 10px;
        right: 10px;
        left: auto;
        width: auto;
        margin: 0;
        padding: 5px;
        display: flex;
        justify-content: flex-end;
        z-index: 1002;
    }
    .carousel-indicators li {
        width: 60px;
        height: 60px;
        margin: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        overflow: hidden;
        cursor: pointer;
        opacity: 0.7;
        background: none;
    }
    .carousel-indicators li.active {
        opacity: 1;
        border-color: #71cd14;
    }
    .carousel-indicators li img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    /* Ensure carousel controls are clickable */
    .carousel-control-prev,
    .carousel-control-next {
        z-index: 1002;
        opacity: 0.7;
        width: 10%;
    }
    .carousel-control-prev:hover,
    .carousel-control-next:hover {
        opacity: 1;
    }
    .zoom-wrapper {
        position: relative;
        z-index: 999;
    }
</style>

<div class="product_image_area">
    <div class="container">
        <div class="row s_product_inner">
            <div class="col-lg-6">
                <div class="s_product_img">
                    <div id="carouselExampleIndicators" class="carousel slide" data-interval="false">
                        <ol class="carousel-indicators">
                            @foreach($product->galleries as $index => $gallery)
                                <li data-target="#carouselExampleIndicators" data-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}">
                                    @php
                                        $thumbUrl = Str::startsWith($gallery->image, ['http://', 'https://'])
                                            ? $gallery->image
                                            : asset($gallery->image);
                                    @endphp
                                    <img src="{{ $thumbUrl }}" alt="Thumbnail {{ $index + 1 }}">
                                </li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            @foreach($product->galleries as $index => $gallery)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <div class="zoom-wrapper">
                                        @php
                                            if (Str::startsWith($gallery->image, ['http://', 'https://'])) {
                                                $imageUrl = $gallery->image;
                                            } else {
                                                $imageUrl = asset($gallery->image);
                                            }
                                        @endphp
                                        <a href="{{ $imageUrl }}" class="image-link" data-image="{{ $imageUrl }}">
                                            <img class="d-block w-100 product-image elevate-zoom" src="{{ $imageUrl }}" data-zoom-image="{{ $imageUrl }}" alt="Slide {{ $index + 1 }}" style="max-width:100%;height:auto;" />
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($product->galleries->count() > 1)
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="s_product_text">
                    <h3>{{ $product->name }}</h3>
                    <h2 id="product-total-price">{{ number_format($product->price, 2) }}</h2>
                    <ul class="list">
                        <li>
                            <a class="active" href="#">
                                <span>Category</span> : {{ $product->category->name ?? 'N/A' }}
                            </a>
                        </li>
                        <li>
                            <a href="#"> <span>Availibility</span> : {{ ucfirst($product->availability) }}</a>
                        </li>
                    </ul>
                    <div class="text-success fw-bold">
                        Total Sold: {{ number_format($totalSold) }}
                    </div>
@if($product->color)
    @php
        $colors = explode(',', $product->color);
    @endphp

    <div class="mb-3" id="product-color">
        <label><strong>Available Colors:</strong></label>
        <div class="d-flex flex-wrap gap-2 mt-2">
            @foreach($colors as $color)
                @php $c = trim($color); @endphp
                <label class="color-circle" style="background-color: {{ $c }};" data-color="{{ $c }}">
                    <input type="checkbox" name="selected_color[]" class="d-none" value="{{ $c }}">
                </label>
            @endforeach
        </div>
    </div>
@endif





                    @php
                        $shareText = urlencode("Check out this product: " . route('product.details', $product->slug));
                    @endphp
                    <a href="https://wa.me/?text={{ $shareText }}" target="_blank" class="btn btn-success">
                        <i class="fab fa-whatsapp"></i> Share on WhatsApp
                    </a>
                    <div class="card_area mb-3 mt-1">
                        <a class="main_btn mt-1" href="#" id="addToCartBtn" data-product="{{ $product->id }}">Add to Cart</a>
                        <a class="main_btn mt-1 btn-buy-now" href="#" data-product="{{ $product->id }}" style="background:#71cd14;color:#fff;">Buy Now</a>
                    </div>
          @if($product->color)
    @php
        $colors = explode(',', $product->color);
    @endphp

    <div class="mb-3" id="product-color">
        <label><strong>Available Colors:</strong></label>
        <div class="d-flex flex-wrap gap-2 mt-2">
            @foreach($colors as $color)
                @php $c = trim($color); @endphp
                <label class="color-circle" style="background-color: {{ $c }};" data-color="{{ $c }}">
                    <input type="checkbox" name="selected_color[]" class="d-none" value="{{ $c }}">
                </label>
            @endforeach
        </div>
    </div>
@endif



                    {!! $product->info !!}
                    <div id="watching-count" style="font-weight:bold;color:#71cd14;margin-bottom:10px;">
                        <i class="fa fa-eye" style="color:black;margin-right:5px;"></i>
                        Currently <span id="watching-number"></span> customers watching this product
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" alt="Product Image" style="width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>

<section class="product_description_area">
    <div class="container">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active no-dark" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Description</a>
            </li>
            @if($product->specifications && $product->specifications->count())
                <li class="nav-item">
                    <a class="nav-link no-dark" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Specification</a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link no-dark" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false">Reviews</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="product-description">
                    {!! $product->description !!}
                </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            @if($product->specifications && $product->specifications->count())
                                @foreach($product->specifications as $spec)
                                    <tr>
                                        <td><h5>{{ $spec->key }}</h5></td>
                                        <td><h5>{{ $spec->value }}</h5></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2"><h5>No specifications found.</h5></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                <div class="row">
                    <div class="col-lg-6">
                        @php
                            $average = $reviews->avg('rating');
                            $count = $reviews->count();
                            $ratingBreakdown = $reviews->groupBy('rating')->map->count();
                        @endphp
                        <div class="row total_rate">
                            <div class="col-6">
                                <div class="box_total">
                                    <h5 class="no-dark8">Overall</h5>
                                    <h4 class="no-dark8" id="overall-rating">{{ number_format($average, 1) }}</h4>
                                    <h6 class="no-dark8" id="review-count">({{ $count }} Reviews)</h6>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rating_list">
                                    <h3 id="rating-list-title">Based on {{ $count }} Reviews</h3>
                                    <ul class="list no-dark8" id="rating-breakdown">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <li>
                                                <a href="#">
                                                    {{ $i }} Star
                                                    @for ($j = 0; $j < 5; $j++)
                                                        <i class="fa fa-star{{ $j < $i ? '' : '-o' }}"></i>
                                                    @endfor
                                                    {{ $ratingBreakdown[$i] ?? 0 }}
                                                </a>
                                            </li>
                                        @endfor
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="review_list" id="review-list">
                            @if($reviews->count())
                                @foreach ($reviews as $review)
                                    <div class="review_item" data-review-id="{{ $review->id }}">
                                        <div class="media">
                                            <div class="d-flex">
                                                <img src="{{ asset('assets/img/user.jpg') }}" alt="" style="width: 50px;" />
                                            </div>
                                            <div class="media-body">
                                                <h4>{{ $review->name }}</h4>
                                                @php
                                                    $fullStars = floor($review->rating);
                                                    $halfStar = ($review->rating - $fullStars) >= 0.5 ? 1 : 0;
                                                    $emptyStars = 5 - $fullStars - $halfStar;
                                                @endphp
                                                @for ($i = 0; $i < $fullStars; $i++)
                                                    <i class="fa fa-star"></i>
                                                @endfor
                                                @if ($halfStar)
                                                    <i class="fa fa-star-half-o"></i>
                                                @endif
                                                @for ($i = 0; $i < $emptyStars; $i++)
                                                    <i style="color: #E5E4E2;" class="fa fa-star-o"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <p>{{ $review->message }}</p>
                                        @foreach ($review->replies as $reply)
                                            <div class="ml-4 mt-2 bg-light p-2 rounded">
                                                <img src="{{ asset('assets/img/user.jpg') }}" alt="" style="width: 50px;" />
                                                <strong>{{ $reply->name }}</strong>: {{ $reply->reply }}
                                            </div>
                                        @endforeach
                                        <div class="reply-form-container ml-4 mt-2" id="reply-form-{{ $review->id }}" style="display: none;">
                                            <form action="{{ route('review.reply') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="review_id" value="{{ $review->id }}">
                                                <div class="form-group">
                                                    <input type="text" name="name" class="form-control" placeholder="Your Name" required />
                                                </div>
                                                <div class="form-group">
                                                    <textarea name="reply" class="form-control" rows="2" placeholder="Your Reply" required></textarea>
                                                </div>
                                                <button class="btn btn-sm btn-secondary">Reply</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p>No reviews yet.</p>
                            @endif
                        </div>
                        <!-- Pagination -->
                        @if($reviews->hasPages())
                            <nav aria-label="Review pagination">
                                <ul class="pagination">
                                    <li class="page-item {{ $reviews->onFirstPage() ? 'disabled' : '' }}">
                                        <a class="page-link" href="{{ $reviews->previousPageUrl() }}" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    @foreach($reviews->getUrlRange(1, $reviews->lastPage()) as $page => $url)
                                        <li class="page-item {{ $reviews->currentPage() == $page ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $url }}" data-page="{{ $page }}">{{ $page }}</a>
                                        </li>
                                    @endforeach
                                    <li class="page-item {{ $reviews->hasMorePages() ? '' : 'disabled' }}>
                                        <a class="page-link" href="{{ $reviews->nextPageUrl() }}" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        <div class="review_box">
                            <h4>Add a Review</h4>
                            <form class="row contact_form" action="{{ route('review.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="col-md-12 no-dark-rating">
                                    <div class="form-group">
                                        <div class="star-rating" data-rating="0">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fa fa-star-o star" data-value="{{ $i }}"></i>
                                            @endfor
                                            <input type="hidden" name="rating" id="rating-value" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Your Full Name" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="text" name="phone" class="form-control" placeholder="Phone Number">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea class="form-control" name="message" rows="3" placeholder="Review" required></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn submit_btn btn-theme">Submit Now</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

<!-- External Dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/elevatezoom/3.0.8/jquery.elevatezoom.min.js"></script>

<!-- Fallback for ElevateZoom if CDN fails -->
<script>
    if (typeof jQuery.fn.elevateZoom === 'undefined') {
        console.warn('ElevateZoom CDN failed, attempting to load local fallback');
        document.write('<script src="{{ asset('assets/js/jquery.elevatezoom.min.js') }}"><\/script>');
    }
</script>

<script>
(function($) {
    $(document).ready(function () {
        let $elevateZoom = null;

        // Initialize ElevateZoom for desktop
        function initElevateZoom() {
            if (window.innerWidth >= 768) {
                if ($.fn.elevateZoom) {
                    console.log('Initializing ElevateZoom'); // Debug
                    // Remove existing ElevateZoom instances
                    if ($elevateZoom) {
                        $elevateZoom.removeData('elevateZoom');
                        $('.zoomContainer').remove();
                    }
                    // Initialize ElevateZoom for the active slide
                    $elevateZoom = $('.carousel-item.active .elevate-zoom').elevateZoom({
                        zoomType: 'window',
                        cursor: 'crosshair',
                        zoomWindowFadeIn: 500,
                        zoomWindowFadeOut: 500,
                        zoomWindowWidth: 400,
                        zoomWindowHeight: 400,
                        borderSize: 1,
                        borderColour: '#ccc',
                        lensFadeIn: 200,
                        lensFadeOut: 200,
                        constrainType: 'height',
                        constrainSize: 400,
                        zoomWindowPosition: 1,
                        zoomWindowOffetx: 10
                    });
                    // Verify image URLs
                    $('.elevate-zoom').each(function() {
                        console.log('Image URL:', $(this).attr('src')); // Debug
                    });
                } else {
                    console.error('ElevateZoom plugin not loaded');
                }
            } else {
                // Remove ElevateZoom for mobile devices
                if ($elevateZoom) {
                    $elevateZoom.removeData('elevateZoom');
                    $('.zoomContainer').remove();
                    $elevateZoom = null;
                }
            }
        }

        // Run ElevateZoom on load and resize
        initElevateZoom();
        $(window).on('resize', initElevateZoom);

        // Reinitialize ElevateZoom on carousel slide change
        $('#carouselExampleIndicators').on('slid.bs.carousel', function() {
            if (window.innerWidth >= 768 && $.fn.elevateZoom) {
                console.log('Reinitializing ElevateZoom on slide change'); // Debug
                // Remove existing ElevateZoom instances
                if ($elevateZoom) {
                    $elevateZoom.removeData('elevateZoom');
                    $('.zoomContainer').remove();
                }
                // Initialize ElevateZoom for the active slide
                $elevateZoom = $('.carousel-item.active .elevate-zoom').elevateZoom({
                    zoomType: 'window',
                    cursor: 'crosshair',
                    zoomWindowFadeIn: 500,
                    zoomWindowFadeOut: 500,
                    zoomWindowWidth: 400,
                    zoomWindowHeight: 400,
                    borderSize: 1,
                    borderColour: '#ccc',
                    lensFadeIn: 200,
                    lensFadeOut: 200,
                    constrainType: 'height',
                    constrainSize: 400,
                    zoomWindowPosition: 1,
                    zoomWindowOffetx: 10
                });
            }
        });

        // Ensure carousel controls and indicators are clickable
        $('.carousel-control-prev, .carousel-control-next').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent ElevateZoom from capturing these clicks
            const $carousel = $('#carouselExampleIndicators');
            const direction = $(this).hasClass('carousel-control-prev') ? 'prev' : 'next';
            $carousel.carousel(direction);
        });

        $('.carousel-indicators li').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent ElevateZoom from capturing these clicks
            const $carousel = $('#carouselExampleIndicators');
            const slideIndex = $(this).data('slide-to');
            $carousel.carousel(slideIndex);
        });

        // Image modal for mobile/tablet
        $('.image-link').on('click', function(e) {
            e.preventDefault();
            if (window.innerWidth < 768) {
                var imageSrc = $(this).data('image');
                console.log('Opening modal with image:', imageSrc); // Debug
                $('#modalImage').attr('src', imageSrc);
                $('#imageModal').modal({
                    backdrop: 'static',
                    keyboard: false
                }).modal('show');
            }
        });

        // Prevent ElevateZoom from capturing clicks on the image itself
        $('.elevate-zoom').on('click', function(e) {
            if (window.innerWidth >= 768) {
                e.stopPropagation(); // Allow clicks on image for zooming, but don't propagate to parent
            }
        });

        // Price update functionality
        function updateTotalPrice() {
            let basePrice = parseFloat({{ $product->price }});
            let total = isNaN(basePrice) ? 0 : basePrice;
            $('.option-input:checked').each(function() {
                let price = parseFloat($(this).data('price'));
                total += isNaN(price) ? 0 : price;
            });
            $('#product-total-price').text(total.toFixed(2));
        }

        // Option selection handling
        $(document).on('change', '.option-input', function() {
            updateTotalPrice();
            $('.option-label').removeClass('active');
            $('.option-input:checked').each(function() {
                $(this).closest('.option-label').addClass('active');
            });
        });

        // Initial price calculation
        updateTotalPrice();

        // Add to Cart and Buy Now
       $(document).on('click', '#addToCartBtn, .btn-buy-now', function(e) {
    e.preventDefault();
    var $btn = $(this);

    // Get selected colors
    let selectedColors = $('.color-circle input:checked').map(function() {
        return $(this).val();
    }).get();

    // If no color selected, automatically select the first one
    if (selectedColors.length === 0) {
        let firstColorInput = $('.color-circle input').first();
        firstColorInput.prop('checked', true);
        firstColorInput.closest('.color-circle').addClass('selected');
        selectedColors = [firstColorInput.val()];
    }

    $.ajax({
        url: "{{ route('cart.add') }}",
        type: "POST",
        data: {
            product_id: $btn.data('product'),
            quantity: 1,
            total_price: $('#product-total-price').text(),
            selected_color: selectedColors, // send multiple colors
            _token: "{{ csrf_token() }}"
        },
        success: function(res) {
            if (res.status) {
                updateHeaderCounts();
                if ($btn.hasClass('btn-buy-now')) {
                    window.location.href = "{{ route('checkout') }}";
                } else {
                    Swal.fire('Success', res.message, 'success');
                    window.location.href = "{{ route('cart') }}";
                }
            } else {
                Swal.fire('Error', res.message || 'Something went wrong.', 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Error', 'Failed to add to cart.', 'error');
        }
    });
});


        // Update header counts
        function updateHeaderCounts() {
            $.ajax({
                url: '{{ route("get.cart.wishlist.counts") }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#desktopCartCount').text(response.cartCount > 0 ? response.cartCount : '').toggle(response.cartCount > 0);
                    $('#desktopWishlistCount').text(response.wishlistCount > 0 ? response.wishlistCount : '').toggle(response.wishlistCount > 0);
                    $('#mobileCartCount').text(response.cartCount > 0 ? response.cartCount : '').toggle(response.cartCount > 0);
                    $('#mobileWishlistCount').text(response.wishlistCount > 0 ? response.wishlistCount : '').toggle(response.wishlistCount > 0);
                },
                error: function(xhr) {
                    console.error('Error fetching header counts:', xhr);
                }
            });
        }

        // Initialize reply form toggles
        function initializeReplyToggles() {
            $('.toggle-reply-form').off('click').on('click', function () {
                const reviewId = $(this).data('review-id');
                $('.reply-form-container').hide();
                $('#reply-form-' + reviewId).toggle();
            });
        }
        initializeReplyToggles();

        // Enhanced Star Rating Functionality
        let currentRating = 0;
        const $starContainer = $('.star-rating');
        const $stars = $('.star-rating .star');

        $starContainer.on('mousemove', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const starWidth = rect.width / 5;
            let hoverValue = Math.ceil((x / starWidth) * 2) / 2; // Nearest 0.5
            hoverValue = Math.max(0, Math.min(5, hoverValue));
            updateStars(hoverValue);
        });

        $starContainer.on('mouseleave', function() {
            updateStars(currentRating);
        });

        $starContainer.on('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const starWidth = rect.width / 5;
            currentRating = Math.ceil((x / starWidth) * 2) / 2; // Nearest 0.5
            currentRating = Math.max(0, Math.min(5, currentRating));
            $('#rating-value').val(currentRating);
            updateStars(currentRating);
        });

        function updateStars(rating) {
            $stars.each(function(index) {
                const value = index + 1;
                $(this).removeClass('fa-star fa-star-half-o fa-star-o filled half-filled');
                if (rating >= value) {
                    $(this).addClass('fa-star filled');
                } else if (rating >= value - 0.5) {
                    $(this).addClass('fa-star-o half-filled');
                } else {
                    $(this).addClass('fa-star-o');
                }
            });
        }

        // Update review summary UI
        function updateReviewSummary() {
            $.ajax({
                url: "{{ route('product.reviews.summary', $product->id) }}",
                method: "GET",
                success: function(summary) {
                    $('#overall-rating').text(summary.average ? summary.average.toFixed(1) : '0.0');
                    $('#review-count').text(`(${summary.count || 0} Reviews)`);
                    $('#rating-list-title').text(`Based on ${summary.count || 0} Reviews`);
                    $('#rating-breakdown').empty();
                    for (let i = 5; i >= 1; i--) {
                        let count = summary.ratingBreakdown && summary.ratingBreakdown[i] ? summary.ratingBreakdown[i] : 0;
                        let starsHtml = '';
                        for (let j = 0; j < 5; j++) {
                            starsHtml += `<i class="fa fa-star${j < i ? '' : '-o'}"></i>`;
                        }
                        $('#rating-breakdown').append(`
                            <li>
                                <a href="#">
                                    ${i} Star ${starsHtml} ${count}
                                </a>
                            </li>
                        `);
                    }
                },
                error: function(xhr) {
                    console.error('Failed to fetch review summary:', xhr);
                    Swal.fire('Error', 'Failed to fetch review summary.', 'error');
                }
            });
        }

        // Load reviews with pagination
        function loadReviews(page) {
            $.ajax({
                url: "{{ route('product.reviews', $product->id) }}",
                method: 'GET',
                data: { page: page },
                success: function(res) {
                    if (res.status && res.reviews) {
                        $('#review-list').empty();
                        if (res.reviews.data.length === 0) {
                            $('#review-list').html('<p>No reviews yet.</p>');
                        } else {
                            $.each(res.reviews.data, function(index, review) {
                                var fullStars = Math.floor(review.rating);
                                var halfStar = (review.rating - fullStars) >= 0.5 ? 1 : 0;
                                var emptyStars = 5 - fullStars - halfStar;
                                var starsHtml = '';
                                for (var i = 0; i < fullStars; i++) starsHtml += '<i class="fa fa-star"></i>';
                                if (halfStar) starsHtml += '<i class="fa fa-star-half-o"></i>';
                                for (var i = 0; i < emptyStars; i++) starsHtml += '<i style="color: #E5E4E2;" class="fa fa-star-o"></i>';

                                var repliesHtml = '';
                                if (review.replies && review.replies.length) {
                                    $.each(review.replies, function(idx, reply) {
                                        repliesHtml += `
                                            <div class="ml-4 mt-2 bg-light p-2 rounded">
                                                <img src="{{ asset('assets/img/user.jpg') }}" alt="" style="width: 50px;" />
                                                <strong>${reply.name || 'Anonymous'}</strong>: ${reply.reply || ''}
                                            </div>
                                        `;
                                    });
                                }

                                var reviewHtml = `
                                    <div class="review_item" data-review-id="${review.id}">
                                        <div class="media">
                                            <div class="d-flex">
                                                <img src="{{ asset('assets/img/user.jpg') }}" alt="" style="width: 50px;" />
                                            </div>
                                            <div class="media-body">
                                                <h4>${review.name || 'Anonymous'}</h4>
                                                ${starsHtml}
                                            </div>
                                        </div>
                                        <p>${review.message || ''}</p>
                                        ${repliesHtml}
                                        <div class="reply-form-container ml-4 mt-2" id="reply-form-${review.id}" style="display: none;">
                                            <form action="{{ route('review.reply') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="review_id" value="${review.id}">
                                                <div class="form-group">
                                                    <input type="text" name="name" class="form-control" placeholder="Your Name" required />
                                                </div>
                                                <div class="form-group">
                                                    <textarea name="reply" class="form-control" rows="2" placeholder="Your Reply" required></textarea>
                                                </div>
                                                <button class="btn btn-sm btn-secondary">Reply</button>
                                            </form>
                                        </div>
                                    </div>
                                `;
                                $('#review-list').append(reviewHtml);
                            });
                        }

                        // Update pagination
                        updatePagination(res.reviews);
                        initializeReplyToggles();
                    } else {
                        $('#review-list').html('<p>No reviews yet.</p>');
                        $('.pagination').empty();
                    }
                },
                error: function(xhr) {
                    console.error('Failed to load reviews:', xhr);
                    Swal.fire('Error', 'Failed to load reviews.', 'error');
                }
            });
        }

        // Update pagination links
        function updatePagination(reviews) {
            var currentPage = reviews.current_page;
            var lastPage = reviews.last_page;
            var html = '';

            if (lastPage > 1) {
                html += `
                    <nav aria-label="Review pagination">
                        <ul class="pagination">
                            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                                <a class="page-link" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                `;

                var startPage = Math.max(1, currentPage - 2);
                var endPage = Math.min(lastPage, currentPage + 2);

                for (var i = startPage; i <= endPage; i++) {
                    html += `
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>
                    `;
                }

                html += `
                            <li class="page-item ${currentPage === lastPage ? 'disabled' : ''}">
                                <a class="page-link" href="#" data-page="${currentPage + 1}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                `;
            }

            $('.pagination').html(html);

            // Handle pagination clicks
            $('.pagination .page-link').on('click', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                if (page && !$(this).parent().hasClass('disabled')) {
                    loadReviews(page);
                }
            });
        }

        // Review submission
        $('.review_box form').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);
            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                success: function(res) {
                    if (res.status && res.review) {
                        Swal.fire('Success', 'Review added successfully!', 'success');
                        $form[0].reset();
                        $('#rating-value').val(0);
                        currentRating = 0;
                        updateStars(0);
                        loadReviews(1);
                        updateReviewSummary();
                    } else {
                        Swal.fire('Error', res.message || 'Something went wrong.', 'error');
                    }
                },
                error: function(xhr) {
                    console.error('Review submission error:', xhr);
                    Swal.fire('Error', 'Failed to submit review.', 'error');
                }
            });
        });

        // Reply submission
        $('#review-list').on('submit', '.reply-form-container form', function(e) {
            e.preventDefault();
            var $form = $(this);
            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serialize(),
                success: function(res) {
                    if (res.status && res.reply) {
                        Swal.fire('Success', 'Reply added successfully!', 'success');
                        $form[0].reset();
                        var newReply = `
                            <div class="ml-4 mt-2 bg-light p-2 rounded">
                                <img src="{{ asset('assets/img/user.jpg') }}" alt="" style="width: 50px;" />
                                <strong>${res.reply.name || 'Anonymous'}</strong>: ${res.reply.reply || ''}
                            </div>
                        `;
                        $form.closest('.reply-form-container').before(newReply);
                        $form.closest('.reply-form-container').hide();
                        initializeReplyToggles();
                    } else {
                        Swal.fire('Error', res.message || 'Something went wrong.', 'error');
                    }
                },
                error: function(xhr) {
                    console.error('Reply submission error:', xhr);
                    Swal.fire('Error', 'Failed to submit reply.', 'error');
                }
            });
        });

        // Watching count
        function updateWatchingNumber() {
            $.ajax({
                url: "{{ route('api.watching.count') }}",
                method: "GET",
                data: { product_id: "{{ $product->id }}" },
                success: function(res) {
                    $('#watching-number').text(res.count);
                },
                error: function(xhr) {
                    console.error('Failed to fetch watching count:', xhr);
                }
            });
        }

        updateWatchingNumber();
        setInterval(updateWatchingNumber, 15000);

        // Initialize reviews and pagination
        loadReviews(1);
        updateReviewSummary();
    });
})(jQuery.noConflict());
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const circles = document.querySelectorAll('.color-circle');

    circles.forEach(circle => {
        circle.addEventListener('click', function() {
            const input = this.querySelector('input');
            if (!input) return;

            // Toggle checkbox
            input.checked = !input.checked;

            // Toggle selected class
            this.classList.toggle('selected', input.checked);
        });
    });

    // Auto-select first color if none selected
    const anyChecked = Array.from(circles).some(c => c.querySelector('input').checked);
    if (!anyChecked && circles.length > 0) {
        const first = circles[0];
        const input = first.querySelector('input');
        input.checked = true;
        first.classList.add('selected');
    }
});


</script>
