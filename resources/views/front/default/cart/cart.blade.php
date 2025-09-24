@extends('front.default.partials.app')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/cart.css') }}" />

<!-- Mobile Header Banner -->
<div class="mobile-header-banner " style="background: linear-gradient(90deg, #ffc107 0%, #ffeb3b 100%); padding: 8px 0;">
    <marquee behavior="scroll" direction="left" scrollamount="6">
        <small class="fw-bold text-dark me-5">FREE DELIVERY All OVER PAKISTAN</small>
        <small class="fw-bold text-dark">FASTEST ORDER PROCESSING</small>
    </marquee>
</div>


<!-- Desktop Header -->
<section class="banner_area ">
    <div class="banner_inner d-flex align-items-center" style="background:linear-gradient(90deg,#71cd14 0%,#eafbe2 100%);min-height:120px;">
        <div class="container">
            <h2 class="text-center fw-bold" style="color:#fff;">Your Cart</h2>
        </div>
    </div>
</section>

<!-- Free Shipping Progress -->
<div class="free-shipping-progress mt-3 position-relative" style="height: 40px;">
    <div class="progress" style="height: 8px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 100%;"></div>
    </div>
    <div class="text-success small mt-1 justify-content-end d-flex">
        <h6 class="mb-0">You qualify for free shipping!</h6>
    </div>
    <div class="truck-moving">🚚</div>
</div>

<style>
/* Inline styles remain unchanged */
</style>

<section class="cart_area py-5" >
    <div class="container-fluid px-lg-5 px-2">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body">
                        @if($items->count())
                            <div class="alert alert-warning d-flex align-items-center mt-3">
                                <i class="bi bi-info-circle-fill me-2"></i>
                               <span>
    ⏳ Hurry! Some items in your cart are in high demand and will only be reserved for a <strong>short time</strong>. 
    Complete your purchase within the next few minutes before they’re released to other shoppers. 
    Note: Your cart will be automatically cleared after 45 minutes.
</span>

                            </div>

                            <!-- Desktop Table View -->
                            <div class="table-responsive d-none d-lg-block">
                                <table class="table align-middle mb-0" id="cartTable">
                                    <thead class="">
                                        <tr>
                                            <th>Product</th>
                                            <th>Image</th>
                                            <th>Price</th>
                                            <th style="width:180px;">Quantity</th>
                                            <th>Total</th>
                                            <th>Item Expire</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                            @php
                                                $cartBasePrice = $item->price; // Base price from cart table
                                                $productBasePrice = $item->product->price; // Base price from product table
                                                $total = $cartBasePrice * $item->quantity;
                                                if ($item->selected_options && count($item->selected_options) > 0) {
                                                    $optionValue = array_sum(array_column($item->selected_options, 'value'));
                                                    $total += $optionValue * $item->quantity;
                                                }
                                            @endphp
                                            <tr data-item="{{ $item->id }}" data-created="{{ $item->created_at ?? now()->setTimezone('Asia/Karachi')->toISOString() }}" data-base-price="{{ $cartBasePrice }}" data-options="{{ json_encode($item->selected_options ?? []) }}" data-total="{{ $total }}">
                                                <td>
                                                    <a href="{{ route('product.details', $item->product->slug) }}" class="fw-semibold text-dark">
                                                        {{ $item->product->name }}
                                                    </a>
                                                
                                                </td>
                                               @php
    $imagePath = $item->product->galleries->first()->image ?? 'default.jpg';

    if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
        $finalImage = $imagePath; // ✅ External URL
    } elseif (\Illuminate\Support\Facades\Storage::exists($imagePath)) {
        $finalImage = Storage::url($imagePath); // ✅ Storage
    } else {
        $finalImage = asset($imagePath); // ✅ Public or default
    }
@endphp

<td>
    <img src="{{ $finalImage }}"
         alt="{{ $item->product->name }}"
         class="rounded shadow-sm"
         style="height:auto; width:100px;">
