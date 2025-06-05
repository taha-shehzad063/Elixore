@extends('front.default.partials.app')
@section('content')
<section class="banner_area">
   <div class="banner_inner d-flex align-items-center">
      <div class="container">
         <div
            class="banner_content d-md-flex justify-content-between align-items-center"
            >
            <div class="mb-3 mb-md-0">
               <h2>Blog Details</h2>
               <p>Very us move be blessed multiply night</p>
            </div>
            <div class="page_link">
               <a href="index.html">Home</a>
               <a href="blog.html">Blog </a>
               <a href="single-blog.html">Blog Details</a>
            </div>
         </div>
      </div>
   </div>
</section>
<section class="blog_area single-post-area section_gap">
   <div class="container">
      <div class="row">
         <div class="col-lg-8 posts-list">
            <div class="single-post">
               <div class="feature-img">
         <img class="img-fluid" src="{{ asset('storage/' . $blogs->image) }}" alt="{{ $blogs->name }}">
               </div>
            <div class="blog_details">
         <h2>{{ $blogs->name }}</h2>
         <ul class="blog-info-link mt-3 mb-4">
            <li><a href="#"><i class="ti-user"></i>  {{ $blogs->tags->pluck('name')->implode(', ') ?: 'Uncategorized' }}</a></li>
            <li><a href="#"><i class="ti-comments"></i>   {{ $blogs->comments_count ?? '0' }} Comments</a></li>
         </ul>
         <p class="excert">
                        {{ $blogs->description }}

         </p>
      </div>
            </div>
            <div class="navigation-top">
               <div class="d-sm-flex justify-content-between text-center">
                  <p class="like-info"><span class="align-middle"><i class="ti-heart"></i></span> Lily and 4 people like this</p>
                  <div class="col-sm-4 text-center my-2 my-sm-0">
            <p class="comment-count"><span class="align-middle"><i class="ti-comment"></i></span>   {{ $blogs->comments_count ?? '0' }} Comments</p>
                  </div>
                  <ul class="social-icons">
                     <li><a href="#"><i class="ti-facebook"></i></a></li>
                     <li><a href="#"><i class="ti-twitter-alt"></i></a></li>
                     <li><a href="#"><i class="ti-dribbble"></i></a></li>
                     <li><a href="#"><i class="ti-wordpress"></i></a></li>
                  </ul>
               </div>
             
            </div>
           <div class="blog-author">
      <div class="media align-items-center">
         <img src="{{ asset('user/images.png') }}" alt="Author">
         <div class="media-body">
            <a href="#">
               <h4>Harvard milan</h4>
            </a>
            <p>Second divided from form fish beast made. Every of seas all gathered use saying you're, he our dominion twon Second divided from</p>
         </div>
      </div>
   </div>
             <div class="comments-area">
      <h4>{{ $comments->count() }} Comments</h4>
      @foreach($comments as $comment)
      <div class="comment-list">
         <div class="single-comment justify-content-between d-flex">
            <div class="user justify-content-between d-flex">
               <div class="thumb">
                  <img src="{{ asset('user/images.png') }}" alt="">
               </div>
               <div class="desc">
                  <p class="comment">{{ $comment->comment }}</p>
                  <div class="d-flex justify-content-between">
                     <div class="d-flex align-items-center">
                        <h5><a href="#">{{ $comment->author_name }}</a></h5>
                        <p class="date">{{ $comment->created_at->diffForHumans() }}</p>
                     </div>
                     <div class="reply-btn">
                        <a href="#" class="btn-reply text-uppercase" onclick="reply({{ $comment->id }}, '{{ $comment->author_name }}')">Reply</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         {{-- Replies --}}
         @foreach($comment->replies as $reply)
         <div class="comment-list ml-4">
            <div class="single-comment d-flex">
               <div class="thumb">
                  <img src="{{ asset('user/images.png') }}" alt="">
               </div>
               <div class="desc">
                  <p class="comment">{{ $reply->comment }}</p>
                  <div class="d-flex justify-content-between">
                     <div class="d-flex align-items-center">
                        <h5><a href="#">{{ $reply->author_name }}</a></h5>
                        <p class="date">{{ $reply->created_at->diffForHumans() }}</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         @endforeach
      </div>
      @endforeach
   </div>
           <div class="comment-form mt-5">
            <h4>Leave a Reply</h4>
            <form class="form-contact comment_form" action="{{ route('blog.comment') }}" method="POST">
               @csrf
               <input type="hidden" name="blog_id" value="{{ $blogs->id }}">
               <input type="hidden" name="parent_id" id="parent_id" value="">
               <div class="row">
                  <div class="col-12">
                     <div class="form-group">
                        <textarea class="form-control w-100" name="comment" cols="30" rows="9" placeholder="Write Comment" required></textarea>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <input class="form-control" name="name" type="text" placeholder="Name" required>
                     </div>
                  </div>
                  <div class="col-sm-6">
                     <div class="form-group">
                        <input class="form-control" name="email" type="email" placeholder="Email">
                     </div>
                  </div>
                  <div class="col-12">
                     <div class="form-group">
                        <input class="form-control" name="website" type="text" placeholder="Website">
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <button type="submit" class="main_btn">Send Message</button>
               </div>
            </form>
         </div>
         </div>
         <div class="col-lg-4">
            <div class="blog_right_sidebar">
               <aside class="single_sidebar_widget search_widget">
                  <form action="#">
                     <div class="form-group">
                        <div class="input-group mb-3">
                           <input type="text" class="form-control" placeholder="Search Keyword">
                           <div class="input-group-append">
                              <button class="btn" type="button"><i class="ti-search"></i></button>
                           </div>
                        </div>
                     </div>
                     <button class="main_btn rounded-0 w-100" type="submit">Search</button>
                  </form>
               </aside>
               <aside class="single_sidebar_widget post_category_widget">
                  <h4 class="widget_title">Category</h4>
                  <ul class="list cat-list">
                     <li>
                        <a href="#" class="d-flex">
                           <p>Resaurant food</p>
                           <p>(37)</p>
                        </a>
                     </li>
                     <li>
                        <a href="#" class="d-flex">
                           <p>Travel news</p>
                           <p>(10)</p>
                        </a>
                     </li>
                     <li>
                        <a href="#" class="d-flex">
                           <p>Modern technology</p>
                           <p>(03)</p>
                        </a>
                     </li>
                     <li>
                        <a href="#" class="d-flex">
                           <p>Product</p>
                           <p>(11)</p>
                        </a>
                     </li>
                     <li>
                        <a href="#" class="d-flex">
                           <p>Inspiration</p>
                           <p>(21)</p>
                        </a>
                     </li>
                     <li>
                        <a href="#" class="d-flex">
                           <p>Health Care</p>
                           <p>(21)</p>
                        </a>
                     </li>
                  </ul>
               </aside>
             <aside class="single_sidebar_widget popular_post_widget">
               <h3 class="widget_title">Recent Post</h3>
               @foreach($latestBlogs as $blog)
               <div class="media post_item">
                  <img src="{{ asset('storage/' . $blog->image) }}" alt="post" style="width: 80px; height: 60px; object-fit: cover;">
                  <div class="media-body">
                     <a href="{{ route('blogs.details', $blog->slug) }}">
                        <h3>{{ Str::limit($blog->name, 35) }}</h3>
                     </a>
                     <p>
                        @if (\Carbon\Carbon::parse($blog->created_at)->gt(\Carbon\Carbon::now()->subMonth()))
                        {{ \Carbon\Carbon::parse($blog->created_at)->diffForHumans() }}
                        @else
                        {{ \Carbon\Carbon::parse($blog->created_at)->format('F d, Y') }}
                        @endif
                     </p>
                  </div>
               </div>
               @endforeach
            </aside>
               <aside class="single_sidebar_widget tag_cloud_widget">
            <h4 class="widget_title">Tag Clouds</h4>
            <ul class="list">
               @foreach($tags as $tag)
               <li>
                  <a href="#">{{ $tag->name }}</a>
               </li>
               @endforeach
            </ul>
         </aside>
              
            </div>
         </div>
      </div>
   </div>
</section>
@endsection
<script>
   function reply(id, name) {
       document.getElementById('parent_id').value = id;
       window.scrollTo({
           top: document.querySelector('.comment-form').offsetTop,
           behavior: 'smooth'
       });
   }
</script>