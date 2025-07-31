@extends('front.default.partials.app')

@section('content')
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center" style="background:linear-gradient(90deg,#71cd14 0%,#eafbe2 100%);min-height:120px;">
        <div class="container">
            <h2 class="text-center fw-bold" style="color:#fff;">Your Cart</h2>
        </div>
    </div>
</section>

<div class="free-shipping-progress mt-4 position-relative" style="height: 50px;">
    <div class="progress" style="height: 10px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 100%;"></div>
    </div>

    <div class="text-success small mt-1 justify-content-end d-flex"><h3>You qualify for free shipping!</h3></div>

    <!-- Moving Truck -->
    <div class="truck-moving">🚚</div>
</div>

<style>
.truck-moving {
    position: absolute;
    top: -20px;
    right: 0;
    font-size: 28px;
    animation: truckMove 12s ease-in-out infinite;
}

/* Truck moves forward and flips at ends */
@keyframes truckMove {
    0% {
        right: 0%;
        transform: scaleX(1); /* face right */
    }
    45% {
        right: 100%;
        transform: scaleX(1);
    }
    50% {
        right: 100%;
        transform: scaleX(-1); /* turn around */
    }
    95% {
        right: 0%;
        transform: scaleX(-1); /* moving right */
    }
    100% {
        right: 0%;
        transform: scaleX(1); /* flip again for next loop */
    }
}
</style>





<section class="cart_area py-5" style="background:#f8f9fa;">
    <div class="container-fluid px-lg-5 px-2">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body">
                        @if($items->count())
                      
<div class="alert alert-warning d-flex align-items-center mt-3">
    <i class="bi bi-info-circle-fill me-2"></i>
    <span>
        ⏳ Hurry! One or more items in your cart are in high demand and will only be reserved for the next <strong>limited time</strong>. 
        Complete your purchase before they’re released to others.
    </span>
</div>



                        <div class="table-responsive">
                            <table class="table align-middle mb-0" id="cartTable">
                                <thead class="table-light">
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
                                    <tr data-item="{{ $item->id }}" data-created="{{ $item->created_at }}">
                                        <td>
                                            <a href="{{ route('product.details', $item->product->slug) }}" class="fw-semibold text-dark">
                                                {{ $item->product->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <img style="height:100px;width:100px;" src="{{ asset('storage/' . ($item->product->galleries->first()->image ?? 'default.jpg')) }}" width="60" class="rounded shadow-sm" alt="{{ $item->product->name }}">
                                        </td>
                                        <td>
                                            <span class="fw-bold price" style="color:#71cd14;">Rs{{ number_format($item->price, 2) }}</span>
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
                                            <span class="fw-bold item-total">Rs{{ number_format($item->price * $item->quantity, 2) }}</span>
                                        </td>
                                        <td colspan="5">
    <span class="badge bg-warning text-dark fw-semibold timer-message" id="timer-{{ $item->id }}">⏳ Loading...</span>
</td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-6 col-md-8 col-12">
                                <div class="cart-note p-4 rounded-3 mb-3" style="background:#fff;">
                                    <h5 class="mb-3 fw-bold" style="color:#71cd14;">Order Note <small class="text-muted">(Max 1000 characters)</small></h5>
                                    <form id="orderNoteForm">
                                        <textarea id="orderNote" name="order_note" class="form-control mb-2" maxlength="1000" placeholder="Write your order note...">{{ session('order_note') }}</textarea>
                                        <small class="text-muted d-block text-end">
                                            Characters: <span id="charCount">0</span>/1000
                                        </small>
                                        <button type="submit" id="saveNoteBtn" class="btn btn-outline-success rounded-pill mt-2" style="color:#71cd14;border-color:#71cd14;">Save Note</button>
                                    </form>
                                    <div id="savedOrderNote" class="d-flex justify-content-between align-items-center mt-3" style="@if(!session('order_note'))display: none;@endif background: #eafbe2; padding: 10px 15px; border-radius: 10px;">
                                        <span class="text-dark fw-semibold" id="noteContent">{{ session('order_note') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-8 col-12 ms-auto">
                                <div class="cart-summary p-4 rounded-3" style="background:#eafbe2;">
                                    <h5 class="mb-3 fw-bold" style="color:#71cd14;">Cart Summary</h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-semibold">Subtotal:</span>
                                        <span class="fw-bold" id="cartSubtotal">Rs{{ number_format($items->sum(fn($i) => $i->price * $i->quantity), 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-semibold">Shipping:</span>
                                        <span class="fw-bold">Free</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fs-5 fw-bold">
                                        <span>Total:</span>
                                        <span style="color:#71cd14;" id="cartTotal">Rs{{ number_format($items->sum(fn($i) => $i->price * $i->quantity), 2) }}</span>
                                    </div>
                                    <a href="{{ route('checkout') }}" class="btn btn-success w-100 mt-3 rounded-pill fw-bold" style="background:#71cd14;border:none;">Proceed to Checkout</a>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-5">
                            <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" width="150" class="mb-3" alt="Empty Cart">
                            <h4 class="fw-bold text-muted">Your cart is empty.</h4>
                            <a href="{{ route('main') }}" class="btn btn-outline-success rounded-pill mt-3" style="color:#71cd14;border-color:#71cd14;">Continue Shopping</a>
                        </div>
                        @endif
                <div class="col-8 ms-auto">
                            <div class="delivery-msg">
      <h3>
                              <b>            We deliver your products fast with our trusted partners:
</b>
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
@endsection

<!-- JS Section -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function updateCartTotals() {
    let subtotal = 0;
    $('#cartTable tbody tr').each(function () {
        let price = parseFloat($(this).find('.price').text().replace('Rs',''));
        let qty = parseInt($(this).find('.quantity-input').val());
        let total = price * qty;
        $(this).find('.item-total').text('Rs' + total.toFixed(2));
        subtotal += total;
    });
    $('#cartSubtotal, #cartTotal').text('Rs' + subtotal.toFixed(2));
}

$(document).ready(function () {
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
        const row = input.closest('tr');

        $.ajax({
            url: '/cart/update/' + itemId,
            type: 'POST',
            data: {
                quantity: quantity,
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                if (res.status) {
                    const price = parseFloat(row.find('.price').text().replace('Rs',''));
                    const total = price * quantity;
                    row.find('.item-total').text('Rs' + total.toFixed(2));
                    updateCartTotals();
                }
            }
        });
    });

    // SweetAlert2 delete confirmation
    $(document).on('click', '.remove-cart-item', function (e) {
        e.preventDefault();
        const itemId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to remove this item from your cart.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#71cd14',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/cart/remove/' + itemId,
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
                    }
                });
            }
        });
    });

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
            }
        });
    });

    $('#deleteOrderNote').on('click', function () {
        $.ajax({
            url: '{{ route('cart.deleteNote') }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                if (res.status) {
                    $('#orderNote').val('');
                    $('#savedOrderNote').hide();
                    $('#charCount').text('0');
                }
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
    updateCartTotals();
});
</script>

<script>
function startCountdownTimers() {
    $('#cartTable tbody tr').each(function () {
        const row = $(this);
        const itemId = row.data('item');
        const createdAt = row.data('created'); // UTC timestamp from Laravel

        if (!createdAt) return;

        const timerElement = $('#timer-' + itemId);

        // Parse createdAt and add 45 minutes
        const createdTime = new Date(createdAt);
        const expireTime = new Date(createdTime.getTime() + 45 * 60 * 1000);

        function updateTimer() {
            const now = new Date();
            const remaining = expireTime - now;

            if (remaining <= 0) {
                timerElement.text('⛔ Time expired! Item will be removed...');
                row.fadeOut(1000, function () {
                    // Optionally send request to backend to remove item
                    $.ajax({
                        url: '/cart/remove/' + itemId,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function (res) {
                            if (res.status) {
                                row.remove();
                                updateCartTotals();
                            }
                        }
                    });
                });
                return;
            }

            const mins = Math.floor(remaining / 60000);
            const secs = Math.floor((remaining % 60000) / 1000);
            const msg = mins < 1 ? "⏰ Hurry! Few seconds left!" : `⏳ ${mins}m ${secs}s left`;

            timerElement.text(msg);
        }

        // Start interval
        updateTimer();
        setInterval(updateTimer, 1000);
    });
}

$(document).ready(function () {
    // Existing code...
    startCountdownTimers(); // Call after cart renders
});
</script>
