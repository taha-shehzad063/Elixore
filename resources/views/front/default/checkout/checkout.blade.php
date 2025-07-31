@extends('front.default.partials.app')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}" />
<style>
/* Hide original <select> */

</style>
<div class="checkout-wrapper">
    <div class="checkout-main">
        <div class="checkout-title">Checkout</div>
        <form id="checkoutForm">
            @csrf
            <!-- Delivery Address -->
            <div class="mb-4">
                <div class="checkout-label">Delivery Address</div>
                <div style="display: flex; gap: 12px;">
                    <input type="text" class="checkout-input" name="shipping_address[name]" placeholder="Full Name" required>
                    <input type="text" class="checkout-input" name="shipping_address[phone]" placeholder="Phone Number" required>
                </div>
                <input type="text" class="checkout-input" name="shipping_address[address]" placeholder="Street Address" required>
                <input type="text" class="checkout-input" name="shipping_address[apartment]" placeholder="Apartment, suite, etc. (optional)">
                <div style="display: flex; gap: 12px;">
                    <input type="text" class="checkout-input" name="shipping_address[city]" placeholder="City" required>
                    <input type="text" class="checkout-input" name="shipping_address[zip]" placeholder="Postal code (optional)">
                </div>
<div class="mb-3">
    <label for="shippingCountry" class="form-label">Country</label>
    <select 
        name="shipping_address[country]" 
        class="form-select" 
        id="shippingCountry"
        required
    >
        <option class="d-none">Select Country</option>
        @foreach ($countries as $country)
            <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
        @endforeach
    </select>
</div>              
            </div>
            <!-- Shipping Method -->
          <div class="mb-4">
   <div class="checkout-label">Shipping Method</div>

@php $firstShipping = true; @endphp
@foreach($checkoutOptions as $option)
    @if($option->type === 'shipping')
        <div class="shipping-method-box {{ $firstShipping ? 'selected' : '' }}">
            <label class="checkout-radio-label" style="display:flex; align-items:center;">
                <input 
                    type="radio" 
                    name="shipping_method" 
                    value="{{ $option->key }}" 
                    data-shipping-cost="{{ $option->shipping_cost }}"
                    {{ $firstShipping ? 'checked' : '' }}>
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
           <!-- Inside your <form id="checkoutForm"> -->
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
            <input type="text" class="checkout-input" name="billing_address[apartment]" placeholder="Apartment, suite, etc. (optional)">
            <div style="display: flex; gap: 12px;">
                <input type="text" class="checkout-input" name="billing_address[city]" placeholder="City">
                <input type="text" class="checkout-input" name="billing_address[zip]" placeholder="Postal code (optional)">
            </div>
<!-- Country Dropdown -->
<div class="mb-3">
    <label for="billingCountry" class="form-label">Country</label>
    <select 
        name="billing_address[country]" 
        class="form-select" 
        id="billingCountry"
        style="width: 100%;"
    >
        <option value="">Select Country</option>
        @foreach ($countries as $country)
            <option value="{{ $country['name'] }}">{{ $country['name'] }}</option>
        @endforeach
    </select>
</div>


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
            <button type="submit" class="checkout-btn">Complete Order</button>
        </form>
    </div>
   <div class="checkout-summary-box">
    <div class="order-summary-title">Order Summary</div>
    <ul class="order-summary-list" style="list-style:none;padding:0;">
        @foreach($items as $item)
        <li>
            <span>
                <img style="height:150px;width:150px;" src="{{ asset('storage/' . ($item->product->galleries->first()->image ?? 'default.jpg')) }}" alt="" style="width:38px;height:38px;object-fit:cover;border-radius:6px;margin-right:8px;">
                {{ $item->product->name ?? '' }} x{{ $item->quantity }}
            </span>
            <span>Rs{{ number_format($item->price * $item->quantity, 2) }}</span>
        </li>
        @endforeach
    </ul>
    <div class="order-summary-list">
        <li>
            <span>Subtotal</span>
            <span>Rs{{ number_format($items->sum(fn($i) => $i->price * $i->quantity), 2) }}</span>
        </li>
        <li>
            <span>Shipping</span>
            <span id="shippingCost" style="color:#71cd14;">FREE</span>
        </li>
    </div>
    <div class="order-summary-total">
        <span>Total</span>
        <span id="orderTotal">Rs{{ number_format($items->sum(fn($i) => $i->price * $i->quantity), 2) }}</span>
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
        <div class="modal-header" style="background:#f3ffe7;">
          <h5 class="modal-title" id="proofModalLabel" style="color:#71cd14;">Upload Payment Proof</h5>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Payment Screenshot <span style="color:red">*</span></label>
            <input type="file" name="proof_image" class="form-control" accept="image/*" required>
            <div class="invalid-feedback" id="proof_image_error"></div>
          </div>
          <div class="mt-2 text-muted">Please upload a screenshot or photo of your payment receipt.</div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn" style="background:#71cd14;color:#fff;">Upload</button>
        </div>
      </div>
    </form>
  </div>
</div>
   
@endsection
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- ✅ Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
 
<!-- Place this right before closing </body> tag -->
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
    // Billing address toggle
    $('input[name="address_option"]').on('change', function(){
        $('.billing-method-box').removeClass('selected');
        $(this).closest('.billing-method-box').addClass('selected');

        if ($(this).val() === 'billing') {
            $('#billingAddressFields').slideDown();
            $('#useBillingAddress').val(1); // signal to backend: use billing address
        } else {
            // Clear all billing address fields
            $('#billingAddressFields').find('input, select').each(function() {
                if ($(this).is('select')) {
                    $(this).val('').trigger('change');
                } else {
                    $(this).val('');
                }
            });
            $('#billingAddressFields').slideUp();
            $('#useBillingAddress').val(0); // signal to backend: use shipping address
        }
    });

        // Shipping method toggle and update shipping cost
  $('input[name="shipping_method"]').on('change', function () {
    $('.shipping-method-box').removeClass('selected');
    $(this).closest('.shipping-method-box').addClass('selected');

    let subtotal = {{ $items->sum(fn($i) => $i->price * $i->quantity) }};
    
    // Get shipping cost from data attribute of selected option
    let shippingFee = parseFloat($(this).data('shipping-cost')) || 0;

    // Update shipping cost display with color change
    if (shippingFee === 0) {
        $('#shippingCost').text('FREE').css('color', '#71cd14');
    } else {
        $('#shippingCost').text('Rs ' + shippingFee.toFixed(2)).css('color', '#222');
    }

    // Calculate total with shipping
    let total = subtotal + shippingFee;
    $('#orderTotal').text('Rs' + total.toFixed(2));
});



    // Submit order
    $('#checkoutForm').on('submit', function(e){
        e.preventDefault();
        $('.text-danger').remove(); // Remove previous errors

        $.ajax({
            url: '{{ route("checkout.placeOrder") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(res){
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

    // Proof upload
    $('#proofForm').on('submit', function(e){
        e.preventDefault();
        $('#proof_image_error').text('');
        var formData = new FormData(this);
        $.ajax({
            url: '{{ route("order.uploadProof") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res){
                if(res.status){
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
            error: function(xhr){
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    if(errors.proof_image){
                        $('#proof_image_error').text(errors.proof_image[0]);
                    }
                }
            }
        });
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

