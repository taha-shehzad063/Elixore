@extends('front.default.partials.app')
@section('content')
<section class="banner_area">
      <div class="banner_inner d-flex align-items-center">
        <div class="container">
          <div
            class="banner_content d-md-flex justify-content-between align-items-center"
          >
            <div class="mb-3 mb-md-0">
              <h2>Blog</h2>
              <p>Very us move be blessed multiply night</p>
            </div>
            <div class="page_link">
              <a href="index.html">Home</a>
              <a href="blog.html">Blog </a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--================End Home Banner Area =================-->

  <!--================Blog Area =================-->
  <section class="blog_area section_gap">
      <div class="container">
          <div class="row">
              <div class="col-lg-8 mb-5 mb-lg-0">
                  <div class="blog_left_sidebar">
                    
                      
                    @foreach ($blogs as $blog)
<article class="blog_item">
    <div class="blog_item_img">
        <img class="card-img rounded-0" src="{{ asset('storage/' . $blog->image) }}" alt="{{ $blog->title }}">
        <a href="#" class="blog_item_date">
            <h3>{{ $blog->created_at->format('d') }}</h3>
            <p>{{ $blog->created_at->format('M') }}</p>
        </a>
    </div>
    
    <div class="blog_details">
        <a class="d-inline-block" href="{{ route('blogs.details', $blog->slug) }}">
            <h2>{{ $blog->name }}</h2>
        </a>
        <p>{{ Str::limit($blog->description, 120) }}</p>
        <ul class="blog-info-link">
            <li>
                <a href="#">
                    <i class="ti-user"></i>
                    {{ $blog->tags->pluck('name')->implode(', ') ?: 'Uncategorized' }}
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="ti-comments"></i>
                    {{ $blog->comments_count ?? '0' }} Comments
                </a>
            </li>
        </ul>
    </div>
</article>
@endforeach

                      


                      <nav class="blog-pagination justify-content-center d-flex">
                          <ul class="pagination">
                              <li class="page-item">
                                  <a href="#" class="page-link" aria-label="Previous">
                                      <span aria-hidden="true">
                                          <span class="ti-arrow-left"></span>
                                      </span>
                                  </a>
                              </li>
                              <li class="page-item">
                                  <a href="#" class="page-link">1</a>
                              </li>
                              <li class="page-item active">
                                  <a href="#" class="page-link">2</a>
                              </li>
                              <li class="page-item">
                                  <a href="#" class="page-link" aria-label="Next">
                                      <span aria-hidden="true">
                                          <span class="ti-arrow-right"></span>
                                      </span>
                                  </a>
                              </li>
                          </ul>
                      </nav>
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
                                    <p>21</p>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="d-flex">
                                    <p>Health Care (21)</p>
                                    <p>09</p>
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


                      <aside class="single_sidebar_widget instagram_feeds">
                        <h4 class="widget_title">Instagram Feeds</h4>
                        <ul class="instagram_row flex-wrap">
                            <li>
                                <a href="#">
                                  <img class="img-fluid" src="{{ asset('assets/img/instagram/widget-i1.png') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                  <img class="img-fluid" src="{{ asset('assets/img/instagram/widget-i2.png') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                  <img class="img-fluid" src="{{ asset('assets/img/instagram/widget-i3.png') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                  <img class="img-fluid" src="{{ asset('assets/img/instagram/widget-i4.png') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                  <img class="img-fluid" src="{{ asset('assets/img/instagram/widget-i5.png') }}" alt="">
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                  <img class="img-fluid" src="{{ asset('assets/img/instagram/widget-i6.png') }}" alt="">
                                </a>
                            </li>
                        </ul>
                      </aside>


                      <aside class="single_sidebar_widget newsletter_widget">
                        <h4 class="widget_title">Newsletter</h4>

                        <form action="#">
                          <div class="form-group">
                            <input type="email" class="form-control" placeholder="Enter email" required>
                          </div>
                          <button class="main_btn rounded-0 w-100" type="submit">Subscribe</button>
                        </form>
                      </aside>
                  </div>
              </div>
          </div>
      </div>
  </section>
  @endsection