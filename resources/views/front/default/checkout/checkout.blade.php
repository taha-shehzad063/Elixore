@extends('front.default.partials.app')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}" />
<style>
.color-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 2px solid #fff; /* default border */
    display: inline-block;
    transition: transform 0.2s, border-color 0.2s;
}

.color-circle.selected {
    border: 2px solid #71b43c; /* green border to indicate selection */
}


/* Hide original <select> */
.new-loader-overlay {
    position: fixed;
    top: 50%;
    left: 50%;
    width: 100%;
    height: 100%;
    display: none; /* Ensure loader is hidden by default */
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.new-loader {
    border: 4px solid #f3f3f3;
    border-top: 4px solid #71cd14;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: rotateLoader 1s linear infinite;
}
@keyframes rotateLoader {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
<div class="checkout-wrapper">
    <!-- Loader Overlay -->
    <div class="new-loader-overlay" id="newLoader">
        <div class="new-loader"></div>
    </div>
    <div class="checkout-main">
        <div class="checkout-title">Checkout</div>
        <form id="checkoutForm">
            @csrf
            <div class="checkout-label">Contact</div>
            <div style="display: flex; gap: 12px;">
                <input type="text" class="checkout-input" name="email" placeholder="Enter Your Email" required>
            </div>
            <!-- Delivery Address -->
            <div class="mb-4">
                <div class="checkout-label">Delivery Address</div>
                <div style="display: flex; gap: 12px;">
                    <input type="text" class="checkout-input" name="shipping_address[name]" placeholder="Full Name" required>
                    <input type="text" class="checkout-input" name="shipping_address[phone]" placeholder="Phone Number" required>
                </div>
                <input type="text" class="checkout-input" name="shipping_address[address]" placeholder="Street Address" required>
                <input type="text" class="checkout-input" name="shipping_address[state]" placeholder="State" required>
                <input type="text" class="checkout-input" name="shipping_address[apartment]" placeholder="Apartment, suite, etc. (optional)">
                <div style="display: flex; gap: 12px;">
                    <input type="text" class="checkout-input" name="shipping_address[city]" placeholder="City" required>
                    <input type="text" class="checkout-input" name="shipping_address[zip]" placeholder="Postal code (optional)">
                </div>
                <input type="text" class="checkout-input" name="shipping_address[country]" placeholder="Country" value="Pakistan" readonly required>
            </div>
            <!-- Shipping Method -->
            <div class="mb-4">
                <div class="checkout-label">Shipping Method</div>
                @php
                    $firstShipping = true;
                    $shippingIndex = 0;
                @endphp
                @foreach($checkoutOptions as $option)
                    @if($option->type === 'shipping')
                        @php $shippingIndex++; @endphp
                        <div class="shipping-method-box {{ $firstShipping ? 'selected' : '' }} {{ $shippingIndex == 1 ? 'first-shipping-option' : '' }}"
                             @if($shippingIndex == 1 && $cartSubtotal < 3000) style="display:none;" @endif>
                            <label class="checkout-radio-label" style="display:flex; align-items:center;">
                                <input
                                    type="radio"
                                    name="shipping_method"
                                    value="{{ $option->key }}"
                                    data-shipping-cost="{{ $option->shipping_cost }}"
                                    {{ ($firstShipping && $cartSubtotal >= 3000) ? 'checked' : '' }}>
                                <span style="margin-left: 10px;">{{ $option->key }} ({{ $option->message }})</span>
                                <span style="margin-left:auto; font-weight:600; color:{{ $option->shipping_cost == 0 ? '#71cd14' : '#222' }}">
                                    {{ $option->shipping_cost == 0 ? 'FREE' : 'Rs ' . number_format($option->shipping_cost, 2) }}
                                </span>
                            </label>
                        </div>
                        @php $firstShipping = false; @endphp
                    @endif
                @endforeach
            </div>
            <!-- Payment Method -->
            <div class="mb-4">
                <div class="checkout-label">
                    {{ $checkoutOptions->where('type', 'payment')->first()?->label ?? 'No Payment Method' }}
                </div>
                @php $firstPayment = true; @endphp
                @foreach($checkoutOptions as $option)
                    @if($option->type === 'payment')
                        <div class="payment-method-box {{ $firstPayment ? 'selected' : '' }}">
                            <label class="checkout-radio-label" style="display: flex; align-items: center;">
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    value="{{ $option->key }}" 
                                    {{ $firstPayment ? 'checked' : '' }}>
                                <span style="margin-left: 10px;">{{ $option->key }}</span>
                            </label>
                            <div class="payment-details" style="font-size: 0.95em; color: #888; margin-left: 28px; margin-top: 4px; display: none;">
                                @if(!empty($option->bank_name))
                                    Bank Name: {{ $option->bank_name }}<br>
                                @endif
                                @if(!empty($option->account_name))
                                    Account Name: {{ $option->account_name }}<br>
                                @endif
                                @if(!empty($option->account_number))
                                    Account Number: {{ $option->account_number }}<br>
                                @endif
                                @if(!empty($option->message))
                                    <span style="color:#71cd14;">{{ $option->message }}</span>
                                @endif
                            </div>
                        </div>
                        @php $firstPayment = false; @endphp
                    @endif
                @endforeach
            </div>
            <!-- Billing Address -->
            <input type="hidden" name="use_billing_address" id="useBillingAddress" value="0">
            <div class="mb-4">
                <div class="checkout-label">Billing Address</div>
                <div class="billing-method-box-group">
                    <div class="billing-method-box selected">
                        <label class="checkout-radio-label">
                            <input type="radio" name="address_option" value="same" checked>
                            Same as shipping address
                        </label>
                    </div>
                    <div class="billing-method-box">
                        <label class="checkout-radio-label">
                            <input type="radio" name="address_option" value="billing">
                            Use a different billing address
                        </label>
                    </div>
                </div>
                <div id="billingAddressFields" style="display:none;">
                    <div class="billing-fields-box">
                        <div style="display: flex; gap: 12px;">
                            <input type="text" class="checkout-input" name="billing_address[name]" placeholder="Full Name">
                            <input type="text" class="checkout-input" name="billing_address[phone]" placeholder="Phone Number">
                        </div>
                        <input type="text" class="checkout-input" name="billing_address[address]" placeholder="Street Address">
                        <input type="text" class="checkout-input" name="billing_address[state]" placeholder="State">
                        <input type="text" class="checkout-input" name="billing_address[apartment]" placeholder="Apartment, suite, etc. (optional)">
                        <div style="display: flex; gap: 12px;">
                            <input type="text" class="checkout-input" name="billing_address[city]" placeholder="City">
                            <input type="text" class="checkout-input" name="billing_address[zip]" placeholder="Postal code (optional)">
                        </div>
                        <input type="text" class="checkout-input" name="billing_address[country]" placeholder="Country" value="Pakistan" readonly required>
                    </div>
                </div>
            </div>
            <!-- Order Note -->
            <div class="mb-4">
                <div class="checkout-label">Order Note</div>
                @if(session('order_note'))
                    <div class="p-3 rounded" style="background:#eafbe2; color:#222; border:1px solid #71cd14;">
                        {{ session('order_note') }}
                    </div>
                    <input type="hidden" name="order_note" value="{{ session('order_note') }}">
                @else
                    <div class="text-muted">No order note added.</div>
                @endif
            </div>
            <input type="hidden" name="order_data" value="">
            <input type="hidden" name="total" id="orderTotalInput" value="{{ $cartSubtotal }}">
            <button type="submit" class="checkout-btn">Complete Order</button>
        </form>
    </div>
    <div class="checkout-summary-box">
        <div class="order-summary-title">Order Summary</div>
        <ul class="order-summary-list" style="list-style:none;padding:0;">
            @foreach($items as $item)
            <li>
                <span>
@php
    $imagePath = $item->product->galleries->first()->image ?? 'default.jpg';

    if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
        $finalImage = $imagePath; // ✅ External URL
    } elseif (\Illuminate\Support\Facades\Storage::exists($imagePath)) {
        $finalImage = Storage::url($imagePath); // ✅ Storage file
    } else {
        $finalImage = asset($imagePath); // ✅ Public asset or default
    }
@endphp

<img src="{{ $finalImage }}" 
     alt="{{ $item->product->name ?? 'Product' }}" 
     style="height:150px;width:150px;object-fit:cover;border-radius:6px;margin-right:8px;">
                    {{ $item->product->name ?? '' }} x{{ $item->quantity }}
                    <p>Ordered Variants</p>
@if($item->selected_color)
    @php
        $selectedColors = explode(',', $item->selected_color);
    @endphp
    <div class="mb-2 d-flex flex-wrap gap-2" style="margin-left: 10px;">
        @foreach($selectedColors as $color)
            @php $c = trim($color); @endphp
            <span class="color-circle selected" style="background-color: {{ $c }};"></span>
        @endforeach
    </div>
@endif



                </span>
                @php
                  
                    $itemTotal = ($item->price) * $item->quantity;
                @endphp
            </li>
            @endforeach
        </ul>
        <div class="order-summary-list">
            <li>
                <span>Subtotal</span>
                <span>Rs{{ number_format($cartSubtotal, 2) }}</span>
            </li>
            <li>
                <span>Shipping</span>
                <span id="shippingCost" style="color:#71cd14;">FREE</span>
            </li>
        </div>
        <div class="order-summary-total">
            <span>Total</span>
            <span id="orderTotal">Rs{{ number_format($cartSubtotal, 2) }}</span>
        </div>
    </div>
</div>

<!-- Proof Modal -->
<div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="proofForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="order_data" value="">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #71cd14, #5bb300); border-bottom: none;">
                    <h5 class="modal-title" id="proofModalLabel" style="color: #fff; font-weight: 600;">Upload Payment Proof</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="upload-container text-center">
                        <label for="proof_image" class="upload-label">
                            <div class="upload-arrow">
                                <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#71cd14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 5v14m0 0l-7-7m7 7l7-7"/>
                                </svg>
                            </div>
                            <div class="upload-text">Drag & Drop or <span style="color: #71cd14; cursor: pointer;">Browse</span> to upload</div>
                            <input type="file" id="proof_image" name="proof_image" class="form-control d-none" accept="image/*" required>
                            <div id="proof_image_error" class="invalid-feedback text-center"></div>
                        </label>
                        <div class="preview-container mt-3" id="imagePreview">
                            <img id="previewImage" src="" alt="Preview" style="max-width: 100%; max-height: 200px; display: none; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        </div>
                        <div class="mt-2 text-muted">Please upload a screenshot or photo of your payment receipt (Max 2MB).</div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-top-0">
                    <button type="submit" class="btn upload-btn" style="background: #71cd14; color: #fff; padding: 10px 30px; border-radius: 25px;">Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2 only once
    $('#shippingCountry,#billingCountry').each(function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2({
                placeholder: "Select a country",
                allowClear: true,
                width: '100%'
            });
        }
    });
});
</script>

