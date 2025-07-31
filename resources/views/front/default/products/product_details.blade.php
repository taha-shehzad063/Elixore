@extends('front.default.partials.app')
@section('content')
<div class="product_image_area">
   <div class="container">
      <div class="row s_product_inner">
         <div class="col-lg-6">
            <div class="s_product_img">
               <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                  <!-- Indicators -->
                  <ol class="carousel-indicators">
                     @foreach($product->galleries as $index => $gallery)
                     <li data-target="#carouselExampleIndicators" data-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $gallery->image) }}" alt="" style="width: 60px; height: 60px; object-fit: cover;">
                     </li>
                     @endforeach
                  </ol>
                  <!-- Slides -->
                  <div class="carousel-inner">
                     @foreach($product->galleries as $index => $gallery)
                     <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <img class="d-block w-100" src="{{ asset('storage/' . $gallery->image) }}" alt="Slide {{ $index + 1 }}">
                     </div>
                     @endforeach
                  </div>
                  <!-- Controls (optional) -->
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
               <h2>{{$product->price}} </h2>
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
               <p>
                  {{$product->info}}
               </p>
               <div class="card_area">
                  <a class="main_btn" href="#" id="addToCartBtn" data-product="{{ $product->id }}">Add to Cart</a>
                  <a class="icon_btn" href="#">
                  <i class="lnr lnr lnr-diamond"></i>
                  </a>
                  <a class="icon_btn" href="#">
                  <i class="lnr lnr lnr-heart"></i>
                  </a>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--================End Single Product Area =================-->
<!--================Product Description Area =================-->
<section class="product_description_area">
   <div class="container">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
         <li class="nav-item">
            <a
               class="nav-link"
               id="home-tab"
               data-toggle="tab"
               href="#home"
               role="tab"
               aria-controls="home"
               aria-selected="true"
               >Description</a
               >
         </li>
         @if($product->specifications && $product->specifications->count())
         <li class="nav-item">
            <a
               class="nav-link"
               id="profile-tab"
               data-toggle="tab"
               href="#profile"
               role="tab"
               aria-controls="profile"
               aria-selected="false"
               >Specification</a>
         </li>
         @endif
         <li class="nav-item">
            <a
               class="nav-link active"
               id="review-tab"
               data-toggle="tab"
               href="#review"
               role="tab"
               aria-controls="review"
               aria-selected="false"
               >Reviews</a
               >
         </li>
      </ul>
      <div class="tab-content" id="myTabContent">
         <div
            class="tab-pane fade"
            id="home"
            role="tabpanel"
            aria-labelledby="home-tab"
            >
            <p>
               {{$product->description}}
            </p>
         </div>
         <div
            class="tab-pane fade"
            id="profile"
            role="tabpanel"
            aria-labelledby="profile-tab"
            >
            <div class="table-responsive">
               <table class="table">
                  <tbody>
                     @if($product->specifications && $product->specifications->count())
                     @foreach($product->specifications as $spec)
                     <tr>
                        <td>
                           <h5>{{ $spec->key }}</h5>
                        </td>
                        <td>
                           <h5>{{ $spec->value }}</h5>
                        </td>
                     </tr>
                     @endforeach
                     @else
                     <tr>
                        <td colspan="2">
                           <h5>No specifications found.</h5>
                        </td>
                     </tr>
                     @endif
                  </tbody>
               </table>
            </div>
         </div>
         <div
            class="tab-pane fade show active"
            id="review"
            role="tabpanel"
            aria-labelledby="review-tab"
            >
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
                              $fullStars = floor($review->rating);      // full stars
                              $halfStar = ($review->rating - $fullStars) >= 0.5 ? 1 : 0; // half star if >= .5
                              $emptyStars = 5 - $fullStars - $halfStar; // remaining empty
                              @endphp
                              {{-- Full Stars --}}
                              @for ($i = 0; $i < $fullStars; $i++)
                              <i class="fa fa-star text-warning"></i>
                              @endfor
                              {{-- Half Star --}}
                              @if ($halfStar)
                              <i class="fa fa-star-half-o text-warning"></i>
                              @endif
                              {{-- Empty Stars --}}
                              @for ($i = 0; $i < $emptyStars; $i++)
                              <i class="fa fa-star-o text-warning"></i>
                              @endfor
                           </div>
                        </div>
                        <p>{{ $review->message }}</p>
                        {{-- Replies --}}
                        @foreach ($review->replies as $reply)
                        <div class="ml-4 mt-2 bg-light p-2 rounded">
                           <img src="{{ asset('assets//img/user.jpg') }}" alt="" style="width: 50px;" />
                           <strong>{{ $reply->name }}</strong>: {{ $reply->reply }}
                        </div>
                        @endforeach
                        {{-- Reply Button --}}
                        <button class="btn btn-sm btn-link text-primary toggle-reply-form" data-review-id="{{ $review->id }}">Reply</button>
                        {{-- Reply Form (Hidden by default) --}}
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
               {{-- Review Submission Form --}}
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
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(document).ready(function () {
      $('.toggle-reply-form').on('click', function () {
         const reviewId = $(this).data('review-id');
         $('.reply-form-container').hide(); // Hide all reply forms
         $('#reply-form-' + reviewId).toggle(); // Toggle only clicked one
      });
   });
</script>
<script>
   $(document).ready(function () {
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
   });
</script>
<script>
$(document).ready(function(){
    $('#addToCartBtn').on('click', function(e){
        e.preventDefault();
        $.ajax({
            url: "{{ route('cart.add') }}",
            type: "POST",
            data: {
                product_id: $(this).data('product'),
                quantity: 1,
                _token: "{{ csrf_token() }}"
            },
            success: function(res){
                if(res.status){
                    alert(res.message); // Replace with better UI if needed
                    window.location.href = "{{ route('cart') }}";
                }
            }
        });
    });
});
</script>