</td>

                                                <td>
                                                    <span class="fw-bold price" style="color:#71cd14;">Rs{{ number_format($cartBasePrice, 2) }}</span>
                                                </td>
                                                <td>
                                                    <div class="quantity-wrapper d-flex align-items-center">
                                                        <a href="#" class="btn btn-outline-success btn-sm quantity-btn" data-action="decrease" data-id="{{ $item->id }}" style="border-color:#71cd14; color:#71cd14;">−</a>
                                                        <input type="text" readonly name="quantity" value="{{ $item->quantity }}" class="form-control text-center mx-1 quantity-input" style="width: 45px;" data-id="{{ $item->id }}">
                                                        <a href="#" class="btn btn-outline-success btn-sm quantity-btn" data-action="increase" data-id="{{ $item->id }}" style="border-color:#71cd14; color:#71cd14;">+</a>
                                                        <a href="#" class="btn btn-outline-danger btn-sm ms-2 rounded-pill remove-cart-item" data-id="{{ $item->id }}">
                                                            <i class="bi bi-trash"></i>
                                                        </a>
                                                     
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="fw-bold item-total">Rs{{ number_format($total, 2) }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning text-dark fw-semibold timer-message" id="timer-{{ $item->id }}">⏳ Loading...</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Card View -->
                            <div class="d-lg-none">
                                @foreach($items as $item)
                                   
                                    <div class="card mb-3 cart-item-card" data-item="{{ $item->id }}" data-created="{{ $item->created_at ?? now()->setTimezone('Asia/Karachi')->toISOString() }}" data-base-price="{{ $cartBasePrice }}" data-options="{{ json_encode($item->selected_options ?? []) }}" data-total="{{ $total }}">
                                        <div class="card-body">
                                            <div class="row">
                                               @php
    $imagePath = $item->product->galleries->first()->image ?? 'default.jpg';

    if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
        $finalImage = $imagePath; // ✅ External URL
    } elseif (\Illuminate\Support\Facades\Storage::exists($imagePath)) {
        $finalImage = Storage::url($imagePath); // ✅ Storage
    } else {
        $finalImage = asset($imagePath); // ✅ Public or default
    }
@endphp

<div class="col-4">
    <img src="{{ $finalImage }}" 
         class="img-fluid rounded" 
         style="height: 120px; width: 100%; object-fit: cover;" 
         alt="{{ $item->product->name }}">
