<!DOCTYPE html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">

@include('front.default.partials.css')
@include('front.default.partials.header')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Bootstrap 5 (if not already included) -->

<body>
    <!-- Loader -->
  <div id="loader">
  <div class="circle-loader">
    <div class="arc arc1"></div>
    <div class="arc arc2"></div>
    <div class="arc arc3"></div>
    <div class="arc arc4"></div>

    <!-- Truck circling around -->
    <div class="truck-wrapper">
      <div class="truck">
        <img src="https://img.freepik.com/premium-vector/red-cargo-truck-with-container-vector-illustration-design_892631-5169.jpg" alt="truck">
      </div>
    </div>
  </div>
</div>

    @yield('content')

    <!-- Page Content -->
</body>

@include('front.default.partials.footer')
@include('front.default.partials.js')


</html>