<script>
$(function(){
    // Function to show/hide loader
    function toggleNewLoader(show) {
        $('#newLoader').toggle(show);
    }

    // Function to update total and shipping cost
    function updateTotal() {
        let subtotal = {{ $cartSubtotal }};
        let shippingFee = 0;
        let $selectedOption;

        // Automatically select second option and add Rs 200 if subtotal < 3000
        if (subtotal < 3000) {
            $selectedOption = $('.shipping-method-box')
                .not('.first-shipping-option')
                .first()
                .find('input[type=radio]');
            $selectedOption.prop('checked', true)
                .closest('.shipping-method-box')
                .addClass('selected');
            $('.first-shipping-option').hide();
        } else {
            $selectedOption = $('.first-shipping-option')
                .find('input[type=radio]');
            $selectedOption.prop('checked', true)
                .closest('.shipping-method-box')
                .addClass('selected');
        }
        shippingFee = parseFloat($selectedOption.data('shipping-cost')) || 0;

        let total = subtotal + shippingFee;
        $('#orderTotal').text('Rs' + total.toFixed(2));
        $('#orderTotalInput').val(total.toFixed(2));
        $('#shippingCost').text(shippingFee === 0 ? 'FREE' : 'Rs' + shippingFee.toFixed(2));
    }

    // Billing address toggle
    $('input[name="address_option"]').on('change', function(){
        $('.billing-method-box').removeClass('selected');
        $(this).closest('.billing-method-box').addClass('selected');
        if ($(this).val() === 'billing') {
            $('#billingAddressFields').slideDown();
            $('#useBillingAddress').val(1);
        } else {
            $('#billingAddressFields').find('input, select').each(function() {
                if ($(this).is('select')) {
                    $(this).val('').trigger('change');
                } else {
                    $(this).val('');
                }
            });
            $('#billingAddressFields').slideUp();
            $('#useBillingAddress').val(0);
        }
    });

    // Shipping method toggle and update shipping cost
    $('input[name="shipping_method"]').on('change', function () {
        $('.shipping-method-box').removeClass('selected');
        $(this).closest('.shipping-method-box').addClass('selected');
        let subtotal = {{ $cartSubtotal }};
        let shippingFee = parseFloat($(this).data('shipping-cost')) || 0;
        let total = subtotal + shippingFee;
        $('#orderTotal').text('Rs' + total.toFixed(2));
        $('#orderTotalInput').val(total.toFixed(2));
        $('#shippingCost').text(shippingFee === 0 ? 'FREE' : 'Rs' + shippingFee.toFixed(2));
    });

    // Set initial total and shipping cost
    updateTotal();

    // Submit order
    $('#checkoutForm').on('submit', function(e){
        e.preventDefault();
        $('.text-danger').remove();
        toggleNewLoader(true); // Show loader on form submit
        $.ajax({
            url: '{{ route("checkout.placeOrder") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(res){
                toggleNewLoader(false); // Hide loader on response
                if(res.status){
                    if(res.show_proof_modal){
                        $('#proofForm input[name="order_data"]').val(JSON.stringify(res.order_data));
                        $('#proofModal').modal('show');
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thank you!',
                            text: res.message,
                            confirmButtonColor: '#71cd14'
                        }).then(() => {
                            window.location.href = '/';
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: res.message,
                        confirmButtonColor: '#71cd14'
                    });
                }
            },
            error: function(xhr){
                toggleNewLoader(false); // Hide loader on error
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    for (let key in errors) {
                        let input = $('[name="'+key+'"]');
                        if(input.length){
                            input.after('<div class="text-danger small">'+errors[key][0]+'</div>');
                        }
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Failed!',
                        text: 'Please check the fields and try again.',
                        confirmButtonColor: '#71cd14'
                    });
                }
            }
        });
    });

    // Proof upload with preview
    $('#proofForm').on('submit', function(e) {
        e.preventDefault();
        $('#proof_image_error').text('');
        toggleNewLoader(true); // Show loader on form submit
        let fileInput = $('#proof_image')[0];
        let file = fileInput.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                $('#proof_image_error').text('File size exceeds 2MB. Please upload a smaller file.');
                toggleNewLoader(false); // Hide loader if file size error
                return;
            }
        }
        let formData = new FormData(this);
        $.ajax({
            url: '{{ route("order.uploadProof") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                toggleNewLoader(false); // Hide loader on response
                if (res.status) {
                    $('#proofModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Thank you!',
                        text: res.message,
                        confirmButtonColor: '#71cd14'
                    }).then(() => {
                        window.location.href = '/';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: res.message,
                        confirmButtonColor: '#71cd14'
                    });
                }
            },
            error: function(xhr) {
                toggleNewLoader(false); // Hide loader on error
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if (errors.proof_image) {
                        $('#proof_image_error').text(errors.proof_image[0]);
                    }
                }
            }
        });
    });

    // Image preview
    $('#proof_image').on('change', function() {
        let file = this.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                $('#proof_image_error').text('File size exceeds 2MB. Please upload a smaller file.');
                $('#previewImage').hide();
                return;
            }
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
                $('#previewImage').show();
            }
            reader.readAsDataURL(file);
            $('#proof_image_error').text('');
        } else {
            $('#previewImage').hide();
        }
    });
});
</script>

<script>
$(document).ready(function() {
    // Function to show details of selected payment and hide others
    function updatePaymentDetails() {
        const selected = $('input[name="payment_method"]:checked').val();
        $('.payment-method-box').each(function() {
            const radio = $(this).find('input[name="payment_method"]');
            const details = $(this).find('.payment-details');
            if (radio.val() === selected) {
                details.slideDown();
            } else {
                details.slideUp();
            }
        });
    }

    // Run on page load
    updatePaymentDetails();

    // Run when user changes selection
    $('input[name="payment_method"]').on('change', function() {
        updatePaymentDetails();
    });
});
</script>