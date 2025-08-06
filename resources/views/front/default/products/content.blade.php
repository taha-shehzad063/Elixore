
<link rel="stylesheet" href="{{ asset('assets/css/shop.css') }}" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="container">
            <div class="banner_content d-md-flex justify-content-between align-items-center">
                <div class="mb-3 mb-md-0">
                    <h2>Shop Category</h2>
                    <p>Very us move be blessed multiply night</p>
                </div>
                <div class="page_link">
                    <a href="{{ route('main') }}">Home</a>
                    <a href="{{ route('shop.index') }}">Shop</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="cat_product_area section_gap">
    <div class="container-fluid">
        <div class="row flex-row-reverse">
            <div class="col-lg-9">
                <div class="product_top_bar">
                    <div class="left_dorp">
                        <select class="sorting" id="per_page">
                            <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>Show 12</option>
                            <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>Show 24</option>
                            <option value="36" {{ request('per_page') == 36 ? 'selected' : '' }}>Show 36</option>
                        </select>
                    </div>
                </div>
                <button id="open-filter-sidebar" class="btn btn-success d-lg-none mb-3" style="background:#71cd14;">
                    <i class="ti-filter"></i> Filters
                </button>

                <div class="d-none d-md-flex align-items-center mb-3">
                    <span class="me-2">VIEW AS</span>
                    <button type="button" class="btn btn-outline-secondary btn-sm view-mode-btn {{ request('grid_mode') == 'list' ? 'active' : '' }}" data-cols="list">
                        <img src="https://thumbs.dreamstime.com/b/hamburger-menu-icon-selection-three-lines-option-website-navigation-navigate-open-list-black-white-symbol-sign-graphic-361715125.jpg" alt="List" style="height:24px;">
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm view-mode-btn {{ request('grid_mode') == '6' ? 'active' : '' }}" data-cols="6">
                        <img src="https://www.pngkey.com/png/detail/116-1160644_two-vertical-parallel-lines-vector-lineas-paralelas-verticales.png" alt="2" style="height:24px;">
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm view-mode-btn {{ request('grid_mode') == '4' ? 'active' : '' }}" data-cols="4">
                        <img src="https://static.thenounproject.com/png/3120978-200.png" alt="3" style="height:24px;">
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm view-mode-btn {{ request('grid_mode') == '3' ? 'active' : '' }}" data-cols="3">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQaay22MbmODe7RG8AduTTMHvNf6nd4R93LDQ&s" alt="6" style="height:24px;">
                    </button>
                </div>

                <div class="latest_product_inner position-relative" id="product-list">
                    <div class="loading-overlay" id="loading-overlay">
                        <div class="loading-bar"></div>
                    </div>
                    @include('front.default.products.products')
                </div>
            </div>

            <div class="col-lg-3 d-none d-md-flex">
                <div class="left_sidebar_area">
                    @if (!Route::is('category.products'))
                    <aside class="left_widgets p_filter_widgets">
                        <div class="l_w_title">
                            <h3>Browse Categories</h3>
                        </div>
                        <div class="widgets_inner">
                            <ul class="list" id="category-list">
                                <li>
                                    <label class="filter-category-label {{ !request('category') && !isset($selectedCategoryId) ? 'active' : '' }}">
                                        <input type="radio" name="category" class="filter-category" value="" {{ !request('category') && !isset($selectedCategoryId) ? 'checked' : '' }}>
                                        All Categories
                                    </label>
                                </li>
                                @foreach($categories as $category)
                                    <li>
                                        <label class="filter-category-label {{ (request('category') == $category->id || (isset($selectedCategoryId) && $selectedCategoryId == $category->id)) ? 'active' : '' }}">
                                            <input type="radio" name="category" class="filter-category" value="{{ $category->id }}" {{ (request('category') == $category->id || (isset($selectedCategoryId) && $selectedCategoryId == $category->id)) ? 'checked' : '' }}>
                                            {{ $category->name }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </aside>
                    @endif

                    <aside class="left_widgets p_filter_widgets">
                        <div class="l_w_title">
                            <h3>Sort By</h3>
                        </div>
                        <div class="widgets_inner">
                            <ul class="list" id="sort-list">
                                <li>
                                    <label class="filter-sort-label {{ !request('sort_by') ? 'active' : '' }}">
                                        <input type="radio" name="sort_by" class="filter-sort" value="" {{ !request('sort_by') ? 'checked' : '' }}>
                                        Default
                                    </label>
                                </li>
                                <li>
                                    <label class="filter-sort-label {{ request('sort_by') == 'price_asc' ? 'active' : '' }}">
                                        <input type="radio" name="sort_by" class="filter-sort" value="price_asc" {{ request('sort_by') == 'price_asc' ? 'checked' : '' }}>
                                        Price: Low to High
                                    </label>
                                </li>
                                <li>
                                    <label class="filter-sort-label {{ request('sort_by') == 'price_desc' ? 'active' : '' }}">
                                        <input type="radio" name="sort_by" class="filter-sort" value="price_desc" {{ request('sort_by') == 'price_desc' ? 'checked' : '' }}>
                                        Price: High to Low
                                    </label>
                                </li>
                                <li>
                                    <label class="filter-sort-label {{ request('sort_by') == 'best_selling' ? 'active' : '' }}">
                                        <input type="radio" name="sort_by" class="filter-sort" value="best_selling" {{ request('sort_by') == 'best_selling' ? 'checked' : '' }}>
                                        Best Selling
                                    </label>
                                </li>
                                <li>
                                    <label class="filter-sort-label {{ request('sort_by') == 'a_z' ? 'active' : '' }}">
                                        <input type="radio" name="sort_by" class="filter-sort" value="a_z" {{ request('sort_by') == 'a_z' ? 'checked' : '' }}>
                                        Name: A-Z
                                    </label>
                                </li>
                                <li>
                                    <label class="filter-sort-label {{ request('sort_by') == 'z_a' ? 'active' : '' }}">
                                        <input type="radio" name="sort_by" class="filter-sort" value="z_a" {{ request('sort_by') == 'z_a' ? 'checked' : '' }}>
                                        Name: Z-A
                                    </label>
                                </li>
                                <li>
                                    <label class="filter-sort-label {{ request('sort_by') == 'old_to_new' ? 'active' : '' }}">
                                        <input type="radio" name="sort_by" class="filter-sort" value="old_to_new" {{ request('sort_by') == 'old_to_new' ? 'checked' : '' }}>
                                        Old to New
                                    </label>
                                </li>
                                <li>
                                    <label class="filter-sort-label {{ request('sort_by') == 'new_to_old' ? 'active' : '' }}">
                                        <input type="radio" name="sort_by" class="filter-sort" value="new_to_old" {{ request('sort_by') == 'new_to_old' ? 'checked' : '' }}>
                                        New to Old
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </aside>

                    <aside class="left_widgets p_filter_widgets">
                        <div class="l_w_title">
                            <h3>Product Tags</h3>
                        </div>
                        <div class="widgets_inner">
                            <ul class="list" id="tags-list">
                                @include('front.default.products.partials.tags_list')
                            </ul>
                        </div>
                    </aside>

                    <aside class="left_widgets p_filter_widgets">
                        <div class="l_w_title">
                            <h3>Filter by Price</h3>
                        </div>
                        <div class="widgets_inner">
                            <div id="price-range"></div>
                            <div id="price-range-label"></div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="mobile-filter-sidebar" class="mobile-filter-sidebar">
    <div class="sidebar-header d-flex justify-content-between align-items-center px-3 py-2" style="background:#71cd14;">
        <span class="text-white fw-bold">Filters</span>
        <button id="close-filter-sidebar" class="btn btn-sm btn-light">&times;</button>
    </div>
    <div class="sidebar-body p-3">
        @if (!Route::is('category.products'))
        <aside class="left_widgets p_filter_widgets mb-4">
            <div class="l_w_title"><h3>Browse Categories</h3></div>
            <div class="widgets_inner">
                <ul class="list" id="category-list-mobile">
                    <li>
                        <label class="filter-category-label {{ !request('category') && !isset($selectedCategoryId) ? 'active' : '' }}">
                            <input type="radio" name="category" class="filter-category" value="" {{ !request('category') && !isset($selectedCategoryId) ? 'checked' : '' }}>
                            All Categories
                        </label>
                    </li>
                    @foreach($categories as $category)
                        <li>
                            <label class="filter-category-label {{ (request('category') == $category->id || (isset($selectedCategoryId) && $selectedCategoryId == $category->id)) ? 'active' : '' }}">
                                <input type="radio" name="category" class="filter-category" value="{{ $category->id }}" {{ (request('category') == $category->id || (isset($selectedCategoryId) && $selectedCategoryId == $category->id)) ? 'checked' : '' }}>
                                {{ $category->name }}
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>
        @endif
        <aside class="left_widgets p_filter_widgets mb-4">
            <div class="l_w_title"><h3>Sort By</h3></div>
            <div class="widgets_inner">
                <ul class="list" id="sort-list-mobile">
                    <li>
                        <label class="filter-sort-label {{ !request('sort_by') ? 'active' : '' }}">
                            <input type="radio" name="sort_by" class="filter-sort" value="" {{ !request('sort_by') ? 'checked' : '' }}>
                            Default
                        </label>
                    </li>
                    <li>
                        <label class="filter-sort-label {{ request('sort_by') == 'price_asc' ? 'active' : '' }}">
                            <input type="radio" name="sort_by" class="filter-sort" value="price_asc" {{ request('sort_by') == 'price_asc' ? 'checked' : '' }}>
                            Price: Low to High
                        </label>
                    </li>
                    <li>
                        <label class="filter-sort-label {{ request('sort_by') == 'price_desc' ? 'active' : '' }}">
                            <input type="radio" name="sort_by" class="filter-sort" value="price_desc" {{ request('sort_by') == 'price_desc' ? 'checked' : '' }}>
                            Price: High to Low
                        </label>
                    </li>
                    <li>
                        <label class="filter-sort-label {{ request('sort_by') == 'best_selling' ? 'active' : '' }}">
                            <input type="radio" name="sort_by" class="filter-sort" value="best_selling" {{ request('sort_by') == 'best_selling' ? 'checked' : '' }}>
                            Best Selling
                        </label>
                    </li>
                    <li>
                        <label class="filter-sort-label {{ request('sort_by') == 'a_z' ? 'active' : '' }}">
                            <input type="radio" name="sort_by" class="filter-sort" value="a_z" {{ request('sort_by') == 'a_z' ? 'checked' : '' }}>
                            Name: A-Z
                        </label>
                    </li>
                    <li>
                        <label class="filter-sort-label {{ request('sort_by') == 'z_a' ? 'active' : '' }}">
                            <input type="radio" name="sort_by" class="filter-sort" value="z_a" {{ request('sort_by') == 'z_a' ? 'checked' : '' }}>
                            Name: Z-A
                        </label>
                    </li>
                    <li>
                        <label class="filter-sort-label {{ request('sort_by') == 'old_to_new' ? 'active' : '' }}">
                            <input type="radio" name="sort_by" class="filter-sort" value="old_to_new" {{ request('sort_by') == 'old_to_new' ? 'checked' : '' }}>
                            Old to New
                        </label>
                    </li>
                    <li>
                        <label class="filter-sort-label {{ request('sort_by') == 'new_to_old' ? 'active' : '' }}">
                            <input type="radio" name="sort_by" class="filter-sort" value="new_to_old" {{ request('sort_by') == 'new_to_old' ? 'checked' : '' }}>
                            New to Old
                        </label>
                    </li>
                </ul>
            </div>
        </aside>

        <aside class="left_widgets p_filter_widgets mb-4">
            <div class="l_w_title"><h3>Product Tags</h3></div>
            <div class="widgets_inner">
                <ul class="list" id="tags-list-mobile">
                    @include('front.default.products.partials.tags_list')
                </ul>
            </div>
        </aside>

        <aside class="left_widgets p_filter_widgets mb-4">
            <div class="l_w_title"><h3>Filter by Price</h3></div>
            <div class="widgets_inner">
                <form id="mobile-price-filter-form">
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="number" class="form-control filter-price-min" name="min_price" placeholder="Min" min="{{ $minPrice }}" max="{{ $maxPrice }}" value="{{ request('min_price', $minPrice) }}">
                        </div>
                        <div class="col-6">
                            <input type="number" class="form-control filter-price-max" name="max_price" placeholder="Max" min="{{ $minPrice }}" max="{{ $maxPrice }}" value="{{ request('max_price', $maxPrice) }}">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100 mt-3" style="background:#71cd14;">OK</button>
                </form>
            </div>
        </aside>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    // CSRF Token Setup
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (!csrfToken) {
        console.error('CSRF token not found. Ensure <meta name="csrf-token"> is present in the layout.');
    }

    // Initialize selectedGridMode from localStorage or default to '4'
    let selectedGridMode = localStorage.getItem('selectedGridMode') || '{{ request('grid_mode', '4') }}';

    // Get current category route from a hidden input or JS variable
    let categoryRoute = "{{ Route::is('category.products') ? url()->current() : '' }}";

    // Initialize Price Slider
    $('#price-range').slider({
        range: true,
        min: {{ $minPrice ?? 0 }},
        max: {{ $maxPrice ?? 1000 }},
        values: [{{ request('min_price', $minPrice ?? 0) }}, {{ request('max_price', $maxPrice ?? 1000) }}],
        slide: function (event, ui) {
            $('#price-range-label').text('$' + ui.values[0] + ' - $' + ui.values[1]);
        },
        stop: function (event, ui) {
            filterProducts();
        }
    });

    // Set initial label for price range
    $('#price-range-label').text('$' + $('#price-range').slider('values', 0) + ' - $' + $('#price-range').slider('values', 1));

    // Set initial active state for category and sort
    $('.filter-category:checked').closest('.filter-category-label').addClass('active');
    $('.filter-sort:checked').closest('.filter-sort-label').addClass('active');

    // Filter Products Function
    function filterProducts() {
        const categoryId = $('.filter-category:checked').val() || '';
        const tags = [];
        $('.filter-tag:checked').each(function () {
            tags.push($(this).val());
        });
        let minPrice, maxPrice;
        if (window.innerWidth <= 991) {
            minPrice = $('.filter-price-min').val() || {{ $minPrice ?? 0 }};
            maxPrice = $('.filter-price-max').val() || {{ $maxPrice ?? 1000 }};
        } else {
            minPrice = $('#price-range').slider('values', 0);
            maxPrice = $('#price-range').slider('values', 1);
        }
        const sortBy = $('.filter-sort:checked').val() || '';
        const perPage = $('#per_page').val();

        const data = {
            tags: tags,
            min_price: minPrice,
            max_price: maxPrice,
            sort_by: sortBy,
            per_page: perPage,
            grid_mode: selectedGridMode
        };
        if (!categoryRoute) {
            data.category = categoryId; // Only send category param on /shop
        }

        console.log('Filter data:', data);

        let ajaxUrl = categoryRoute ? categoryRoute : "{{ route('shop.index') }}";

        $.ajax({
            url: ajaxUrl,
            method: "GET",
            data: data,
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            beforeSend: function () {
                console.log('Sending AJAX request...');
                showSkeleton();
                $('#loading-overlay').show();
                $('#product-list').css('opacity', '1');
            },
            success: function (response) {
                console.log('AJAX success:', response.html ? response.html.substring(0, 100) + '...' : response);
                setTimeout(function() {
                    $('#product-list').html(response.html);
                    $('#tags-list').html(response.tags);
                    $('#tags-list-mobile').html(response.tags);
                    // Rebind tag change events
                    $('.filter-tag').off('change').on('change', function () {
                        console.log('Tag changed:', $(this).val());
                        filterProducts();
                    });
                    $('#loading-overlay').hide();
                    $('#product-list').css('opacity', '1');
                    initializePagination();
                    applyGridMode();
                }, 1000);
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error, xhr.responseText);
                $('#loading-overlay').hide();
                $('#product-list').css('opacity', '1');
                $('#product-list').html('<p class="text-center">Error loading products. Please try again.</p>');
            }
        });
    }

    // Initialize Pagination
    function initializePagination() {
        $('.pagination a').off('click').on('click', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            if (categoryRoute) {
                let params = url.split('?')[1] || '';
                url = categoryRoute + (params ? '?' + params : '');
            }
            $.ajax({
                url: url,
                method: "GET",
                data: {
                    grid_mode: selectedGridMode,
                    category: $('.filter-category:checked').val() || '',
                    tags: $('.filter-tag:checked').map(function() { return $(this).val(); }).get(),
                    min_price: window.innerWidth <= 991 ? $('.filter-price-min').val() : $('#price-range').slider('values', 0),
                    max_price: window.innerWidth <= 991 ? $('.filter-price-max').val() : $('#price-range').slider('values', 1),
                    sort_by: $('.filter-sort:checked').val() || '',
                    per_page: $('#per_page').val()
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                beforeSend: function () {
                    console.log('Sending pagination AJAX request...');
                    showSkeleton();
                    $('#loading-overlay').show();
                    $('#product-list').css('opacity', '1');
                },
                success: function (response) {
                    console.log('Pagination success:', response.html ? response.html.substring(0, 100) + '...' : response);
                    setTimeout(function() {
                        $('#product-list').html(response.html);
                        $('#tags-list').html(response.tags);
                        $('#tags-list-mobile').html(response.tags);
                        $('.filter-tag').off('change').on('change', function () {
                            console.log('Tag changed:', $(this).val());
                            filterProducts();
                        });
                        $('#loading-overlay').hide();
                        $('#product-list').css('opacity', '1');
                        initializePagination();
                        applyGridMode();
                    }, 1000);
                },
                error: function (xhr, status, error) {
                    console.error('Pagination error:', status, error, xhr.responseText);
                    $('#loading-overlay').hide();
                    $('#product-list').css('opacity', '1');
                }
            });
        });
    }

    // Apply Grid Mode
    function applyGridMode() {
        if (window.innerWidth <= 991) {
            selectedGridMode = 'list';
        }
        $('#product-grid')
            .removeClass('product-cols-2 product-cols-3 product-cols-4 product-cols-6 product-cols-list')
            .addClass('product-cols-' + selectedGridMode);

        $('.view-mode-btn').removeClass('active');
        $('.view-mode-btn[data-cols="' + selectedGridMode + '"]').addClass('active');

        if (selectedGridMode === 'list') {
            $('.product-desc,.icon-action').removeClass('d-none');
            $('.product-icons').hide();
        } else {
            $('.product-desc,.icon-action').addClass('d-none');
            $('.product-icons').show();
        }
    }

    // View Mode Button Click
    $('.view-mode-btn').on('click', function () {
        selectedGridMode = $(this).data('cols');
        localStorage.setItem('selectedGridMode', selectedGridMode);
        console.log('View mode changed:', selectedGridMode);
        applyGridMode();
        filterProducts();
    });

    // Category Filter
    $('.filter-category').on('change', function () {
        var categoryId = $(this).val();
        $.ajax({
            url: "{{ route('shop.tags.byCategory') }}",
            method: "GET",
            data: { category_id: categoryId },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                $('#tags-list').html(response.html);
                $('#tags-list-mobile').html(response.html);
                $('.filter-tag').off('change').on('change', function () {
                    console.log('Tag changed:', $(this).val());
                    filterProducts();
                });
            },
            error: function (xhr, status, error) {
                console.error('Tags fetch error:', status, error, xhr.responseText);
            }
        });
        console.log('Category selected:', $(this).val());
        $('.filter-category-label').removeClass('active');
        $(this).closest('.filter-category-label').addClass('active');
        filterProducts();
    });

    // Sort Filter
    $('.filter-sort').on('change', function () {
        console.log('Sort selected:', $(this).val());
        $('.filter-sort-label').removeClass('active');
        $(this).closest('.filter-sort-label').addClass('active');
        filterProducts();
    });

    // Tag Filter
    $('.filter-tag').on('change', function () {
        console.log('Tag changed:', $(this).val());
        filterProducts();
    });

    // Per Page
    $('#per_page').on('change', function () {
        console.log('Per page changed:', $(this).val());
        filterProducts();
    });

    // Add to Cart
    $('#product-list').on('click', '.cart-btn', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const productId = $(this).data('id');

        $.ajax({
            url: url,
            method: 'POST',
            data: {
                product_id: productId,
                quantity: 1
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            beforeSend: function () {
                $('#loading-overlay').show();
                $('#product-list').css('opacity', '0.5');
            },
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#71cd14',
                        customClass: {
                            popup: 'animated fadeInDown'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            filterProducts();
                        }
                    });
                }
            },
            error: function (xhr) {
                console.error('Cart error:', xhr.responseJSON);
                Swal.fire({
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Failed to add product to cart.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#71cd14'
                });
            },
            complete: function () {
                $('#loading-overlay').hide();
                $('#product-list').css('opacity', '1');
            }
        });
    });

    // Add to Wishlist
    $('#product-list').on('click', '.wishlist-btn', function (e) {
        e.preventDefault();
        const url = $(this).data('url');
        const productId = $(this).data('id');

        $.ajax({
            url: url,
            method: 'POST',
            data: { product_id: productId },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            beforeSend: function () {
                $('#loading-overlay').show();
                $('#product-list').css('opacity', '0.5');
            },
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#71cd14',
                        customClass: {
                            popup: 'animated fadeInDown'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            filterProducts();
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Note!',
                        text: response.message,
                        icon: 'info',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#71cd14'
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error('Add to wishlist error:', status, error, xhr.responseText);
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to add product to wishlist.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#71cd14'
                });
            },
            complete: function () {
                $('#loading-overlay').hide();
                $('#product-list').css('opacity', '1');
            }
        });
    });

    // Mobile Filter Sidebar
    $('#open-filter-sidebar').on('click', function() {
        $('#mobile-filter-sidebar').addClass('open');
    });
    $('#close-filter-sidebar').on('click', function() {
        $('#mobile-filter-sidebar').removeClass('open');
    });
    $(document).on('click', function(e) {
        if ($(e.target).closest('#mobile-filter-sidebar, #open-filter-sidebar').length === 0) {
            $('#mobile-filter-sidebar').removeClass('open');
        }
    });

    $('#mobile-price-filter-form').on('submit', function(e) {
        e.preventDefault();
        filterProducts();
        $('#mobile-filter-sidebar').removeClass('open');
    });

    function showSkeleton() {
        $('#product-list').html(`@include('front.default.products.skeleton')`);
    }

    // Initialize Pagination and Grid Mode on Page Load
    initializePagination();
    applyGridMode();

    // Debug: Check if jQuery and jQuery UI are loaded
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded.');
    } else {
        console.log('jQuery loaded successfully.');
    }
    if (typeof $.ui === 'undefined') {
        console.error('jQuery UI is not loaded.');
    } else {
        console.log('jQuery UI loaded successfully.');
    }

    // Initial tags load for category route
    @if(isset($selectedCategoryId))
        $.ajax({
            url: "{{ route('shop.tags.byCategory') }}",
            method: "GET",
            data: { category_id: "{{ $selectedCategoryId }}" },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function (response) {
                $('#tags-list').html(response.html);
                $('#tags-list-mobile').html(response.html);
                $('.filter-tag').off('change').on('change', function () {
                    console.log('Tag changed:', $(this).val());
                    filterProducts();
                });
            },
            error: function (xhr, status, error) {
                console.error('Initial tags fetch error:', status, error, xhr.responseText);
            }
        });
    @endif
});
</script>

<style>
    .skeleton {
        background: linear-gradient(90deg, #eee 25%, #E5E4E2 50%, #eee 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 0.8s infinite linear;
        border-radius: 6px;
    }
    @keyframes skeleton-loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    .skeleton-img { width: 100%; height: 150px; margin-bottom: 12px; }
    .skeleton-title { width: 60%; height: 18px; }
    .skeleton-desc { width: 90%; height: 14px; }
    .skeleton-btn { width: 40%; height: 32px; }
    .skeleton-price { width: 30%; height: 18px; }
</style>
