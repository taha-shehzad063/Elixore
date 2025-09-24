@if (!request()->ajax())
    @extends('front.default.partials.app')

    @section('content')
@endif

<link rel="stylesheet" href="{{ asset('assets/css/shop.css') }}" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<section class="banner_area">
    <div class="banner_inner d-flex align-items-center" style="color: black;">
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
                    <div class="left_dorp no-dark1">
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
                    <h5 class="me-2">VIEW AS</h5>
                    <button type="button" class="btn btn-outline-secondary btn-sm view-mode-btn {{ request('grid_mode') == 'list' ? 'active' : '' }}" data-cols="list">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRQrS6kuEy9PhZJydK2hX91U7Px1S_TL0o4dkvAcx2Mnx7sjfjOamWP84BQakS600FVUUM&usqp=CAU" alt="List" style="height:24px;">
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm view-mode-btn {{ request('grid_mode') == '6' ? 'active' : '' }}" data-cols="6">
                        <img src="https://www.pngkey.com/png/detail/116-1160644_two-vertical-parallel-lines-vector-lineas-paralelas-verticales.png" alt="2" style="height:24px;">
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm view-mode-btn {{ request('grid_mode') == '4' ? 'active' : '' }}" data-cols="4">
                        <img src="https://img.freepik.com/premium-vector/hamburger-menu-icon-buttons-website-ui-navigation-mobile-app-vector-elements-user-interface-icons_1211661-943.jpg?semt=ais_hybrid&w=740&q=80" alt="3" style="height:24px;">
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm view-mode-btn {{ request('grid_mode') == '3' ? 'active' : '' }}" data-cols="3">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQaay22MbmODe7RG8AduTTMHvNf6nd4R93LDQ&s" alt="6" style="height:24px;">
                    </button>
                </div>

                <div class="latest_product_inner position-relative" id="product-grid">
                    <div class="loading-overlay" id="loading-overlay">
                        <div class="loading-bar"></div>
                    </div>
                    @include('front.default.products.products')
                </div>
            </div>

            <div class="col-lg-3 d-none d-md-flex">
                <div class="left_sidebar_area">
                    <aside class="left_widgets p_filter_widgets">
                        <div class="l_w_title">
                            <h3 class="filter-toggle" data-target="sort-list">Sort By <span class="toggle-icon">▼</span></h3>
                        </div>
                        <div class="widgets_inner collapse" id="sort-list">
                            <ul class="list">
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
                            <h3 class="filter-toggle" data-target="tags-list">Product Tags <span class="toggle-icon">▼</span></h3>
                        </div>
                        <div class="widgets_inner collapse" id="tags-list">
                            <ul class="list">
                                @include('front.default.products.partials.tags_list')
                            </ul>
                        </div>
                    </aside>

                    <aside class="left_widgets p_filter_widgets">
                        <div class="l_w_title">
                            <h3 class="filter-toggle" data-target="availability-list">Availability <span class="toggle-icon">▼</span></h3>
                        </div>
                        <div class="widgets_inner collapse" id="availability-list">
                            <ul class="list">
                                <li>
                                    <label class="filter-availability-label {{ !request('availability') ? 'active' : '' }}">
                                        <input type="radio" name="availability" class="filter-availability" value="" {{ !request('availability') ? 'checked' : '' }}>
                                        All
                                    </label>
                                </li>
                                <li>
                                    <label class="filter-availability-label {{ request('availability') == 'in stock' ? 'active' : '' }}">
                                        <input type="radio" name="availability" class="filter-availability" value="in stock" {{ request('availability') == 'in stock' ? 'checked' : '' }}>
                                        In Stock
                                    </label>
                                </li>
                                <li>
                                    <label class="filter-availability-label {{ request('availability') == 'out of stock' ? 'active' : '' }}">
                                        <input type="radio" name="availability" class="filter-availability" value="out of stock" {{ request('availability') == 'out of stock' ? 'checked' : '' }}>
                                        Out of Stock
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </aside>

                    <aside class="left_widgets p_filter_widgets">
                        <div class="l_w_title">
                            <h3 class="filter-toggle" data-target="price-range-section">Filter by Price <span class="toggle-icon">▼</span></h3>
                        </div>
                        <div class="widgets_inner collapse" id="price-range-section">
                            <div id="price-range"></div>
                            <div id="price-range-label" class="mt-2"></div>
                            <div class="row g-2 mt-2">
                                <div class="col-6">
                                    <input type="number" class="form-control filter-price-min" name="min_price" placeholder="Min" min="{{ $minPrice }}" max="{{ $maxPrice }}" value="{{ request('min_price', $minPrice) }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control filter-price-max" name="max_price" placeholder="Max" min="{{ $minPrice }}" max="{{ $maxPrice }}" value="{{ request('max_price', $maxPrice) }}">
                                </div>
                            </div>
                        </div>
                    </aside>
                    <button id="clear-filters"style="background-color:#71cd14; color:white;" class="btn w-100 mt-3">Clear Filters</button>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="mobile-filter-sidebar" class="mobile-filter-sidebar">
    <div class="sidebar-header d-flex justify-content-between align-items-center px-3 py-2" style="background:#71cd14;">
        <h5 class="text-white fw-bold">Filters</h5>
        <button id="close-filter-sidebar" class="btn btn-sm btn-light">&times;</button>
    </div>
    <div class="sidebar-body p-3">
        <aside class="left_widgets p_filter_widgets mb-4">
            <div class="l_w_title">
                <h3 class="filter-toggle" data-target="sort-list-mobile">Sort By <span class="toggle-icon">▼</span></h3>
            </div>
            <div class="widgets_inner collapse" id="sort-list-mobile">
                <ul class="list">
                    <li>
                        <label class="filter-sort-label {{ !request('sort_by') ? 'active' : '' }}">
                            <input type="radio" name="sort_by_mobile" class="filter-sort" value="" {{ !request('sort_by') ? 'checked' : '' }}>
                            Default
                        </label>
                    </li>
                    <li>
                        <label class="filter-sort-label {{ request('sort_by') == 'price_asc' ? 'active' : '' }}">
                            <input type="radio" name="sort_by_mobile" class="filter-sort" value="price_asc" {{ request('sort_by') == 'price_asc' ? 'checked' : '' }}>
                            Price: Low to High
                        </label>
                    </li>
                    <li>
                        <label class="filter-sort-label {{ request('sort_by') == 'price_desc' ? 'active' : '' }}">
                            <input type="radio" name="sort_by_mobile" class="filter-sort" value="price_desc" {{ request('sort_by') == 'price_desc' ? 'checked' : '' }}>
                            Price: High to Low
                        </label>
                    </li>
                    <li>
                        <label class="filter-sort-label {{ request('sort_by') == 'best_selling' ? 'active' : '' }}">
                            <input type="radio" name="sort_by_mobile" class="filter-sort" value="best_selling" {{ request('sort_by') == 'best_selling' ? 'checked' : '' }}>
                            Best Selling
                        </label>
                    </li>
                    <li>
                        <label class="filter-sort-label {{ request('sort_by') == 'a_z' ? 'active' : '' }}">
                            <input type="radio" name="sort_by_mobile" class="filter-sort" value="a_z" {{ request('sort_by') == 'a_z' ? 'checked' : '' }}>
                            Name: A-Z
                        </label>
                    </li>
                    <li>
                        <label class="filter-sort-label {{ request('sort_by') == 'z_a' ? 'active' : '' }}">
                            <input type="radio" name="sort_by_mobile" class="filter-sort" value="z_a" {{ request('sort_by') == 'z_a' ? 'checked' : '' }}>
                            Name: Z-A
                        </label>
                    </li>
                    <li>
                        <label class="filter-sort-label {{ request('sort_by') == 'old_to_new' ? 'active' : '' }}">
                            <input type="radio" name="sort_by_mobile" class="filter-sort" value="old_to_new" {{ request('sort_by') == 'old_to_new' ? 'checked' : '' }}>
                            Old to New
                        </label>
                    </li>
                    <li>
                        <label class="filter-sort-label {{ request('sort_by') == 'new_to_old' ? 'active' : '' }}">
                            <input type="radio" name="sort_by_mobile" class="filter-sort" value="new_to_old" {{ request('sort_by') == 'new_to_old' ? 'checked' : '' }}>
                            New to Old
                        </label>
                    </li>
                </ul>
            </div>
        </aside>

        <aside class="left_widgets p_filter_widgets mb-4">
            <div class="l_w_title">
                <h3 class="filter-toggle" data-target="tags-list-mobile">Product Tags <span class="toggle-icon">▼</span></h3>
            </div>
            <div class="widgets_inner collapse" id="tags-list-mobile">
                <ul class="list">
                    @include('front.default.products.partials.tags_list')
                </ul>
            </div>
        </aside>

        <aside class="left_widgets p_filter_widgets mb-4">
            <div class="l_w_title">
                <h3 class="filter-toggle" data-target="availability-list-mobile">Filter by Availability <span class="toggle-icon">▼</span></h3>
            </div>
            <div class="widgets_inner collapse" id="availability-list-mobile">
                <ul class="list">
                    <li>
                        <label class="filter-availability-label {{ !request('availability') ? 'active' : '' }}">
                            <input type="radio" name="availability_mobile" class="filter-availability" value="" {{ !request('availability') ? 'checked' : '' }}>
                            All
                        </label>
                    </li>
                    <li>
                        <label class="filter-availability-label {{ request('availability') == 'in stock' ? 'active' : '' }}">
                            <input type="radio" name="availability_mobile" class="filter-availability" value="in stock" {{ request('availability') == 'in stock' ? 'checked' : '' }}>
                            In Stock
                        </label>
                    </li>
                    <li>
                        <label class="filter-availability-label {{ request('availability') == 'out of stock' ? 'active' : '' }}">
                            <input type="radio" name="availability_mobile" class="filter-availability" value="out of stock" {{ request('availability') == 'out of stock' ? 'checked' : '' }}>
                            Out of Stock
                        </label>
                    </li>
                </ul>
            </div>
        </aside>

        <aside class="left_widgets p_filter_widgets mb-4">
            <div class="l_w_title">
                <h3 class="filter-toggle" data-target="mobile-price-filter-form">Filter by Price <span class="toggle-icon">▼</span></h3>
            </div>
            <div class="widgets_inner collapse" id="mobile-price-filter-form">
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
        <button id="clear-filters-mobile" style="background-color:#71cd14; color:white;"class="btn  w-100 mt-3">Clear Filters</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Pass authentication status to JavaScript
