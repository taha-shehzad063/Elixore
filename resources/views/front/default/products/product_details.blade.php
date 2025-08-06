@extends('front.default.partials.app')
@section('content')
<style>
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
</style>

<div class="product_image_area">
   <div class="container">
      <div class="row s_product_inner">
         <div class="col-lg-6">
            <!-- Carousel section unchanged -->
            <div class="s_product_img">
               <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                  <ol class="carousel-indicators">
                     @foreach($product->galleries as $index => $gallery)
                     <li data-target="#carouselExampleIndicators" data-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $gallery->image) }}" alt="" style="width: 60px; height: 60px; object-fit: cover;">
                     </li>
                     @endforeach
                  </ol>
                  <div class="carousel-inner">
                     @foreach($product->galleries as $index => $gallery)
                     <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="easyzoom easyzoom--overlay">
                           <a href="{{ asset('storage/' . $gallery->image) }}">
                              <img class="d-block w-100"
                                   src="{{ asset('storage/' . $gallery->image) }}"
                                   alt="Slide {{ $index + 1 }}"
                                   style="max-width:100%;height:auto;" />
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
               <h3>{{$product->name}}</h3>
               <h2 id="product-total-price">{{ number_format($product->price, 2) }}</h2>
               <ul class="list">
                  <li>
                     <a class="active" href="#">
                     <span>Category</span> : {{ $product->category->name ?? 'N/A' }}
                     </a>
                  </li>
                  <li>
                     <a href="#"> <span>Availibility</span> : {{ ucfirst($product->availability) }}
                     </a>
                  </li>
               </ul>
               @if($product->options && $product->options->count())
               <div class="mb-3" id="product-options">
                  <label><strong>Weight:</strong></label>
                  <div class="d-block">
                     @foreach($product->options as $option)
                     <label class="btn btn-outline-dark option-label mb-1">
                        <input type="checkbox"
                               class="option-input d-none"
                               data-price="{{ $option->value ?? 0 }}"
                               value="{{ $option->id }}">
                        <span>{{ $option->key }}</span>
                     </label>
                     @endforeach
                  </div>
               </div>
               @endif
               <p>{{$product->info}}</p>
               <div class="card_area">
                  <a class="main_btn" href="#" id="addToCartBtn" data-product="{{ $product->id }}">Add to Cart</a>
                  <a class="main_btn btn-buy-now" href="#" data-product="{{ $product->id }}" style="background:#71cd14;color:#fff;">Buy Now</a>
                  <a class="icon_btn" href="#">
                  <i class="lnr lnr lnr-diamond"></i>
                  </a>
                  <a class="icon_btn" href="#">
                  <i class="lnr lnr lnr-heart"></i>
                  </a>
               </div>
               <div id="watching-count" style="font-weight:bold;color:#71cd14;margin-bottom:10px;">
                  <i class="fa fa-eye" style="color:black;margin-right:5px;"></i>
                  Currently <span id="watching-number"></span> customers watching this product
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<section class="product_description_area">
   <!-- Reviews section unchanged -->
   <div class="container">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
         <li class="nav-item">
            <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Description</a>
         </li>
         @if($product->specifications && $product->specifications->count())
         <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Specification</a>
         </li>
         @endif
         <li class="nav-item">
            <a class="nav-link active" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review" aria-selected="false">Reviews</a>
         </li>
      </ul>
      <div class="tab-content" id="myTabContent">
         <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
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
         <div class="tab-pane fade show active" id="review" role="tabpanel" aria-labelledby="review-tab">
            <div class="row">
               <div class="col-lg-6">
                  @php
                  $average = $product->reviews->avg('rating');
                  $count = $product->reviews->count();
                  $ratingBreakdown = $product->reviews->groupBy('rating')->map->count();
                  @endphp
                  <div class="row total_rate">
                     <div class="col-6">
                        <div class="box_total">
                           <h5>Overall</h5>
                           <h4>{{ number_format($average, 1) }}</h4>
                           <h6>({{ $count }} Reviews)</h6>
                        </div>
                     </div>
                     <div class="col-6">
                        <div class="rating_list">
                           <h3>Based on {{ $count }} Reviews</h3>
                           <ul class="list">
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
                  <div class="review_list">
                     @foreach ($product->reviews as $review)
                     <div class="review_item">
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
                              <i class="fa fa-star text-warning"></i>
                              @endfor
                              @if ($halfStar)
                              <i class="fa fa-star-half-o text-warning"></i>
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
                        <button class="btn btn-sm btn-link text-primary toggle-reply-form" data-review-id="{{ $review->id }}">Reply</button>
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
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="review_box">
                     <h4>Add a Review</h4>
                     <form class="row contact_form" action="{{ route('review.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="col-md-12">
                           <div class="form-group">
                              <label>Your Rating:</label>
                              <div class="star-rating" data-rating="0">
                                 @for ($i = 1; $i <= 5; $i++)
                                 <i class="fa fa-star-o star" style="color:yellow;font-size:25px;" data-value="{{ $i }}"></i>
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
                           <button type="submit" class="btn submit_btn">Submit Now</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<!-- Dependencies -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easyzoom@2.6.0/css/easyzoom.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/easyzoom@2.6.0/dist/easyzoom.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function($) {
    $(document).ready(function () {
        // Debugging: Log jQuery version
        console.log('jQuery version:', $.fn.jquery);

        // Initialize EasyZoom
        let $easyzoom = null;
        try {
            if ($.fn.easyZoom) {
                $easyzoom = $('.easyzoom').easyZoom();
                console.log('EasyZoom initialized');
            } else {
                console.warn('EasyZoom plugin not loaded');
            }
        } catch (e) {
            console.error('EasyZoom initialization failed:', e);
        }

        // Disable zoom on mobile screens
        function toggleZoom() {
            try {
                if ($easyzoom && $easyzoom.data('easyZoom')) {
                    if (window.innerWidth < 768) {
                        $easyzoom.data('easyZoom').disable();
                    } else {
                        $easyzoom.data('easyZoom').enable();
                    }
                }
            } catch (e) {
                console.error('ToggleZoom failed:', e);
            }
        }
        toggleZoom();
        $(window).on('resize', toggleZoom);

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

        // Initial calculation
        updateTotalPrice();

        // Add to Cart and Buy Now
        $(document).on('click', '#addToCartBtn, .btn-buy-now', function(e) {
            e.preventDefault();
            var $btn = $(this); // Save reference to clicked button

            let selectedOptions = $('.option-input:checked').map(function() {
                return $(this).val();
            }).get();

            $.ajax({
                url: "{{ route('cart.add') }}",
                type: "POST",
                data: {
                    product_id: $btn.data('product'),
                    quantity: 1,
                    total_price: $('#product-total-price').text(),
                    options: selectedOptions,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if(res.status) {
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

        // Initialize reply form toggles
        function initializeReplyToggles() {
            $('.toggle-reply-form').off('click').on('click', function () {
                const reviewId = $(this).data('review-id');
                $('.reply-form-container').hide();
                $('#reply-form-' + reviewId).toggle();
            });
        }
        initializeReplyToggles();

        // Star rating functionality
        let currentRating = 0;

        $('.star-rating .star').on('mousemove', function (e) {
            const index = $(this).data('value');
            const offset = $(this).offset();
            const width = $(this).width();
            const relX = e.pageX - offset.left;
            const percent = relX / width;

            $('.star-rating .star').each(function () {
                const value = $(this).data('value');
                if (value < index) {
                    $(this).removeClass().addClass('fa fa-star star');
                } else if (value === index) {
                    if (percent < 0.5) {
                        $(this).removeClass().addClass('fa fa-star-half-o star');
                    } else {
                        $(this).removeClass().addClass('fa fa-star star');
                    }
                } else {
                    $(this).removeClass().addClass('fa fa-star-o star');
                }
            });
        });

        $('.star-rating .star').on('mouseleave', function () {
            updateStars(currentRating);
        });

        $('.star-rating .star').on('click', function (e) {
            const index = $(this).data('value');
            const offset = $(this).offset();
            const width = $(this).width();
            const relX = e.pageX - offset.left;
            const percent = relX / width;

            currentRating = percent < 0.5 ? index - 0.5 : index;
            $('#rating-value').val(currentRating);
            updateStars(currentRating);
        });

        function updateStars(rating) {
            $('.star-rating .star').each(function () {
                const value = $(this).data('value');
                if (value <= Math.floor(rating)) {
                    $(this).removeClass().addClass('fa fa-star star');
                } else if (value - 0.5 === rating) {
                    $(this).removeClass().addClass('fa fa-star-half-o star');
                } else {
                    $(this).removeClass().addClass('fa fa-star-o star');
                }
            });
        }

        // Review submission
        $('.review_box form').on('submit', function (e) {
            e.preventDefault();
            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                success: function(res) {
                    if (res.status && res.review) {
                        Swal.fire('Success', 'Review was added!', 'success');
                        $form[0].reset();
                        $('#rating-value').val(0);
                        currentRating = 0;
                        updateStars(0);

                        var review = res.review;

                        var reviewHtml = `
                            <div class="review_item">
                                <div class="media">
                                    <div class="d-flex">
                                        <img src="{{ asset('assets/img/user.jpg') }}" alt="" style="width: 50px;" />
                                    </div>
                                    <div class="media-body">
                                        <h4>${review.name ? review.name : 'Anonymous'}</h4>
                                        ${renderStars(review.rating || 0)}
                                    </div>
                                </div>
                                <p>${review.message ? review.message : ''}</p>
                                <button class="btn btn-sm btn-link text-primary toggle-reply-form" data-review-id="${review.id || ''}">Reply</button>
                                <div class="reply-form-container ml-4 mt-2" id="reply-form-${review.id || 'new'}" style="display: none;">
                                    <form action="{{ route('review.reply') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="review_id" value="${review.id || ''}">
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

                        $('.review_list').prepend(reviewHtml);

                        $.ajax({
                            url: "{{ route('product.reviews.summary', $product->id) }}",
                            method: "GET",
                            success: function(summary) {
                                $('.box_total h4').text(summary.average ? summary.average.toFixed(1) : '0.0');
                                $('.box_total h6').text(`(${summary.count || 0} Reviews)`);
                                $('.rating_list h3').text(`Based on ${summary.count || 0} Reviews`);

                                $('.rating_list ul.list').empty();
                                for (let i = 5; i >= 1; i--) {
                                    let count = summary.ratingBreakdown ? summary.ratingBreakdown[i] || 0 : 0;
                                    let starsHtml = '';
                                    for (let j = 0; j < 5; j++) {
                                        starsHtml += `<i class="fa fa-star${j < i ? '' : '-o'}"></i>`;
                                    }
                                    $('.rating_list ul.list').append(`
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
                            }
                        });

                        initializeReplyToggles();
                    } else {
                        Swal.fire('Error', res.message || 'Something went wrong.', 'error');
                    }
                },
                error: function(xhr) {
                    console.error('Review submission error:', xhr);
                    Swal.fire('Error', 'Something went wrong.', 'error');
                }
            });
        });

        // Reply submission
        $('.reply-form-container form').on('submit', function(e) {
            e.preventDefault();
            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serialize(),
                success: function(res) {
                    if (res.status && res.reply) {
                        Swal.fire('Success', 'Reply was added!', 'success');
                        $form[0].reset();

                        var newReply = `
                            <div class="ml-4 mt-2 bg-light p-2 rounded">
                                <img src="{{ asset('assets/img/user.jpg') }}" alt="" style="width: 50px;" />
                                <strong>${res.reply.name ? res.reply.name : 'Anonymous'}</strong>: ${res.reply.reply ? res.reply.reply : ''}
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
                    Swal.fire('Error', 'Validation failed.', 'error');
                }
            });
        });

        function renderStars(rating) {
            var html = '';
            var fullStars = Math.floor(rating);
            var halfStar = (rating - fullStars) >= 0.5 ? 1 : 0;

            for (var i = 0; i < fullStars; i++) html += '<i class="fa fa-star text-warning"></i>';
            if (halfStar) html += '<i class="fa fa-star-half-o text-warning"></i>';

            // No empty stars
            return html;
        }

        // Watching count
        function updateWatchingNumber() {
            $.ajax({
                url: "{{ url('/api/watching-count') }}",
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
    });
})(jQuery.noConflict());
</script>
<style>
.option-label input[type="radio"]:checked + span,
.option-label.active {
    background: #222;
    color: #fff;
    border-color: #222;
}
</style>
@endsection