<!--================ start footer Area  =================-->
<style>
  @media (max-width: 380px) {
  .foote {
    bottom: 0px !important;
  }
}
/* Default footer link */
.footer-link {
    color: #ccc;
    text-decoration: none;
    transition: color 0.3s ease;
}

/* Hover effect */
.footer-link:hover {
    color: #71cd14 !important;
}

/* Active link */
.footer-link.active {
    color: #71cd14 !important;
    font-weight: bold;
}

</style>
<footer class="footer-area pt-5 pb-3" style="background: #111; color: #fff;">
  <div class="container">
    <div class="row">

      <!-- Logo and Contact Info -->
<div class="col-lg-3 col-sm-6 col-12 mb-4">
        <div class="footer-logo mb-3">
          <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="height: 60px;">
        </div>
        <p>Pakistan’s Premier Fashion & Lifestyle Hub – Bringing Light, Style, and Elegance to Your Everyday.</p>
        <p><i class="fa fa-phone mr-2"></i> +92 327 3546 6753</p>
        <p><i class="fa fa-envelope mr-2"></i> info.roshni.store@gmail.com</p>
      </div>

<!-- Shop By (First 5 Categories) -->
<div class="col-lg-2 col-md-6 col-6 mb-4">
    <h5 class="text-light">Shop by</h5>
    <ul class="list-unstyled">
        @foreach(App\Models\Category::take(5)->get() as $category)
            @php
                $slug = Str::slug($category->name); // Converts "Men Shoes" to "men-shoes"
            @endphp
            <li>
                <a href="{{ route('category.products', ['name' => $slug]) }}" 
                   class="footer-link {{ request()->is('category/'.$slug) ? 'active' : '' }}">
                    {{ $category->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>

@php
    use App\Models\Category;
 

    $categories = Category::with('subCategories')->get();
  
@endphp
<!-- Links -->
<div class="col-lg-2 col-md-6 col-6 mb-4">
    <h5 class="text-light">Links</h5>
    <ul class="list-unstyled">
        <li><a href="{{ route('main') }}" class="footer-link {{ request()->routeIs('main') ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ route('shop.index') }}" class="footer-link {{ request()->routeIs('shop.index') ? 'active' : '' }}">Shop</a></li>
        @foreach($categories as $category)
    @php
        $urlName = str_replace(' ', '-', strtolower($category->name)); 
    @endphp
    <li>
        <a href="{{ route('category.products', ['name' => $urlName]) }}" 
           class="footer-link {{ request()->is('category/'.$urlName) ? 'active' : '' }}">
            {{ $category->name }}
        </a>
    </li>
@endforeach

        <li><a href="{{ route('contact') }}" class="footer-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
    </ul>
</div>

@php
    use App\Models\Policy;
    $footer_policies = Policy::select('title', 'slug')->get();
@endphp

<!-- Support -->
<div class="col-lg-2 col-md-6 mb-4 col-6">
    <h5 class="text-light">Support</h5>
    <ul class="list-unstyled">
        @foreach($footer_policies as $policy)
            <li>
<a href="{{ route('policy.show', $policy->slug) }}"
   class="footer-link {{ request()->is('policies/'.$policy->slug) ? 'active' : '' }}">
    {{ $policy->title }}
</a>

            </li>
        @endforeach
    </ul>
</div>



      <!-- Newsletter & Social -->
      <div class="col-lg-3 col-md-6 mb-4 col-12">
<h5 class="text-light">Follow Us</h5>
        <div class="mb-3">
          <a href="#" class="mr-2"><i class="fab fa-facebook text-white"></i></a>
          <a href="#" class="mr-2"><i class="fab fa-instagram text-white"></i></a>
          <a href="#" class="mr-2"><i class="fab fa-tiktok text-white"></i></a>
          <a href="#" class="mr-2"><i class="fab fa-youtube text-white"></i></a>
        </div>
        <h6 class="text-light">Receive our latest updates about our products & deals.</h6>
        <form id="newsletterForm" action="{{ route('newsletter.subscribe') }}" method="POST" class="form-inline">
          @csrf
          <div class="input-group">
            <input type="email" name="email" class="form-control" placeholder="Enter your email..." required>
            <div class="input-group-append">
              <button class="btn btn-warning" type="submit">SUBSCRIBE</button>
            </div>
          </div>
        </form>
      </div>

    </div>

    <!-- Bottom footer -->
    <div class="row pt-4 border-top border-secondary mt-4 ">
      <div class="col-md-8">
        <p class="mb-0 text-light">&copy; {{ date('Y') }} Roshni Pk. All Rights Reserved</p>
      </div>
      <div class="col-md-4 text-right ">
        <img class="foote mt-3 mt-md-0" style="height: 56px; width: 250px;    bottom: 10px;
    position: relative;" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRtroC78ml1CoAkcniu2KDlpXVAojoYpYZTgA&s" alt="Payments" style="height: 32px;">
      </div>
    </div>
  </div>
</footer>
<!--================ End footer Area  =================-->

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(function() {
    $('#newsletterForm').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var formData = $form.serialize();

        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: formData,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Subscribed!',
                    text: res.message || 'Thank you for subscribing!',
                    confirmButtonColor: '#71cd14'
                });
                $form[0].reset();
            },
            error: function(xhr) {
                let msg = 'Something went wrong. Please try again.';
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).map(function(e){
                        return Array.isArray(e) ? e.join('<br>') : e;
                    }).join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: msg,
                    confirmButtonColor: '#71cd14'
                });
            }
        });
    });
});
</script>