const isAuthenticated = @json(auth()->check());
const loginUrl = "{{ route('user.login') }}";
const selectedSubCategoryId = "{{ $selectedSubCategoryId ?? '' }}";
const subCategorySlug = "{{ $subcategory->slug ?? '' }}";

$(document).ready(function () {
    // CSRF Token Setup
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (!csrfToken) {
        console.error('CSRF token not found. Ensure <meta name="csrf-token"> is present in the layout.');
    }

    // Initialize selectedGridMode
    let selectedGridMode = localStorage.getItem('selectedGridMode') || '{{ request('grid_mode', '4') }}';
    let categoryRoute = "{{ Route::is('subcategory.products') ? url()->current() : '' }}";

    // Initialize Price Slider
    const minPrice = {{ $minPrice ?? 0 }};
    const maxPrice = {{ $maxPrice ?? 1000 }};
    const currentMinPrice = {{ request('min_price', $minPrice ?? 0) }};
    const currentMaxPrice = {{ request('max_price', $maxPrice ?? 1000) }};

    $('#price-range').slider({
        range: true,
        min: minPrice,
        max: maxPrice,
        values: [currentMinPrice, currentMaxPrice],
        slide: function(event, ui) {
            $('#price-range-label').text('$' + ui.values[0] + ' - $' + ui.values[1]);
            $('.filter-price-min').val(ui.values[0]);
            $('.filter-price-max').val(ui.values[1]);
        },
        stop: function(event, ui) {
            filterProducts();
        }
    });

    // Update label and inputs on initialization
    $('#price-range-label').text('$' + $('#price-range').slider('values', 0) + ' - $' + $('#price-range').slider('values', 1));
    $('.filter-price-min').val($('#price-range').slider('values', 0));
    $('.filter-price-max').val($('#price-range').slider('values', 1));

    // Sync input fields with slider
    $('.filter-price-min').on('change', function() {
        let value = parseInt($(this).val()) || minPrice;
        if (value < minPrice) value = minPrice;
        if (value > maxPrice) value = maxPrice;
        if (value > $('#price-range').slider('values', 1)) value = $('#price-range').slider('values', 1);
        $('#price-range').slider('values', 0, value);
        $('#price-range-label').text('$' + value + ' - $' + $('#price-range').slider('values', 1));
        filterProducts();
    });

    $('.filter-price-max').on('change', function() {
        let value = parseInt($(this).val()) || maxPrice;
        if (value < minPrice) value = minPrice;
        if (value > maxPrice) value = maxPrice;
        if (value < $('#price-range').slider('values', 0)) value = $('#price-range').slider('values', 0);
        $('#price-range').slider('values', 1, value);
        $('#price-range-label').text('$' + $('#price-range').slider('values', 0) + ' - $' + value);
        filterProducts();
    });

    // Collapsible Filter Toggle
    $('.filter-toggle').on('click', function() {
        const target = $(this).data('target');
        const $target = $('#' + target);
        const $icon = $(this).find('.toggle-icon');
        
        $('.widgets_inner.collapse').not($target).slideUp();
        $('.filter-toggle').not(this).find('.toggle-icon').text('▼');
        
        $target.slideToggle();
        $icon.text($target.is(':visible') ? '▲' : '▼');
    });

    // Update Active States for Radio Buttons and Checkboxes
    function updateActiveStates() {
        $('.filter-sort-label').removeClass('active');
        $('.filter-availability-label').removeClass('active');
        $('.filter-tag-label').removeClass('active');
        $('.filter-sort:checked').closest('.filter-sort-label').addClass('active');
        $('.filter-availability:checked').closest('.filter-availability-label').addClass('active');
        $('.filter-tag:checked').closest('.filter-tag-label').addClass('active');
    }

    // Filter Products Function
    function filterProducts(changedTag = null) {
        const tags = $('.filter-tag:checked').map(function() { return $(this).val(); }).get();
        console.log('Selected tags before AJAX:', tags); // Debug: Log selected tags

        const availability = $('.filter-availability:checked').val() || '';
        const minPriceVal = window.innerWidth <= 991 ? 
            (parseInt($('.filter-price-min').val()) || {{ $minPrice ?? 0 }}) : 
            $('#price-range').slider('values', 0);
        const maxPriceVal = window.innerWidth <= 991 ? 
            (parseInt($('.filter-price-max').val()) || {{ $maxPrice ?? 1000 }}) : 
            $('#price-range').slider('values', 1);
        const sortBy = $('.filter-sort:checked').val() || '';
        const perPage = $('#per_page').val() || 12;
        const gridMode = localStorage.getItem('selectedGridMode') || '{{ request('grid_mode', '4') }}';

        const ajaxData = {
            sub_category_id: selectedSubCategoryId,
            tags: tags,
            availability: availability,
            min_price: minPriceVal,
            max_price: maxPriceVal,
            sort_by: sortBy,
            per_page: perPage,
            grid_mode: gridMode,
            filter: true,
            _: Date.now() // Cache-busting parameter
        };

        let ajaxUrl = categoryRoute || "{{ route('shop.index') }}";
        
        // Removed updateUrlParameters() to prevent URL changes

        $.ajax({
            url: ajaxUrl,
            method: "GET",
            data: ajaxData,
            headers: { 'X-CSRF-TOKEN': csrfToken },
            beforeSend: function () {
                showSkeleton();
                $('#loading-overlay').show();
            },
            success: function (response) {
                console.log('AJAX response tags HTML:', response.tags); // Debug: Log tags HTML
                console.log('Tags sent in request:', tags); // Debug: Log tags sent
                setTimeout(function() {
                    $('#product-grid').html(response.html || '<p class="text-center">No products found.</p>');
                    $('#tags-list, #tags-list-mobile').html(response.tags || '');
                    // Manually sync tag checkboxes
                    $('.filter-tag').each(function() {
                        $(this).prop('checked', tags.includes($(this).val()));
                    });
                    console.log('Tags in DOM after update:', $('.filter-tag').map(function() { 
                        return { id: $(this).val(), checked: $(this).is(':checked') }; 
                    }).get()); // Debug: Log DOM tag state
                    updateActiveStates();
                    bindFilterEvents();
                    $('#loading-overlay').hide();
                    initializePagination();
                    applyGridMode();
                }, 500);
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error, xhr.responseText);
                $('#loading-overlay').hide();
                $('#product-grid').html('<p class="text-center">Error loading products. Please try again.</p>');
            }
        });
    }

    // Removed updateUrlParameters() function entirely since it's no longer needed

    // Initialize Pagination
    function initializePagination() {
        $('.pagination a').off('click').on('click', function (e) {
            e.preventDefault();
            let url = $(this).attr('href');
            if (categoryRoute) {
                let params = url.split('?')[1] || '';
                url = categoryRoute + (params ? '?' + params : '');
            }

            const tags = $('.filter-tag:checked').map(function() { return $(this).val(); }).get();
            console.log('Pagination tags:', tags); // Debug: Log tags during pagination

            $.ajax({
                url: url,
                method: "GET",
                data: {
                    sub_category_id: selectedSubCategoryId,
                    tags: tags,
                    availability: $('.filter-availability:checked').val() || '',
                    min_price: window.innerWidth <= 991 ? (parseInt($('.filter-price-min').val()) || {{ $minPrice ?? 0 }}) : $('#price-range').slider('values', 0),
                    max_price: window.innerWidth <= 991 ? (parseInt($('.filter-price-max').val()) || {{ $maxPrice ?? 1000 }}) : $('#price-range').slider('values', 1),
                    sort_by: $('.filter-sort:checked').val() || '',
                    per_page: $('#per_page').val() || 12,
                    grid_mode: localStorage.getItem('selectedGridMode') || '{{ request('grid_mode', '4') }}',
                    filter: true,
                    _: Date.now() // Cache-busting parameter
                },
                headers: { 'X-CSRF-TOKEN': csrfToken },
                beforeSend: function () {
                    showSkeleton();
                    $('#loading-overlay').show();
                },
                success: function (response) {
                    console.log('Pagination response tags HTML:', response.tags); // Debug: Log tags HTML
                    setTimeout(function() {
                        $('#product-grid').html(response.html || '<p class="text-center">No products found.</p>');
                        $('#tags-list, #tags-list-mobile').html(response.tags || '');
                        $('.filter-tag').each(function() {
                            $(this).prop('checked', tags.includes($(this).val()));
                        });
                        console.log('Tags in DOM after pagination:', $('.filter-tag').map(function() { 
                            return { id: $(this).val(), checked: $(this).is(':checked') }; 
                        }).get()); // Debug: Log DOM tag state
                        updateActiveStates();
                        bindFilterEvents();
                        $('#loading-overlay').hide();
                        initializePagination();
                        applyGridMode();
                    }, 500);
                },
                error: function (xhr, status, error) {
                    console.error('Pagination error:', status, error, xhr.responseText);
                    $('#loading-overlay').hide();
                    $('#product-grid').html('<p class="text-center">Error loading products. Please try again.</p>');
                }
            });
        });
    }

    // Apply Grid Mode
    function applyGridMode() {
        $('#product-grid').removeClass(function (index, className) {
            return (className.match(/(^|\s)product-cols-\S+/g) || []).join(' ');
        });
        
        $('#product-grid').addClass('product-cols-' + selectedGridMode);

        $('.view-mode-btn').removeClass('active');
        $('.view-mode-btn[data-cols="' + selectedGridMode + '"]').addClass('active');

        if (selectedGridMode === 'list') {
            $('.product-desc, .icon-action').removeClass('d-none');
            $('.product-icons').hide();
        } else {
            $('.product-desc, .icon-action').addClass('d-none');
            $('.product-icons').show();
        }
    }

    // Bind Filter Events
    function bindFilterEvents() {
        $('.filter-sort').off('change').on('change', function () {
            updateActiveStates();
            filterProducts();
        });

        $('.filter-availability').off('change').on('change', function () {
            updateActiveStates();
            filterProducts();
        });

        $('.filter-tag').off('change').on('change', function () {
            const tagId = $(this).val();
            const isChecked = $(this).is(':checked');
            console.log('Tag changed:', tagId, 'Checked:', isChecked); // Debug: Log tag change
            updateActiveStates();
            filterProducts({ id: tagId, checked: isChecked });
        });

        $('#per_page').off('change').on('change', function () {
            filterProducts();
        });
    }

    // Fetch Tags for Subcategory
    function fetchTags(subCategoryId) {
        const tags = $('.filter-tag:checked').map(function() { return $(this).val(); }).get();
        $.ajax({
            url: "{{ route('shop.tags.byCategory') }}",
            method: "GET",
            data: { 
                sub_category_id: subCategoryId,
                tags: tags // Pass current tags to ensure consistency
            },
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function (response) {
                console.log('Fetched tags HTML:', response.html); // Debug: Log fetched tags
                $('#tags-list').html(response.html || '');
                $('#tags-list-mobile').html(response.html || '');
                $('.filter-tag').each(function() {
                    $(this).prop('checked', tags.includes($(this).val()));
                });
                console.log('Tags in DOM after fetchTags:', $('.filter-tag').map(function() { 
                    return { id: $(this).val(), checked: $(this).is(':checked') }; 
                }).get()); // Debug: Log DOM tag state
                updateActiveStates();
                bindFilterEvents();
            },
            error: function (xhr, status, error) {
                console.error('Tags fetch error:', status, error, xhr.responseText);
            }
        });
    }

    // View Mode Button Click
    $('.view-mode-btn').on('click', function () {
        selectedGridMode = $(this).data('cols');
        localStorage.setItem('selectedGridMode', selectedGridMode);
        filterProducts();
    });

    // Clear Filters
    $('#clear-filters, #clear-filters-mobile').on('click', function() {
        $('.filter-tag').prop('checked', false);
        $('.filter-availability').val('');
        $('.filter-sort').val('');
        $('.filter-price-min').val({{ $minPrice ?? 0 }});
        $('.filter-price-max').val({{ $maxPrice ?? 1000 }});
        $('#price-range').slider('values', [{{ $minPrice ?? 0 }}, {{ $maxPrice ?? 1000 }}]);
        $('#price-range-label').text('$ {{ $minPrice ?? 0 }} - $ {{ $maxPrice ?? 1000 }}');
        updateActiveStates();
        filterProducts();
    });

    // Add to Cart
    $('#product-grid').on('click', '.cart-btn', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const productId = $(this).data('id');

        $.ajax({
            url: url,
            method: 'POST',
            data: { product_id: productId, quantity: 1 },
            headers: { 'X-CSRF-TOKEN': csrfToken },
            beforeSend: function () {
                $('#loading-overlay').show();
            },
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#71cd14'
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
            }
        });
    });

    // Add to Wishlist
    $('#product-grid').on('click', '.wishlist-btn', function (e) {
        e.preventDefault();
        const url = $(this).data('url');
        const productId = $(this).data('id');

        $.ajax({
            url: url,
            method: 'POST',
            data: { product_id: productId },
            headers: { 'X-CSRF-TOKEN': csrfToken },
            beforeSend: function () {
                $('#loading-overlay').show();
            },
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#71cd14'
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
            error: function (xhr) {
                console.error('Wishlist error:', xhr.responseJSON);
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

    // Show Skeleton
    function showSkeleton() {
        const skeletonHtml = `
            <div class="row">
                ${Array(6).fill().map(() => `
                    <div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="skeleton skeleton-img mb-3"></div>
                        <div class="skeleton skeleton-title mb-2"></div>
                        <div class="skeleton skeleton-desc mb-2"></div>
                        <div class="skeleton skeleton-price mb-2"></div>
                        <div class="skeleton skeleton-btn"></div>
                    </div>
                `).join('')}
            </div>
        `;
        $('#product-grid').html(skeletonHtml);
    }

    // Initialize
    bindFilterEvents();
    initializePagination();
    applyGridMode();

    // Initial tags load for subcategory
    @if(isset($selectedSubCategoryId))
        fetchTags("{{ $selectedSubCategoryId }}");
    @endif
});
</script>

<style>
    .skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1s infinite linear;
        border-radius: 4px;
    }
    @keyframes skeleton-loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    .skeleton-img { width: 100%; height: 200px; }
    .skeleton-title { width: 60%; height: 20px; }
    .skeleton-desc { width: 90%; height: 15px; }
    .skeleton-btn { width: 40%; height: 30px; }
    .skeleton-price { width: 30%; height: 20px; }
    .loading-overlay { display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.7); z-index: 10; }
    .loading-bar { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 50px; height: 50px; border: 5px solid #71cd14; border-top-color: transparent; border-radius: 50%; animation: spin 1s linear infinite; }
    @keyframes spin { to { transform: translate(-50%, -50%) rotate(360deg); } }
    .filter-toggle { cursor: pointer; position: relative; }
    .toggle-icon { position: absolute; right: -19px; }
    .collapse { display: none; }
    .collapse.show { display: block; }
    .ui-slider .ui-slider-handle { width: 20px; height: 20px; border-radius: 50%; background: #71cd14; border: 2px solid #fff; }
    .ui-slider { background: #f0f0f0; height: 8px; border-radius: 4px; }
    .ui-slider-range { background: #71cd14; }
    .filter-tag-label.active { font-weight: bold; color: #71cd14; }
</style>

@if (!request()->ajax())
    @endsection
@endif