</div>

                                                <div class="col-8">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="card-title mb-1">
                                                            <a href="{{ route('product.details', $item->product->slug) }}" class="text-dark text-decoration-none">
                                                                {{ $item->product->name }}
                                                            </a>
                                                        </h6>
                                                        <a href="#" class="btn btn-sm btn-outline-danger remove-cart-item" data-id="{{ $item->id }}">
                                                            <i class="bi bi-x"></i>
                                                        </a>
                                                    </div>
                                                  
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <div class="price-section">
                                                            <span class="fw-bold price" style="color:#71cd14; font-size: 1.1rem;">Rs{{ number_format($cartBasePrice, 2) }}</span>
                                                        </div>
                                                        <div class="quantity-section">
                                                            <div class="d-flex align-items-center">
                                                                <a href="#" class="btn btn-outline-success btn-sm quantity-btn" data-action="decrease" data-id="{{ $item->id }}" style="border-color:#71cd14; color:#71cd14; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">−</a>
                                                                <input type="text" readonly name="quantity" value="{{ $item->quantity }}" class="form-control text-center mx-1 quantity-input" style="width: 50px; height: 32px;" data-id="{{ $item->id }}">
                                                                <a href="#" class="btn btn-outline-success btn-sm quantity-btn" data-action="increase" data-id="{{ $item->id }}" style="border-color:#71cd14; color:#71cd14; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">+</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="total-section">
                                                            <strong>Total: <span class="item-total" style="color:#71cd14;">Rs{{ number_format($total, 2) }}</span></strong>
                                                        </div>
                                                        <div class="timer-section">
                                                        </div>
                                                    </div>
                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="row mt-4">
                                <div class="col-lg-6 col-md-8 col-12">
                                    <div class="cart-note p-4 rounded-3 mb-3" >
                                        <h5 class="mb-3 fw-bold" style="color:#71cd14;">Order Note <small class="text-muted">(Max 1000 characters)</small></h5>
                                        <form id="orderNoteForm">
                                            <textarea id="orderNote" name="order_note" class="form-control mb-2" maxlength="1000" placeholder="Write your order note...">{{ session('order_note') }}</textarea>
                                            <small class="text-muted d-block text-end">
                                                Characters: <span id="charCount">0</span>/1000
                                            </small>
                                            <button type="submit" id="saveNoteBtn" class="btn btn-outline-success rounded-pill mt-2" style="color:#71cd14;border-color:#71cd14;">Save Note</button>
                                        </form>
                                        <div id="savedOrderNote" class="d-flex justify-content-between align-items-center mt-3" style="@if(!session('order_note'))display: none;@endif  padding: 10px 15px; border-radius: 10px;">
                                            <span class="text-dark fw-semibold" id="noteContent">{{ session('order_note') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-8 col-12 ms-auto">
                                    <div class="cart-summary p-4 rounded-3" >
                                        <h5 class="mb-3 fw-bold" style="color:#71cd14;">Cart Summary</h5>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fw-semibold">Subtotal:</span>
                                            <span class="fw-bold" id="cartSubtotal">Rs{{ number_format($items->sum(fn($i) => $i->price * $i->quantity + (count($i->selected_options ?? []) > 0 ? array_sum(array_column($i->selected_options, 'value')) * $i->quantity : 0)), 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="fw-semibold">Shipping:</span>
                                            <span class="fw-bold">Free</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between fs-5 fw-bold">
                                            <span>Total:</span>
                                            <span style="color:#71cd14;" id="cartTotal">Rs{{ number_format($items->sum(fn($i) => $i->price * $i->quantity + (count($i->selected_options ?? []) > 0 ? array_sum(array_column($i->selected_options, 'value')) * $i->quantity : 0)), 2) }}</span>
                                        </div>
<form id="checkoutForm" action="{{ route('checkout.saveTotal') }}" method="POST">
    @csrf
    <input type="hidden" name="total" id="totalInput" value="">

    <button type="submit" class="btn btn-success w-100 mt-3 rounded-pill fw-bold no-dark3"
        style="background:#71cd14;border:none;">
        Proceed to Checkout
    </button>
</form>




                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" width="150" class="mb-3" alt="Empty Cart">
                                <h4 class="fw-bold text-muted">Your cart is empty.</h4>
                                <a href="{{ route('shop.index') }}" class="btn btn-outline-success rounded-pill mt-3" style="color:#71cd14;border-color:#71cd14;">Continue Shopping</a>
                            </div>
                        @endif

                        {{-- Explore More Section --}}
                        @php
                            $cartProductIds = $items->pluck('product_id')->toArray();
                            $latestProducts = \App\Models\Product::latest()
                                ->whereNotIn('id', $cartProductIds)
                                ->take(4)
                                ->get();
                        @endphp

                        <div class="explore-more-section mt-5">
                            <div class="row">
                                <h3 class="text-center mb-4 fw-bold" style="color:#71cd14;">Explore More</h3>
                                @foreach($latestProducts as $product)
                                    <div class="col-lg-3 col-md-6 col-12 mb-3">
                                        <a href="{{ route('product.details', $product->slug) }}" class="text-decoration-none">

                                        <div class="product-card h-100">
                                            <div class="position-relative">
                                                                                                            @php
                                                                $imagePath = $product->galleries->first()->image ?? 'default.jpg';

                                                                if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                                                                    $finalImage = $imagePath; // ✅ External URL
                                                                } elseif (\Illuminate\Support\Facades\Storage::exists($imagePath)) {
                                                                    $finalImage = Storage::url($imagePath); // ✅ Storage path
                                                                } else {
                                                                    $finalImage = asset($imagePath); // ✅ Public path or default
                                                                }
                                                            @endphp

                                                            <img src="{{ $finalImage }}" 
                                                                class="img-fluid rounded" 
                                                                style="height: 200px; width: 100%; object-fit: cover;" 
                                                                alt="{{ $product->name }}">

                                                @if($product->discount > 0)
                                                    <span class="badge bg-danger position-absolute top-0 end-0 m-2">Sale {{ $product->discount }}%</span>
                                                @endif
                                                <button class="btn btn-sm btn-outline-success position-absolute bottom-0 start-0 m-2 quick-add-btn" 
                                                        data-product="{{ $product->id }}"
                                                        style="border-color:#71cd14; color:#71cd14;">
                                                    Quick Add
                                                </button>
                                            </div>
                                            <div class="p-3 no-dark">
                                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem; line-height: 1.3;">
                                                    {{ Str::limit($product->name, 50) }}
                                                </h6>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    @if($product->discount > 0)
                                                        <span class="text-muted text-decoration-line-through small">Rs{{ number_format($product->price, 2) }}</span>
                                                        <span class="fw-bold text-success">from Rs{{ number_format($product->price - ($product->price * $product->discount / 100), 2) }}</span>
                                                    @else
                                                        <span class="fw-bold text-success">Rs{{ number_format($product->price, 2) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                                            </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-lg-8 col-12 ms-auto">
                            <div class="delivery-msg">
                                <h3>
                                    <b>We deliver your products fast with our trusted partners:</b>
                                </h3>
                            </div>
                            <div class="delivery-partners mt-3">
                                <img style="height:150px;width:150px;margin:auto;" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRR203e9brGJnDpRmOaxWLmLVGATEECdqWTNQ&s" alt="TCS">
                                <img style="height:150px;width:150px;margin:auto;" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRhaaOTN5aYYYYpJ98BCyHKdrB8OMe3xIDDgg&s" alt="Leopard Courier">
                                <img style="height:150px;width:150px;margin:auto;" src="https://mulphilog.com.pk/uploads/2017/08/logo.png" alt="BlueEx">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.43/moment-timezone-with-data.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function updateCartTotals() {
    let subtotal = 0;
    const isDesktopView = window.matchMedia("(min-width: 992px)").matches;
    const selector = isDesktopView ? '#cartTable tbody tr' : '.cart-item-card';


    // Reset all item totals before recalculation
    $(selector).find('.item-total').text('Rs0.00');

    $(selector).each(function () {
        const itemId = $(this).data('item');
        const basePrice = parseFloat($(this).data('base-price')) || 0;
        const quantity = parseInt($(this).find('.quantity-input').val()) || 1;
        let options = [];

        // Get raw data-options attribute and parse it safely
        const optionsAttr = $(this).attr('data-options');
        try {
            options = optionsAttr ? JSON.parse(optionsAttr) : [];
        } catch (e) {
            console.warn('Invalid JSON in data-options for item:', itemId, 'Value:', optionsAttr, 'Error:', e.message);
            options = [];
        }

        const optionValue = options.length > 0 ? arraySum(options.map(opt => parseFloat(opt.value) || 0)) : 0;
        const total = (basePrice * quantity) + (optionValue * quantity);

        $(this).find('.item-total').text('Rs' + total.toFixed(2));
        $(this).data('total', total);
        subtotal += total;
    });

    $('#cartSubtotal, #cartTotal').text('Rs' + subtotal.toFixed(2));
}

function arraySum(array) {
    return array.reduce((sum, value) => sum + value, 0);
}

$(document).ready(function () {
    // Initialize cart totals
    updateCartTotals();

    // Quantity button handlers
    $(document).on('click', '.quantity-btn', function (e) {
        e.preventDefault();
        const btn = $(this);
        const action = btn.data('action');
        const itemId = btn.data('id');
        const input = $('.quantity-input[data-id="' + itemId + '"]');
        let quantity = parseInt(input.val()) || 1;
        if (action === 'increase') quantity++;
        if (action === 'decrease' && quantity > 1) quantity--;
        input.val(quantity).trigger('change');
    });

    $(document).on('change', '.quantity-input', function () {
        const input = $(this);
        const itemId = input.data('id');
        const quantity = Math.max(1, parseInt(input.val()));
        const container = input.closest('tr, .cart-item-card');
        const basePrice = parseFloat(container.data('base-price')) || 0;
        let options = [];

        const optionsAttr = container.attr('data-options');
        try {
            options = optionsAttr ? JSON.parse(optionsAttr) : [];
        } catch (e) {
            console.warn('Invalid JSON in data-options for item:', itemId, 'Value:', optionsAttr, 'Error:', e.message);
            options = [];
        }

        input.val(quantity);
        const optionValue = options.length > 0 ? arraySum(options.map(opt => parseFloat(opt.value) || 0)) : 0;
        const total = (basePrice * quantity) + (optionValue * quantity);
        container.find('.item-total').text('Rs' + total.toFixed(2));
        container.data('total', total);
        updateCartTotals();

      $.ajax({
    url: "{{ route('cart.update', ['item' => ':id']) }}".replace(':id', itemId),
    type: 'POST',
    data: {
        quantity: quantity,
        _token: '{{ csrf_token() }}'
    },
    success: function (res) {
        if (res.status) {
            // do your update logic here
        }
    },
    error: function () {
        Swal.fire('Error', 'Failed to update quantity', 'error');
    }
});

    });

    // Remove item from cart
    $(document).on('click', '.remove-cart-item', function (e) {
        e.preventDefault();
        const itemId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to remove this item from your cart.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#71cd20',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
             $.ajax({
    url: "{{ route('cart.remove', ['item' => ':id']) }}".replace(':id', itemId),
    type: 'POST',
    data: { _token: '{{ csrf_token() }}' },
    success: function (res) {
        if (res.status) {
            Swal.fire({
                title: 'Removed!',
                text: 'Item has been removed.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    },
    error: function () {
        Swal.fire('Error', 'Failed to remove item', 'error');
    }
});

            }
        });
    });

    // Order note handling
    $('#orderNoteForm').on('submit', function (e) {
        e.preventDefault();
        const note = $('#orderNote').val();
        if (note.length > 1000) return;

        $.ajax({
            url: '{{ route('cart.saveNote') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                order_note: note
            },
            success: function (res) {
                if (res.status) {
                    $('#noteContent').text(res.note);
                    $('#savedOrderNote').show();
                }
            },
            error: function () {
                Swal.fire('Error', 'Failed to save note', 'error');
            }
        });
    });

    $('#orderNote').on('input', function () {
        const count = $(this).val().length;
        $('#charCount').text(count);
        if (count > 1000) {
            $('#saveNoteBtn').prop('disabled', true).text('Limit Exceeded');
        } else {
            $('#saveNoteBtn').prop('disabled', false).text('Save Note');
        }
    });

    $('#orderNote').trigger('input');

    // Options modal functionality
    let currentEditingItemId = null;
    let currentBasePrice = 0;

    $(document).on('click', '.edit-options', function (e) {
        e.preventDefault();
        const itemId = $(this).data('id');
        const productId = $(this).data('product');
        const selectedOptions = $(this).data('selected') || [];
        currentBasePrice = parseFloat($(this).data('base-price')); // Use cart base price initially
        const productBasePrice = parseFloat($(this).data('product-base-price')); // Store product base price
        currentEditingItemId = itemId;

        $.ajax({
    url: "{{ route('api.product.options', ['id' => ':id']) }}".replace(':id', productId),
    type: 'GET',
    success: function (res) {
        if (res.status) {
            let optionsHtml = '<div class="mb-3"><label><strong>Options:</strong></label><div class="d-block">';
            const hasSelectedOptions = selectedOptions.length > 0;
            optionsHtml += `
                <label class="btn btn-outline-dark option-label mb-1 ${!hasSelectedOptions ? 'active' : ''}">
                    <input type="radio" name="product_option" class="option-input d-none" 
                           data-price="0" 
                           data-key="No Options"
                           value="none" 
                           ${!hasSelectedOptions ? 'checked' : ''}
                           data-product-base-price="${productBasePrice}">
                    <span>No Options (Base Price: Rs${productBasePrice.toFixed(2)})</span>
                </label>
            `;
            res.options.forEach(function (option) {
                const isSelected = selectedOptions.some(selected => selected.id == option.id);
                const priceText = option.value > 0 ? ` (+Rs${option.value})` : '';
                optionsHtml += `
                    <label class="btn btn-outline-dark option-label mb-1 ${isSelected ? 'active' : ''}">
                        <input type="radio" name="product_option" class="option-input d-none" 
                               data-price="${option.value || 0}" 
                               data-key="${option.key}"
                               value="${option.id}" 
                               ${isSelected ? 'checked' : ''}>
                        <span>${option.key}${priceText} (Base Price: Rs${currentBasePrice.toFixed(2)})</span>
                    </label>
                `;
            });
            optionsHtml += '</div></div>';
            optionsHtml += '<div class="price-display"><strong>Updated Price: </strong><span id="modalPrice">Rs' + (currentBasePrice).toFixed(2) + '</span></div>';
            $('#optionsContainer').html(optionsHtml);
            $('#optionsModal').modal('show');
            updateModalPrice(itemId);
        }
    },
    error: function () {
        Swal.fire('Error', 'Failed to load options', 'error');
    }
});

    });

    function updateModalPrice(itemId) {
        const container = $('#timer-' + itemId).closest('tr, .cart-item-card');
        const quantity = parseInt(container.find('.quantity-input').val()) || 1;
        let totalPrice = parseFloat(container.data('base-price')) * quantity; // Start with cart base price
        let selectedOptions = [];

        $('#optionsModal .option-input:checked').each(function () {
            const optionPrice = parseFloat($(this).data('price')) || 0;
            const optionKey = $(this).data('key');
            if ($(this).val() === 'none') {
                totalPrice = parseFloat($(this).data('product-base-price')) * quantity; // Switch to product base price
            } else {
                totalPrice += optionPrice * quantity; // Add option value
            }
            selectedOptions.push({
                id: $(this).val(),
                key: optionKey,
                value: optionPrice
            });
        });

        $('#modalPrice').text('Rs' + totalPrice.toFixed(2));
        return selectedOptions;
    }

    $(document).on('change', '#optionsModal .option-input', function () {
        $('#optionsModal .option-label').removeClass('active');
        $('#optionsModal .option-input:checked').each(function () {
            $(this).closest('.option-label').addClass('active');
        });
        if (currentEditingItemId) {
            updateModalPrice(currentEditingItemId);
        }
    });

    $('#clearAllOptions').on('click', function () {
        $('#optionsModal .option-input').prop('checked', false);
        $('#optionsModal .option-label').removeClass('active');
        if (currentEditingItemId) {
            updateModalPrice(currentEditingItemId);
        }
    });

    $('#saveOptions').on('click', function () {
        if (!currentEditingItemId) return;

        const selectedOptions = updateModalPrice(currentEditingItemId);
        const container = $('#timer-' + currentEditingItemId).closest('tr, .cart-item-card');
        const quantity = parseInt(container.find('.quantity-input').val()) || 1;
        let basePrice = parseFloat(container.data('base-price')) || 0;
        const productBasePrice = parseFloat(container.data('product-base-price')) || 0;
        const optionValue = selectedOptions.length > 0 && selectedOptions[0].id !== 'none' ? arraySum(selectedOptions.map(opt => parseFloat(opt.value) || 0)) : 0;

        // Update base price to product base price if "No Options" is selected
        if (selectedOptions.length === 1 && selectedOptions[0].id === 'none') {
            basePrice = productBasePrice;
            container.attr('data-base-price', basePrice);
            container.find('.price').text('Rs' + basePrice.toFixed(2));
        }

        const total = (basePrice * quantity) + (optionValue * quantity);

        $('#saveOptions').prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-2"></i>Saving...');

      $.ajax({
    url: "{{ route('cart.update', ['item' => ':id']) }}".replace(':id', currentEditingItemId),
    type: 'POST',
    data: {
        options: selectedOptions
            .map(opt => opt.id === 'none' ? null : opt.id)
            .filter(id => id !== null),
        _token: '{{ csrf_token() }}'
    },
    success: function (res) {
        if (res.status) {
            $('#optionsModal').modal('hide');
            container.find('.item-total').text('Rs' + total.toFixed(2));
            container.attr('data-options', JSON.stringify(selectedOptions));
            container.data('total', total);
            updateCartTotals();

            const message = selectedOptions.length > 0 && selectedOptions[0].id !== 'none'
                ? 'Options updated successfully!'
                : 'Options cleared successfully!';

            Swal.fire('Success', message, 'success').then(() => {
                location.reload();
            });
        }
    },
    error: function () {
        Swal.fire('Error', 'Failed to update options', 'error');
    },
    complete: function () {
        $('#saveOptions').prop('disabled', false).text('Save Changes');
    }
});

    });

    // Quick Add functionality
    $(document).on('click', '.quick-add-btn', function (e) {
        e.preventDefault();
        const productId = $(this).data('product');

        $.ajax({
            url: "{{ route('cart.add') }}",
            type: "POST",
            data: {
                product_id: productId,
                quantity: 1,
                _token: "{{ csrf_token() }}"
            },
            success: function (res) {
                if (res.status) {
                    Swal.fire('Success', res.message, 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    Swal.fire('Error', res.message || 'Something went wrong.', 'error');
                }
            },
            error: function () {
                Swal.fire('Error', 'Failed to add to cart.', 'error');
            }
        });
    });

    // Timer functionality
    function startCountdownTimers() {
        let timersInitialized = false;

        function updateAllTimers() {
            const now = moment().tz('Asia/Karachi');
            const isMobileView = !window.matchMedia("(min-width: 992px)").matches;

            $('#cartTable tbody tr:visible, .cart-item-card:visible').each(function () {
                updateTimerForItem($(this), now);
                timersInitialized = true;
            });

            if (!timersInitialized) {
                console.warn('No timers initialized in this cycle');
            }
        }

        function updateTimerForItem(container, now) {
            try {
                const itemId = container.data('item');
                const createdAt = container.data('created');
                const timerElement = $('#timer-' + itemId);

                if (timerElement.hasClass('timer-updated')) {
                    return;
                }


                if (!createdAt || !timerElement.length) {
                    console.warn('Skipping timer for item:', itemId, 'Created At:', createdAt, 'Timer Element:', timerElement.length);
                    if (timerElement.length) {
                        timerElement.html('⛔ No timer data');
                    }
                    return;
                }

                let createdTime = moment.tz(createdAt, 'Asia/Karachi');
                if (!createdTime.isValid()) {
                    console.warn('Invalid createdAt for item:', itemId, 'Value:', createdAt, 'Attempting fallback parsing');
                    createdTime = moment(createdAt);
                    if (!createdTime.isValid()) {
                        console.error('Fallback parsing failed for item:', itemId, 'Value:', createdAt);
                        timerElement.html('⛔ Invalid timer data');
                        return;
                    }
                }


                const expireTime = createdTime.clone().add(45, 'minutes');
                const remaining = expireTime.diff(now, 'milliseconds');


                if (remaining <= 0) {
    timerElement.html('⏳ Time expired! Item will be removed...');
    container.fadeOut(1000, function () {
        $.ajax({
            url: "{{ route('cart.remove', ['item' => ':id']) }}".replace(':id', itemId),
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                if (res.status) {
                    container.remove();
                    updateCartTotals();
                    Swal.fire('Removed', 'Item has been removed due to expired timer.', 'info');
                }
            },
            error: function () {
                Swal.fire('Error', 'Failed to remove expired item', 'error');
            }
        });
    });
    return;
}


                const mins = Math.floor(remaining / 60000);
                const secs = Math.floor((remaining % 60000) / 1000);
                const msg = mins < 1 ? "⏰ Hurry! Few seconds left!" : `⏳ ${mins}m ${secs}s left`;

                timerElement.addClass('timer-updated');
                setTimeout(() => {
                    const currentText = timerElement.text();
                    timerElement.html(msg);
                    const newText = timerElement.text();
                    if (newText !== msg) {
                        console.error('DOM update failed for Item ID:', itemId, 'Expected:', msg, 'Got:', newText);
                        timerElement.html(msg);
                    }
                }, 100);
            } catch (error) {
                console.error('Error in updateTimerForItem for Item ID:', container.data('item'), 'Error:', error.message);
                const timerElement = $('#timer-' + container.data('item'));
                if (timerElement.length) {
                    timerElement.html('⛔ Timer error');
                }
            }
        }

        updateAllTimers();
        const timerInterval = setInterval(updateAllTimers, 1000);

        setTimeout(function () {
            if (!timersInitialized) {
                console.warn('No timers initialized after 30 seconds, stopping interval');
                clearInterval(timerInterval);
            }
        }, 30000);
    }

    function initializeTimers() {
        let retryCount = 0;
        const maxRetries = 10;
        const retryInterval = 1000;

        function tryInitialize() {
            const desktopItems = $('#cartTable tbody tr');
            const mobileItems = $('.cart-item-card');
            const isMobileView = !window.matchMedia("(min-width: 992px)").matches;

            if (desktopItems.length > 0 || mobileItems.length > 0) {
                startCountdownTimers();
            } else if (retryCount < maxRetries) {
                retryCount++;
                console.warn('No cart items found, retrying...');
                setTimeout(tryInitialize, retryInterval);
            } else {
                console.error('Max retries reached, no cart items found');
                $('.timer-message').each(function () {
                    console.warn('Timer not initialized for element:', $(this).attr('id'));
                    $(this).html('⏳ Timer unavailable');
                });
            }
        }

        tryInitialize();

        const observer = new MutationObserver(function (mutations) {
            if ($('#cartTable tbody tr').length > 0 || $('.cart-item-card').length > 0) {
                startCountdownTimers();
                observer.disconnect();
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        setTimeout(function () {
            observer.disconnect();
        }, 30000);
    }

    $(document).ready(function () {
        initializeTimers();
    });
});
</script>

<!-- Options Edit Modal -->
<div class="modal fade" id="optionsModal" tabindex="-1" aria-labelledby="optionsModalLabel" aria-hidden="true" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="optionsModalLabel">Select Product Options</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-3">
                    <i class="bi bi-info-circle me-2 no-dark"></i>
                    <small class="no-dark">Select the options you want for this product. You can select multiple options or none at all.</small>
                </div>
                <div class="no-dark" id="optionsContainer">
                    <!-- Options will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" id="clearAllOptions">Clear All</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="saveOptions">Save Changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('checkoutForm').addEventListener('submit', function (e) {
        const totalText = document.getElementById('cartTotal').innerText;
        const totalValue = parseFloat(totalText.replace('Rs', '').replace(/,/g, '').trim());
        document.getElementById('totalInput').value = totalValue;
    });
</script>
@endsection