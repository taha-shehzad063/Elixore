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
</style>
<section class="banner_area ">
    <div class="banner_inner d-flex align-items-center" style="background:linear-gradient(90deg,#71cd14 0%,#eafbe2 100%);min-height:120px;">
        <div class="container">
            <h2 class="text-center fw-bold" style="color:#fff;">My Orders</h2>
        </div>
    </div>
</section>
<div class="container py-4">
    <div class="mb-4">
    </div>

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

    @if($suggestedProducts->count())
    <div class="mt-5">
        <h4 class="fw-bold mb-3">You may also like</h4>
        <div class="row">
            @foreach($suggestedProducts as $product)
            <div class="col-md-2 col-6 mb-4">
                <div class="card h-100">
                    @php
                        $suggestedImage = optional($product->galleries->first())->image;
                    @endphp
                    <img src="{{ asset('storage/' . ($suggestedImage ?? 'default.png')) }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body p-2">
                        <h6 class="card-title">{{ $product->name }}</h6>
                        <p class="mb-0">
                            <span class="text-danger">{{ number_format($product->discount_price ?? $product->price, 2) }}</span>
                            @if($product->discount_price)
                            <small class="text-muted text-decoration-line-through">{{ number_format($product->price, 2) }}</small>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('#order-tabs .nav-link');

        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const status = this.getAttribute('data-status');

                fetch(`{{ route('orders.index') }}?status=${status}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('orders-container').innerHTML = html;
                });
            });
        });
    });
</script>

