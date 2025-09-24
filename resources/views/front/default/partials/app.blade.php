<!DOCTYPE html>
<html lang="en">
<head>
    

    <!-- Preload Critical Assets -->
    <link rel="preload" href="https://roshnipk.store/public/assets/img/logo.png" as="image" />
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin />
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin />

    <!-- External CSS (Consolidated and Optimized) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
 
    <!-- Check this partial for unclosed Blade directives -->
    @include('front.default.partials.css')
    
    <!-- Check this partial for unclosed Blade directives -->
    @include('front.default.partials.header')
</head>

<body>
    <!-- Loader -->
    <div id="loader">
        <div class="circle-loader">
            <div class="arc arc1"></div>
            <div class="arc arc2"></div>
            <div class="arc arc3"></div>
            <!-- Truck circling around -->
            <div class="truck-wrapper">
                <div class="truck">
                    <img src="https://roshnipk.store/public/assets/img/logo.png" alt="Roshni Store Loading" width="40" height="40">
                </div>
            </div>
        </div>
    </div>

    @yield('content')

    <!-- Conditional jQuery inclusion: This block is properly closed -->
    @if (!request()->routeIs('shop.index') && !request()->routeIs('category.products') && !request()->routeIs('checkout') && !request()->routeIs('subcategory.products'))
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @endif

    <!-- Check these partials for unclosed Blade directives -->
    @include('front.default.partials.footer')
    @include('front.default.partials.js')
    @include('front.default.whatsapp')
    @include('front.default.flow')
    
  
    
    <!-- Dark Mode Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function setDarkMode(isEnabled) {
                if (isEnabled) {
                    document.body.classList.add('dark-mode');
                    if (document.getElementById('toggleDarkMode')) {
                        document.getElementById('toggleDarkMode').checked = true;
                    }
                    if (document.getElementById('toggleDarkMode1')) {
                        document.getElementById('toggleDarkMode1').checked = true;
                    }
                    localStorage.setItem('darkMode', 'enabled');
                } else {
                    document.body.classList.remove('dark-mode');
                    if (document.getElementById('toggleDarkMode')) {
                        document.getElementById('toggleDarkMode').checked = false;
                    }
                    if (document.getElementById('toggleDarkMode1')) {
                        document.getElementById('toggleDarkMode1').checked = false;
                    }
                    localStorage.setItem('darkMode', 'disabled');
                }
            }
            if (localStorage.getItem('darkMode') === 'enabled') {
                setDarkMode(true);
            }
            const darkModeToggles = document.querySelectorAll('#toggleDarkMode, #toggleDarkMode1');
            darkModeToggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    setDarkMode(this.checked);
                });
            });
        });
    </script>
</body>
</html>