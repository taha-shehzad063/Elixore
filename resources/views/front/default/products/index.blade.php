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
                                <h3 class="filter-toggle" data-target="category-list">Categories <span class="toggle-icon">▼</span></h3>
                            </div>
                            <div class="widgets_inner collapse" id="category-list">
                                <ul class="list">
                                    <li>
                                        <label class="filter-category-label {{ !request('category') && !isset($selectedCategoryId) ? 'active' : '' }}">
                                            <input type="radio" name="category" class="filter-category" value="" data-name="" {{ !request('category') && !isset($selectedCategoryId) ? 'checked' : '' }}>
                                            All Categories
                                        </label>
                                    </li>
                                    @foreach($categories as $category)
                                        <li>
                                            <label class="filter-category-label {{ (request('category') == $category->id || (isset($selectedCategoryId) && $selectedCategoryId == $category->id)) ? 'active' : '' }}">
                                                <input type="radio" name="category" class="filter-category" value="{{ $category->id }}" data-name="{{ urlencode(strtolower($category->name)) }}" {{ (request('category') == $category->id || (isset($selectedCategoryId) && $selectedCategoryId == $category->id)) ? 'checked' : '' }}>
                                                {{ $category->name }}
                                                @if($loop->first)
                                                    <span class="badge bg-danger">Hot</span>
                                                @elseif($loop->iteration == 2)
                                                    <span class="badge bg-success">Sale</span>
                                                @endif
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </aside>
                    @endif

                    <aside class="left_widgets p_filter_widgets" id="subcategory-filter" style="display: {{ $subcategories->isNotEmpty() ? 'block' : 'none' }};">
                        <div class="l_w_title">
                            <h3 class="filter-toggle" data-target="subcategory-list">Sub Categories <span class="toggle-icon">▼</span></h3>
                        </div>
                        <div class="widgets_inner collapse" id="subcategory-list">
                            <ul class="list" id="subcategory-ul">
                                <li>
                                    <label class="filter-subcategory-label {{ !request('subcategory') ? 'active' : '' }}">
                                        <input type="radio" name="subcategory" class="filter-subcategory" value="" data-name="" {{ !request('subcategory') ? 'checked' : '' }}>
                                        All Sub Categories
                                    </label>
                                </li>
                                @foreach($subcategories as $subcategory)
                                    <li>
                                        <label class="filter-subcategory-label {{ request('subcategory') == $subcategory->id ? 'active' : '' }}">
                                            <input type="radio" name="subcategory" class="filter-subcategory" value="{{ $subcategory->id }}" data-name="{{ urlencode(strtolower($subcategory->name)) }}" {{ request('subcategory') == $subcategory->id ? 'checked' : '' }}>
                                            {{ $subcategory->name }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </aside>

                    <aside class="left_widgets p_filter_widgets" id="tags-filter" style="{{ $tags->isEmpty() ? 'display: none;' : '' }}">
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
                    <button id="clear-filters" style="background-color:#71cd14; color:white;" class="btn  w-100 mt-3">Clear Filters</button>
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
        @if (!Route::is('category.products'))
            <aside class="left_widgets p_filter_widgets mb-4">
                <div class="l_w_title">
                    <h3 class="filter-toggle" data-target="category-list-mobile">Browse Categories <span class="toggle-icon">▼</span></h3>
                </div>
                <div class="widgets_inner collapse" id="category-list-mobile">
                    <ul class="list">
                        <li>
                            <label class="filter-category-label {{ !request('category') && !isset($selectedCategoryId) ? 'active' : '' }}">
                                <input type="radio" name="category_mobile" class="filter-category" value="" data-name="" {{ !request('category') && !isset($selectedCategoryId) ? 'checked' : '' }}>
                                All Categories
                            </label>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <label class="filter-category-label {{ (request('category') == $category->id || (isset($selectedCategoryId) && $selectedCategoryId == $category->id)) ? 'active' : '' }}">
                                    <input type="radio" name="category_mobile" class="filter-category" value="{{ $category->id }}" data-name="{{ urlencode(strtolower($category->name)) }}" {{ (request('category') == $category->id || (isset($selectedCategoryId) && $selectedCategoryId == $category->id)) ? 'checked' : '' }}>
                                    {{ $category->name }}
                                    @if($loop->first)
                                        <span class="badge bg-danger">Hot</span>
                                    @elseif($loop->iteration == 2)
                                        <span class="badge bg-success">Sale</span>
                                    @endif
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>
        @endif

        <aside class="left_widgets p_filter_widgets mb-4" id="subcategory-filter-mobile" style="display: {{ $subcategories->isNotEmpty() ? 'block' : 'none' }};">
            <div class="l_w_title">
                <h3 class="filter-toggle" data-target="subcategory-list-mobile">Sub Categories <span class="toggle-icon">▼</span></h3>
            </div>
            <div class="widgets_inner collapse" id="subcategory-list-mobile">
                <ul class="list" id="subcategory-ul-mobile">
                    <li>
                        <label class="filter-subcategory-label {{ !request('subcategory') ? 'active' : '' }}">
                            <input type="radio" name="subcategory_mobile" class="filter-subcategory" value="" data-name="" {{ !request('subcategory') ? 'checked' : '' }}>
                            All Sub Categories
                        </label>
                    </li>
                    @foreach($subcategories as $subcategory)
                        <li>
                            <label class="filter-subcategory-label {{ request('subcategory') == $subcategory->id ? 'active' : '' }}">
                                <input type="radio" name="subcategory_mobile" class="filter-subcategory" value="{{ $subcategory->id }}" data-name="{{ urlencode(strtolower($subcategory->name)) }}" {{ request('subcategory') == $subcategory->id ? 'checked' : '' }}>
                                {{ $subcategory->name }}
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <aside class="left_widgets p_filter_widgets mb-4" id="tags-filter-mobile" style="{{ $tags->isEmpty() ? 'display: none;' : '' }}">
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
        <button id="clear-filters-mobile" style="background-color:#71cd14; color:white;" class="btn  w-100 mt-3">Clear Filters</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (!csrfToken) {
        console.error('CSRF token not found.');
    }

    let selectedGridMode = localStorage.getItem('selectedGridMode') || '{{ request('grid_mode', '4') }}';
    let initialCategoryName = '';
    if (window.location.pathname.match(/^\/category\/([^/]+)/)) {
        initialCategoryName = decodeURIComponent(window.location.pathname.match(/^\/category\/([^/]+)/)[1]);
    }

    let categoryRoute = initialCategoryName ? 
        "{{ route('category.products', ':categoryName') }}".replace(':categoryName', encodeURIComponent(initialCategoryName.replace(/ /g, '-').toLowerCase())) : 
        "{{ route('shop.index') }}";

    let currentFilterState = {
        categoryId: '{{ isset($selectedCategoryId) ? $selectedCategoryId : '' }}',
        categoryName: initialCategoryName || '{{ request('category') }}',
        subcategoryId: '{{ request('subcategory') }}',
        subcategoryName: '{{ request('subcategory') ? urlencode(strtolower(SubCategory::find(request('subcategory'))?->name ?? '')) : '' }}',
        sortBy: '{{ request('sort_by') }}',
        availability: '{{ request('availability') }}',
        tags: []
    };

    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    const debouncedFilterProducts = debounce(filterProducts, 300);

    $('#price-range').slider({
        range: true,
        min: {{ $minPrice ?? 0 }},
        max: {{ $maxPrice ?? 1000 }},
        values: [{{ request('min_price', $minPrice ?? 0) }}, {{ request('max_price', $maxPrice ?? 1000) }}],
        slide: function (event, ui) {
            $('#price-range-label').text('' + ui.values[0] + ' - ' + ui.values[1]);
            $('.filter-price-min').val(ui.values[0]);
            $('.filter-price-max').val(ui.values[1]);
        },
        stop: function (event, ui) {
            debouncedFilterProducts();
        }
    });
    $('#price-range-label').text('' + $('#price-range').slider('values', 0) + ' - ' + $('#price-range').slider('values', 1));
    $('.filter-price-min').val($('#price-range').slider('values', 0));
    $('.filter-price-max').val($('#price-range').slider('values', 1));

    // Sync input fields with slider
    $('.filter-price-min').on('change', function() {
        let value = parseInt($(this).val()) || {{ $minPrice ?? 0 }};
        if (value < {{ $minPrice ?? 0 }}) value = {{ $minPrice ?? 0 }};
        if (value > {{ $maxPrice ?? 1000 }}) value = {{ $maxPrice ?? 1000 }};
        if (value > $('#price-range').slider('values', 1)) value = $('#price-range').slider('values', 1);
        $('#price-range').slider('values', 0, value);
        $('#price-range-label').text('' + value + ' - ' + $('#price-range').slider('values', 1));
        debouncedFilterProducts();
    });

    $('.filter-price-max').on('change', function() {
        let value = parseInt($(this).val()) || {{ $maxPrice ?? 1000 }};
        if (value < {{ $minPrice ?? 0 }}) value = {{ $minPrice ?? 0 }};
        if (value > {{ $maxPrice ?? 1000 }}) value = {{ $maxPrice ?? 1000 }};
        if (value < $('#price-range').slider('values', 0)) value = $('#price-range').slider('values', 0);
        $('#price-range').slider('values', 1, value);
        $('#price-range-label').text('' + $('#price-range').slider('values', 0) + ' - ' + value);
        debouncedFilterProducts();
    });

    $(document).on('click', '.filter-toggle', function() {
        const target = $(this).data('target');
        const $target = $('#' + target);
        const $icon = $(this).find('.toggle-icon');
        
        $('.widgets_inner.collapse').not($target).slideUp();
        $('.filter-toggle').not(this).find('.toggle-icon').text('▼');
        
        $target.slideToggle();
        $icon.text($target.is(':visible') ? '▲' : '▼');
    });

    $('.widgets_inner.collapse').slideUp();
    updateActiveStates();
    toggleSubCategoryFilter();
    toggleTagsFilter();

    function filterProducts() {
        const isMobile = window.innerWidth <= 991;
        const $categoryInput = isMobile ? 
            $('input.filter-category[name="category_mobile"]:checked') : 
            $('input.filter-category[name="category"]:checked');
        const $subcategoryInput = isMobile ? 
            $('input.filter-subcategory[name="subcategory_mobile"]:checked') : 
            $('input.filter-subcategory[name="subcategory"]:checked');
        const $sortInput = isMobile ? 
            $('input.filter-sort[name="sort_by_mobile"]:checked') : 
            $('input.filter-sort[name="sort_by"]:checked');
        const $availabilityInput = isMobile ? 
            $('input.filter-availability[name="availability_mobile"]:checked') : 
            $('input.filter-availability[name="availability"]:checked');

        const categoryId = currentFilterState.categoryId || ($categoryInput.length ? $categoryInput.val() : '');
        const categoryName = currentFilterState.categoryName || ($categoryInput.length ? $categoryInput.data('name') : initialCategoryName);
        const subcategoryId = currentFilterState.subcategoryId || ($subcategoryInput.length ? $subcategoryInput.val() : '');
        const tags = currentFilterState.tags.length ? currentFilterState.tags : 
            $('input.filter-tag[name="tags[]"]:checked').map(function() { return $(this).val(); }).get();
        const availability = currentFilterState.availability || ($availabilityInput.length ? $availabilityInput.val() : '');
        const minPrice = isMobile ? 
            ($('input.filter-price-min[name="min_price"]').val() || {{ $minPrice ?? 0 }}) : 
            ($('#price-range').length ? $('#price-range').slider('values', 0) : {{ $minPrice ?? 0 }});
        const maxPrice = isMobile ? 
            ($('input.filter-price-max[name="max_price"]').val() || {{ $maxPrice ?? 1000 }}) : 
            ($('#price-range').length ? $('#price-range').slider('values', 1) : {{ $maxPrice ?? 1000 }});
        const sortBy = currentFilterState.sortBy || ($sortInput.length ? $sortInput.val() : '');
        const perPage = $('#per_page').val() || 12;
        const gridMode = localStorage.getItem('selectedGridMode') || '{{ request('grid_mode', '4') }}';

        let url = categoryId && categoryName ? 
            "{{ route('category.products', ':categoryName') }}".replace(':categoryName', encodeURIComponent(categoryName.replace(/ /g, '-').toLowerCase())) : 
            "{{ route('shop.index') }}";

        const ajaxData = {
            category: categoryId,
            subcategory: subcategoryId,
            tags: tags,
            availability: availability,
            min_price: minPrice,
            max_price: maxPrice,
            sort_by: sortBy,
            per_page: perPage,
            grid_mode: gridMode,
            filter: true
        };

        console.log('Filter AJAX Data:', ajaxData);

        $.ajax({
            url: url,
            method: "GET",
            data: ajaxData,
            headers: { 'X-CSRF-TOKEN': csrfToken },
            beforeSend: function () {
                showSkeleton();
                $('#loading-overlay').show();
            },
            success: function (response) {
                setTimeout(function() {
                    $('#product-list').html(response.html || '<p class="text-center">No products found.</p>');
                    updateActiveStates();
                    toggleTagsFilter();
                    bindFilterEvents();
                    $('#loading-overlay').hide();
                    initializePagination();
                    applyGridMode();
                }, 500);
            },
            error: function (xhr, status, error) {
                console.error('Filter AJAX error:', status, error, xhr.responseText);
                $('#loading-overlay').hide();
                $('#product-list').html('<p class="text-center">Error loading products. Please try again.</p>');
                Swal.fire({
                    title: 'Error!',
                    text: xhr.responseJSON?.error || 'Error loading products.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#71cd14'
                });
            }
        });
    }

    function toggleSubCategoryFilter() {
        const categoryId = currentFilterState.categoryId || 
            $('input.filter-category[name="category"]:checked, input.filter-category[name="category_mobile"]:checked').val();
        console.log('toggleSubCategoryFilter: categoryId=', categoryId);
        if (categoryId) {
            fetchSubCategories(categoryId);
        } else {
            $('#subcategory-filter, #subcategory-filter-mobile').hide();
            $('#subcategory-ul, #subcategory-ul-mobile').html('<li><p>No subcategories available</p></li>');
            $('.filter-subcategory').prop('checked', false);
            $('.filter-subcategory-label').removeClass('active');
            $('.filter-subcategory[value=""]').prop('checked', true).closest('.filter-subcategory-label').addClass('active');
            currentFilterState.subcategoryId = '';
            currentFilterState.subcategoryName = '';
            console.log('Subcategory filter hidden: no categoryId');
            fetchTags();
        }
    }

    function toggleTagsFilter() {
        const $tagsList = $('#tags-list');
        const $tagsListMobile = $('#tags-list-mobile');
        if ($tagsList.find('li').length > 0 && $tagsList.find('li').text().trim() !== '' && !$tagsList.find('li').text().includes('No tags available')) {
            $('#tags-filter, #tags-filter-mobile').show();
            console.log('Tags filter shown');
        } else {
            $('#tags-filter, #tags-filter-mobile').hide();
            console.log('Tags filter hidden');
        }
    }

    function fetchSubCategories(categoryId) {
        console.log('Fetching subcategories for categoryId:', categoryId);
        $('.filter-subcategory').prop('checked', false);
        $('.filter-subcategory-label').removeClass('active');
        $('.filter-subcategory[value=""]').prop('checked', true).closest('.filter-subcategory-label').addClass('active');
        $('.filter-tag').prop('checked', false);
        $('#tags-list, #tags-list-mobile').html('<li><p>Loading tags...</p></li>');
        currentFilterState.subcategoryId = '';
        currentFilterState.subcategoryName = '';
        currentFilterState.tags = [];
        toggleTagsFilter();

        if (categoryId) {
            $.ajax({
                url: "{{ route('shop.subcategories.byCategory') }}",
                method: "GET",
                data: { category_id: categoryId },
                headers: { 'X-CSRF-TOKEN': csrfToken },
                beforeSend: function() {
                    console.log('Starting subcategory fetch for categoryId:', categoryId);
                    $('#subcategory-ul, #subcategory-ul-mobile').html('<li><p>Loading subcategories...</p></li>');
                },
                success: function (response) {
                    console.log('Subcategory fetch success:', response);
                    const $subcategoryUl = $('#subcategory-ul');
                    const $subcategoryUlMobile = $('#subcategory-ul-mobile');
                    if (response.html && response.html.trim() !== '<ul class="list"><li><label class="filter-subcategory-label active"><input type="radio" name="subcategory" class="filter-subcategory" value="" data-name="" checked>All Sub Categories</label></li></ul>') {
                        $subcategoryUl.html(response.html);
                        $subcategoryUlMobile.html(response.html);
                        $('#subcategory-filter, #subcategory-filter-mobile').show();
                        console.log('Subcategory filter shown with content');
                        // Restore subcategory selection if present
                        if (currentFilterState.subcategoryId) {
                            $(`input.filter-subcategory[value="${currentFilterState.subcategoryId}"]`).prop('checked', true).closest('.filter-subcategory-label').addClass('active');
                            fetchTags(currentFilterState.subcategoryId, categoryId);
                        } else {
                            fetchTags(null, categoryId);
                        }
                    } else {
                        $subcategoryUl.html('<li><p>No subcategories available</p></li>');
                        $subcategoryUlMobile.html('<li><p>No subcategories available</p></li>');
                        $('#subcategory-filter, #subcategory-filter-mobile').hide();
                        console.log('Subcategory filter hidden: empty response');
                        fetchTags(null, categoryId);
                    }
                    updateActiveStates();
                    bindFilterEvents();
                },
                error: function (xhr, status, error) {
                    console.error('Subcategories fetch error:', status, error, xhr.responseText);
                    $('#subcategory-ul, #subcategory-ul-mobile').html('<li><p>No subcategories available</p></li>');
                    $('#subcategory-filter, #subcategory-filter-mobile').hide();
                    console.log('Subcategory filter hidden due to error');
                    fetchTags();
                }
            });
        } else {
            $('#subcategory-ul, #subcategory-ul-mobile').html('<li><p>No subcategories available</p></li>');
            $('#subcategory-filter, #subcategory-filter-mobile').hide();
            console.log('Subcategory filter hidden: no categoryId');
            fetchTags();
        }
    }

    function fetchTags(subcategoryId = null, categoryId = null) {
        const data = {};
        if (subcategoryId && subcategoryId !== '') {
            data.subcategory_id = subcategoryId;
            data.category_id = categoryId || currentFilterState.categoryId;
            console.log('Fetching tags for subcategoryId:', subcategoryId, 'categoryId:', data.category_id);
        } else if (categoryId && categoryId !== '') {
            data.category_id = categoryId;
            console.log('Fetching tags for categoryId:', categoryId);
        } else {
            data.no_filter = 1;
            console.log('Fetching tags with no_filter');
        }

        $.ajax({
            url: "{{ route('shop.tags.byCategoryOrSubcategory') }}",
            method: "GET",
            data: data,
            headers: { 'X-CSRF-TOKEN': csrfToken },
            beforeSend: function() {
                console.log('Starting tags fetch with data:', data);
                $('#tags-list, #tags-list-mobile').html('<li><p>Loading tags...</p></li>');
            },
            success: function (response) {
                console.log('Tags fetch success:', response);
                $('#tags-list, #tags-list-mobile').html(response.html || '<li><p>No tags available</p></li>');
                toggleTagsFilter();
                bindFilterEvents();
                // Debug: Log the rendered tags
                console.log('Rendered tags:', $('#tags-list').find('input.filter-tag').map(function() {
                    return $(this).siblings('span').text() || $(this).next().text();
                }).get());
            },
            error: function (xhr, status, error) {
                console.error('Tags fetch error:', status, error, xhr.responseText);
                $('#tags-list, #tags-list-mobile').html('<li><p>No tags available</p></li>');
                toggleTagsFilter();
                console.log('Tags cleared due to error');
            }
        });
    }

    function updateActiveStates() {
        $('.filter-category-label').removeClass('active');
        $('.filter-subcategory-label').removeClass('active');
        $('.filter-sort-label').removeClass('active');
        $('.filter-availability-label').removeClass('active');
        $('.filter-tag-label').removeClass('active');
        $('input.filter-category[name="category"]:checked, input.filter-category[name="category_mobile"]:checked').each(function() {
            $(this).closest('.filter-category-label').addClass('active');
            currentFilterState.categoryId = $(this).val();
            currentFilterState.categoryName = $(this).data('name') ? decodeURIComponent($(this).data('name')) : initialCategoryName;
            console.log('Updated category state:', currentFilterState.categoryId, currentFilterState.categoryName);
        });
        $('input.filter-subcategory[name="subcategory"]:checked, input.filter-subcategory[name="subcategory_mobile"]:checked').each(function() {
            $(this).closest('.filter-subcategory-label').addClass('active');
            currentFilterState.subcategoryId = $(this).val();
            currentFilterState.subcategoryName = $(this).data('name') ? decodeURIComponent($(this).data('name')) : '';
            console.log('Updated subcategory state:', currentFilterState.subcategoryId, currentFilterState.subcategoryName);
        });
        $('input.filter-sort[name="sort_by"]:checked, input.filter-sort[name="sort_by_mobile"]:checked').each(function() {
            $(this).closest('.filter-sort-label').addClass('active');
            currentFilterState.sortBy = $(this).val();
        });
        $('input.filter-availability[name="availability"]:checked, input.filter-availability[name="availability_mobile"]:checked').each(function() {
            $(this).closest('.filter-availability-label').addClass('active');
            currentFilterState.availability = $(this).val();
        });
        $('input.filter-tag[name="tags[]"]:checked').each(function() {
            $(this).closest('.filter-tag-label').addClass('active');
        });
        currentFilterState.tags = $('input.filter-tag[name="tags[]"]:checked').map(function() { return $(this).val(); }).get();
    }

    function bindFilterEvents() {
        $(document).off('change', '.filter-category').on('change', '.filter-category', function () {
            currentFilterState.categoryId = $(this).val();
            currentFilterState.categoryName = $(this).data('name') ? decodeURIComponent($(this).data('name')) : '';
            currentFilterState.subcategoryId = '';
            currentFilterState.subcategoryName = '';
            currentFilterState.tags = [];
            console.log('Category changed:', currentFilterState.categoryId, currentFilterState.categoryName);
            updateActiveStates();
            toggleSubCategoryFilter();
            $('#mobile-filter-sidebar').removeClass('open');
            if (currentFilterState.categoryName) {
                window.history.pushState({}, '', '/category/' + encodeURIComponent(currentFilterState.categoryName.replace(/ /g, '-').toLowerCase()));
                categoryRoute = "{{ route('category.products', ':categoryName') }}".replace(':categoryName', encodeURIComponent(currentFilterState.categoryName.replace(/ /g, '-').toLowerCase()));
            } else {
                window.history.pushState({}, '', '/shop');
                categoryRoute = "{{ route('shop.index') }}";
            }
            debouncedFilterProducts();
        });

        $(document).off('change', '.filter-subcategory').on('change', '.filter-subcategory', function () {
            currentFilterState.subcategoryId = $(this).val();
            currentFilterState.subcategoryName = $(this).data('name') ? decodeURIComponent($(this).data('name')) : '';
            currentFilterState.tags = [];
            $('.filter-tag').prop('checked', false);
            console.log('Subcategory changed:', currentFilterState.subcategoryId, currentFilterState.subcategoryName);
            updateActiveStates();
            fetchTags(currentFilterState.subcategoryId, currentFilterState.categoryId);
            $('#mobile-filter-sidebar').removeClass('open');
            debouncedFilterProducts();
        });

        $(document).off('change', '.filter-sort').on('change', '.filter-sort', function () {
            currentFilterState.sortBy = $(this).val();
            updateActiveStates();
            debouncedFilterProducts();
            $('#mobile-filter-sidebar').removeClass('open');
        });

        $(document).off('change', '.filter-availability').on('change', '.filter-availability', function () {
            currentFilterState.availability = $(this).val();
            updateActiveStates();
            debouncedFilterProducts();
            $('#mobile-filter-sidebar').removeClass('open');
        });

        $(document).off('change', '.filter-tag').on('change', '.filter-tag', function () {
            currentFilterState.tags = $('input.filter-tag[name="tags[]"]:checked').map(function() { return $(this).val(); }).get();
            updateActiveStates();
            debouncedFilterProducts();
            $('#mobile-filter-sidebar').removeClass('open');
        });

        $(document).off('change', '#per_page').on('change', '#per_page', function () {
            debouncedFilterProducts();
            $('#mobile-filter-sidebar').removeClass('open');
        });

        $(document).off('click', '#clear-filters, #clear-filters-mobile').on('click', '#clear-filters, #clear-filters-mobile', function() {
            // Reset category
            $('input.filter-category[name="category"], input.filter-category[name="category_mobile"]').prop('checked', false);
            $('input.filter-category[value=""]').prop('checked', true);
            currentFilterState.categoryId = '';
            currentFilterState.categoryName = '';
            
            // Reset subcategory
            $('input.filter-subcategory[name="subcategory"], input.filter-subcategory[name="subcategory_mobile"]').prop('checked', false);
            $('input.filter-subcategory[value=""]').prop('checked', true);
            currentFilterState.subcategoryId = '';
            currentFilterState.subcategoryName = '';
            
            // Reset tags
            $('input.filter-tag[name="tags[]"]').prop('checked', false);
            currentFilterState.tags = [];
            
            // Reset availability
            $('input.filter-availability[name="availability"], input.filter-availability[name="availability_mobile"]').prop('checked', false);
            $('input.filter-availability[value=""]').prop('checked', true);
            currentFilterState.availability = '';
            
            // Reset sort
            $('input.filter-sort[name="sort_by"], input.filter-sort[name="sort_by_mobile"]').prop('checked', false);
            $('input.filter-sort[value=""]').prop('checked', true);
            currentFilterState.sortBy = '';
            
            // Reset price
            $('.filter-price-min').val({{ $minPrice ?? 0 }});
            $('.filter-price-max').val({{ $maxPrice ?? 1000 }});
            $('#price-range').slider('values', [{{ $minPrice ?? 0 }}, {{ $maxPrice ?? 1000 }}]);
            $('#price-range-label').text(' {{ $minPrice ?? 0 }} -  {{ $maxPrice ?? 1000 }}');
            
            // Update UI and fetch new data
            updateActiveStates();
            toggleSubCategoryFilter();
            $('#mobile-filter-sidebar').removeClass('open');
            debouncedFilterProducts();
        });
    }

    $(document).off('click', '.cart-btn').on('click', '.cart-btn', function (e) {
        e.preventDefault();
        const productId = $(this).data('id');
        $.ajax({
            url: "{{ route('cart.add') }}",
            method: 'POST',
            data: { product_id: productId, quantity: 1 },
            headers: { 'X-CSRF-TOKEN': csrfToken },
            beforeSend: function () { $('#loading-overlay').show(); },
            success: function (response) {
                Swal.fire({
                    title: 'Success!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#71cd14'
                }).then((result) => {
                    if (result.isConfirmed) { debouncedFilterProducts(); }
                });
            },
            error: function (xhr) {
                console.error('Cart AJAX error:', xhr.responseJSON);
                Swal.fire({
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Failed to add product to cart.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#71cd14'
                });
            },
            complete: function () { $('#loading-overlay').hide(); }
        });
    });

    $(document).off('click', '.wishlist-btn').on('click', '.wishlist-btn', function (e) {
        e.preventDefault();
        const productId = $(this).data('id');
        $.ajax({
            url: "{{ route('wishlist.add') }}",
            method: 'POST',
            data: { product_id: productId },
            headers: { 'X-CSRF-TOKEN': csrfToken },
            beforeSend: function () { $('#loading-overlay').show(); },
            success: function (response) {
                Swal.fire({
                    title: response.status ? 'Success!' : 'Note!',
                    text: response.message,
                    icon: response.status ? 'success' : 'info',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#71cd14'
                }).then((result) => {
                    if (result.isConfirmed) { debouncedFilterProducts(); }
                });
            },
            error: function (xhr) {
                console.error('Wishlist AJAX error:', xhr.responseJSON);
                Swal.fire({
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Failed to add product to wishlist.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#71cd14'
                });
            },
            complete: function () { $('#loading-overlay').hide(); }
        });
    });

    function initializePagination() {
        $(document).off('click', '.pagination a').on('click', '.pagination a', function (e) {
            e.preventDefault();
            let href = $(this).attr('href');
            const params = href.split('?')[1] || '';
            const categoryId = currentFilterState.categoryId || 
                $('input.filter-category[name="category"]:checked, input.filter-category[name="category_mobile"]:checked').val();
            const categoryName = currentFilterState.categoryName || 
                ($('input.filter-category[name="category"]:checked, input.filter-category[name="category_mobile"]:checked').data('name') ? 
                decodeURIComponent($('input.filter-category[name="category"]:checked, input.filter-category[name="category_mobile"]:checked').data('name')) : initialCategoryName);
            
            let url = categoryId && categoryName ? 
                "{{ route('category.products', ':categoryName') }}".replace(':categoryName', encodeURIComponent(categoryName.replace(/ /g, '-').toLowerCase())) : 
                "{{ route('shop.index') }}";
            
            if (params) { url += '?' + params; }

            $.ajax({
                url: url,
                method: "GET",
                data: {
                    category: currentFilterState.categoryId,
                    subcategory: currentFilterState.subcategoryId,
                    tags: currentFilterState.tags,
                    availability: currentFilterState.availability,
                    min_price: window.innerWidth <= 991 ? ($('.filter-price-min').val() || {{ $minPrice ?? 0 }}) : $('#price-range').slider('values', 0),
                    max_price: window.innerWidth <= 991 ? ($('.filter-price-max').val() || {{ $maxPrice ?? 1000 }}) : $('#price-range').slider('values', 1),
                    sort_by: currentFilterState.sortBy,
                    per_page: $('#per_page').val() || 12,
                    grid_mode: localStorage.getItem('selectedGridMode') || '{{ request('grid_mode', '4') }}',
                    filter: true
                },
                headers: { 'X-CSRF-TOKEN': csrfToken },
                beforeSend: function () {
                    showSkeleton();
                    $('#loading-overlay').show();
                },
                success: function (response) {
                    setTimeout(function() {
                        $('#product-list').html(response.html || '<p class="text-center">No products found.</p>');
                        updateActiveStates();
                        toggleTagsFilter();
                        bindFilterEvents();
                        $('#loading-overlay').hide();
                        initializePagination();
                        applyGridMode();
                    }, 500);
                },
                error: function (xhr, status, error) {
                    console.error('Pagination AJAX error:', status, error, xhr.responseText);
                    $('#loading-overlay').hide();
                    $('#product-list').html('<p class="text-center">Error loading products. Please try again.</p>');
                    Swal.fire({
                        title: 'Error!',
                        text: xhr.responseJSON?.error || 'Error loading products.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#71cd14'
                    });
                }
            });
        });
    }

    function applyGridMode() {
        $('#product-list').removeClass(function (index, className) {
            return (className.match(/(^|\s)product-cols-\S+/g) || []).join(' ');
        });
        $('#product-list').addClass('product-cols-' + selectedGridMode);
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

    $(document).on('click', '.view-mode-btn', function () {
        selectedGridMode = $(this).data('cols');
        localStorage.setItem('selectedGridMode', selectedGridMode);
        debouncedFilterProducts();
    });

    $(document).on('click', '#open-filter-sidebar', function() {
        $('#mobile-filter-sidebar').addClass('open');
    });

    $(document).on('click', '#close-filter-sidebar', function() {
        $('#mobile-filter-sidebar').removeClass('open');
    });

    $(document).on('click', function(e) {
        if ($(e.target).closest('#mobile-filter-sidebar, #open-filter-sidebar').length === 0) {
            $('#mobile-filter-sidebar').removeClass('open');
        }
    });

    $(document).on('submit', '#mobile-price-filter-form', function(e) {
        e.preventDefault();
        debouncedFilterProducts();
        $('#mobile-filter-sidebar').removeClass('open');
    });

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
        $('#product-list').html(skeletonHtml);
    }

    bindFilterEvents();
    initializePagination();
    applyGridMode();

    @if(isset($selectedCategoryId))
        fetchSubCategories("{{ $selectedCategoryId }}");
        $('#subcategory-filter, #subcategory-filter-mobile').show();
        @if(request('subcategory'))
            fetchTags("{{ request('subcategory') }}", "{{ $selectedCategoryId }}");
        @else
            fetchTags(null, "{{ $selectedCategoryId }}");
        @endif
    @else
        fetchTags();
        $('#subcategory-filter, #subcategory-filter-mobile').hide();
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
    .mobile-filter-sidebar {
        position: fixed;
        top: 0;
        right: -100%;
        width: 300px;
        height: 100%;
        background: #fff;
        z-index: 1000;
        transition: right 0.3s ease;
        overflow-y: auto;
    }
    .mobile-filter-sidebar.open { right: 0; }
    .ui-slider .ui-slider-handle { width: 20px; height: 20px; border-radius: 50%; background: #71cd14; border: 2px solid #fff; }
    .ui-slider { background: #f0f0f0; height: 8px; border-radius: 4px; }
    .ui-slider-range { background: #71cd14; }
    .filter-tag-label.active { font-weight: bold; color: #71cd14; }
</style>

@if (!request()->ajax())
    @endsection
@endif