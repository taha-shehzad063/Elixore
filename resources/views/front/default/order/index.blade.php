@extends('front.default.partials.app')

@section('content')
<style>
    #order-tabs .nav-link {
        color: #333;
        font-weight: 500;
        padding: 10px 16px;
        border: none;
        border-radius: 0;
        background: none;
        position: relative;
        transition: color 0.3s;
    }

    #order-tabs .nav-link.active {
        color: #71cd14;
        font-weight: 600;
    }

    #order-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 25%;
        width: 50%;
        height: 2px;
        background-color: #71cd14;
        border-radius: 2px;
    }

    #order-tabs .nav-link:hover {
        color: #71cd14;
    }

    .nav-tabs {
        border-bottom: 1px solid #ddd;
    }

    .no-orders-container {
        background-color: #f8f9fa;
        padding: 50px 20px;
        border-radius: 8px;
        text-align: center;
        margin: 30px 0;
    }

    .no-orders-container img {
        max-width: 200px;
        margin-bottom: 20px;
    }

    .order-detail-link {
        color: #71cd14;
        font-weight: 500;
        text-decoration: none;
    }

    .order-detail-link:hover {
        text-decoration: underline;
    }

    .add-to-cart-btn {
        background-color: #71cd14;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 14px;
        transition: background-color 0.3s;
        width: 100%;
        margin-top: 8px;
    }

    .add-to-cart-btn:hover {
        background-color: #5fb10d;
    }

    .add-to-cart-btn:disabled {
        background-color: #cccccc;
    }
</style>

<section class="banner_area">
    <div class="banner_inner d-flex align-items-center" style="background:linear-gradient(90deg,#71cd14 0%,#eafbe2 100%);min-height:120px;">
        <div class="container">
            <h2 class="text-center fw-bold" style="color:#fff;">My Orders</h2>
        </div>
    </div>
</section>

<div class="container py-4">
    @php
        $tabs = [
            'all' => 'View All',
            'pending' => 'To Pay',
            'processing' => 'To Ship',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ];
    @endphp

    <ul class="nav nav-tabs mb-4" id="order-tabs">
        @foreach($tabs as $key => $label)
            <li class="nav-item">
                <a class="nav-link @if(request('status', 'all') == $key) active @endif"
                   href="javascript:void(0)"
                   data-status="{{ $key }}">
                    {{ $label }}
                </a>
            </li>
        @endforeach
    </ul>

    <div id="orders-container">
        @include('front.default.order.partials.orders_list', ['orders' => $orders])
    </div>

  
</div>

<script>
$(document).ready(function() {
    // Tab switching functionality
    $('#order-tabs .nav-link').click(function() {
        $('#order-tabs .nav-link').removeClass('active');
        $(this).addClass('active');
        
        const status = $(this).data('status');
        
        $.ajax({
            url: '{{ route("orders.index") }}',
            data: { status: status },
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(response) {
                $('#orders-container').html(response);
            },
            error: function(xhr) {
                console.error('Error loading orders:', xhr.responseText);
            }
        });
    });

    // Add to cart functionality
    $(document).on('click', '.add-to-cart-btn', function() {
        const button = $(this);
        const productId = button.data('product-id');
        
        button.html('<i class="fas fa-spinner fa-spin"></i> Adding...');
        button.prop('disabled', true);
        
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: 1,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                button.html('<i class="fas fa-check"></i> Added');
                setTimeout(() => {
                    button.html('Add to Cart');
                    button.prop('disabled', false);
                }, 2000);
                
                // Update cart count in header
                $('.cart-count').text(response.cart_count);
            },
            error: function(xhr) {
                button.html('Add to Cart');
                button.prop('disabled', false);
                alert('Error: Could not add product to cart');
            }
        });
    });
});
</script>
@endsection