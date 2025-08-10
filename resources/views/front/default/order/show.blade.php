@extends('front.default.partials.app')

@section('content')
<style>
    .tracking-progress {
        position: relative;
        margin-bottom: 30px;
    }
    .tracking-progress .progress {
        height: 8px;
        margin-bottom: 20px;
    }
    .tracking-steps {
        display: flex;
        justify-content: space-between;
        padding: 0;
        margin: 0;
        list-style: none;
    }
    .tracking-steps li {
        text-align: center;
        position: relative;
        flex: 1;
    }
    .tracking-steps li.active .step-icon {
        background-color: #71cd14;
        color: white;
    }
    .tracking-steps li .step-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #6c757d;
        margin-bottom: 5px;
    }
    .tracking-steps li .step-text {
        display: block;
        font-size: 12px;
        color: #6c757d;
    }
    .tracking-steps li.active .step-text {
        color: #71cd14;
        font-weight: bold;
    }
    .address-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .cancel-timer, .refund-timer {
        font-size: 14px;
        color: #dc3545;
        font-weight: bold;
    }
    .timeline {
        position: relative;
        padding: 20px 0;
    }
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 20px;
        width: 4px;
        background: #71cd14;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
        padding-left: 50px;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 16px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: black;
        border: 2px solid #fff;
    }
    .timeline-item .timeline-content {
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #e0e0e0;
    }
    .timeline-item .timeline-date {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 5px;
    }
    .timeline-item .badge {
        font-size: 14px;
    }
</style>

<section class="banner_area" style="background:linear-gradient(90deg,#71cd14 0%,#eafbe2 100%);min-height:120px;">
    <div class="container py-4">
        <h2 class="text-center fw-bold" style="color:#fff;">Order Details</h2>
    </div>
</section>

<div class="container py-4">
    @if($order)
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order #{{ $orderUuid }}</h5>
                <span class="badge 
                    @if($order->status === 'delivered') bg-success 
                    @elseif($order->status === 'cancelled') bg-secondary 
                    @elseif($order->status === 'shipped') bg-primary 
                    @elseif($order->status === 'processing') bg-info 
                    @else bg-warning text-dark 
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <small class="text-muted">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</small>
            <div class="mt-2">
                @if(in_array($order->status, ['pending', 'processing']) && \Carbon\Carbon::parse($order->created_at)->diffInHours(\Carbon\Carbon::now()) < 12 && !$order->is_cancel)
                    <button class="btn btn-danger btn-sm cancel-order" data-order-id="{{ $order->id }}">Cancel Request</button>
                    <span class="cancel-timer" id="cancelTimer"></span>
                @endif
                @if($order->status === 'delivered' && !$order->is_refunded && \Carbon\Carbon::parse($order->created_at)->diffInHours(\Carbon\Carbon::now()) < 12)
                    <button class="btn btn-warning btn-sm refund-order" data-order-id="{{ $order->id }}">Refund Request</button>
                    <span class="refund-timer" id="refundTimer"></span>
                @elseif($order->is_refunded)
                    <span class="text-danger font-weight-bold">Requested for Refund</span>
                @endif
            </div>
        </div>
        
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h6 class="fw-bold mb-3">Order Items</h6>
                    @foreach($order->items as $item)
                    <div class="d-flex border-bottom pb-3 mb-3">
                        <div class="flex-shrink-0">
                            @php
                                $galleryImage = optional($item->product->galleries->first())->image;
                            @endphp
                            <img src="{{ asset('storage/' . ($galleryImage ?? 'default.png')) }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="rounded" width="80">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6>{{ $item->product->name }}</h6>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <small class="text-muted">SKU: {{ $item->product->id }}</small><br>
                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                </div>
                                <div class="text-end">
                                    <div>{{ number_format($item->price, 2) }}</div>
                                    <strong>{{ number_format($item->price * $item->quantity, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="tracking-section mt-4">
                        <h6 class="fw-bold mb-3">Order Tracking</h6>
                        <div class="tracking-progress">
                            <ul class="tracking-steps" id="trackingSteps"></ul>
                        </div>
                        
                        <div class="tracking-details mt-4">
                            @if($order->tracking->first())
                                @php
                                    $latestTracking = $order->tracking->first();
                                @endphp
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Tracking Number:</strong> <span id="trackingNumber">{{ $latestTracking->tracking_number ?? 'N/A' }}</span></p>
                                        <p><strong>Carrier:</strong> <span id="trackingCarrier">{{ $latestTracking->carrier ?? 'N/A' }}</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Estimated Delivery:</strong> <span id="trackingDelivery">{{ $latestTracking->estimated_delivery ? \Carbon\Carbon::parse($latestTracking->estimated_delivery)->format('M d, Y h:i A') : 'N/A' }}</span></p>
                                        <p><strong>Current Location:</strong> <span id="trackingLocation">{{ $latestTracking->location ?? 'N/A' }}</span></p>
                                    </div>
                                </div>
                            @else
                                <p>No tracking information available.</p>
                            @endif
                        </div>
                        
                        <div class="tracking-history mt-4">
                            <h6 class="fw-bold mb-3">Tracking History</h6>
                            @if($order->tracking->count())
                                <div class="timeline">
                                    @foreach($order->tracking as $tracking)
                                        <div class="timeline-item">
                                            <div class="timeline-date">{{ \Carbon\Carbon::parse($tracking->created_at)->format('M d, Y h:i A') }}</div>
                                            <div class="timeline-content">
                                                <span class="badge 
                                                    @if($tracking->status === 'delivered') bg-success
                                                    @elseif($tracking->status === 'cancelled') bg-secondary
                                                    @elseif($tracking->status === 'shipped') bg-primary
                                                    @elseif($tracking->status === 'processing') bg-info
                                                    @elseif($tracking->status === 'in_transit') bg-warning
                                                    @elseif($tracking->status === 'out_for_delivery') bg-warning
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $tracking->status)) }}
                                                </span>
                                                <p><strong>Description:</strong> {{ $tracking->description ?? 'N/A' }}</p>
                                                <p><strong>Location:</strong> {{ $tracking->location ?? 'N/A' }}</p>
                                                <p><strong>Estimated Delivery:</strong> {{ $tracking->estimated_delivery ? \Carbon\Carbon::parse($tracking->estimated_delivery)->format('M d, Y h:i A') : 'N/A' }}</p>
                                                <p><strong>Tracking Number:</strong> {{ $tracking->tracking_number ?? 'N/A' }}</p>
                                                <p><strong>Carrier:</strong> {{ $tracking->carrier ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>No tracking history available.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="address-card">
                        <h6 class="fw-bold mb-3">Shipping Address</h6>
                        @if($order->shippingAddress)
                            <p>{{ $order->shippingAddress->name }}</p>
                            <p>{{ $order->shippingAddress->address }}</p>
                            <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->zip }}</p>
                            <p>{{ $order->shippingAddress->country }}</p>
                            <p>Phone: {{ $order->shippingAddress->phone }}</p>
                        @else
                            <p>No shipping address provided</p>
                        @endif
                    </div>

                    <div class="address-card">
                        <h6 class="fw-bold mb-3">Billing Address</h6>
                        @if($order->billingAddress)
                            <p>{{ $order->billingAddress->name }}</p>
                            <p>{{ $order->billingAddress->address }}</p>
                            <p>{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->zip }}</p>
                            <p>{{ $order->billingAddress->country }}</p>
                            <p>Phone: {{ $order->billingAddress->phone }}</p>
                        @else
                            <p>Same as shipping address</p>
                        @endif
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Order Summary</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>{{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>{{ number_format($order->shipping_cost, 2) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <span>-{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span>{{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <div class="py-5">
            <img src="{{ asset('images/no-orders.png') }}" alt="No Order Found" style="max-width: 300px;" class="mb-4">
            <h4 class="fw-bold text-muted mb-3">Order Not Found</h4>
            <p class="text-muted mb-4">We couldn't find the order you're looking for.</p>
            <a href="{{ route('orders.index') }}" class="btn btn-primary px-4">Back to My Orders</a>
        </div>
    </div>
    @endif

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
                    <img src="{{ asset('storage/' . ($suggestedImage ?? 'default.png')) }}" 
                         class="card-img-top" alt="{{ $product->name }}"
                         style="height: 150px; object-fit: cover;">
                    <div class="card-body p-2">
                        <h6 class="card-title">{{ Str::limit($product->name, 30) }}</h6>
                        <p class="mb-1">
                            <span class="text-danger">{{ number_format($product->discount_price ?? $product->price, 2) }}</span>
                            @if($product->discount_price)
                            <small class="text-muted text-decoration-line-through">{{ number_format($product->price, 2) }}</small>
                            @endif
                        </p>
                        <button class="btn btn-sm btn-outline-primary w-100 add-to-cart" 
                                data-product-id="{{ $product->id }}">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Load tracking data for progress bar if order exists
    @if($order)
    loadTrackingData('{{ $order->id }}');
    
    // Initialize cancel timer
    @if(in_array($order->status, ['pending', 'processing']) && \Carbon\Carbon::parse($order->created_at)->diffInHours(\Carbon\Carbon::now()) < 12 && !$order->is_cancel)
        startCancelTimer('{{ $order->created_at }}');
    @endif

    // Initialize refund timer
    @if($order && $order->status === 'delivered' && !$order->is_refunded && \Carbon\Carbon::parse($order->created_at)->diffInHours(\Carbon\Carbon::now()) < 12)
        startRefundTimer('{{ $order->created_at }}');
    @endif
    @endif

    // Add to cart functionality
    $(document).on('click', '.add-to-cart', function() {
        const productId = $(this).data('product-id');
        const button = $(this);
        
        button.html('<span class="spinner-border spinner-border-sm" role="status"></span> Adding...');
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
                button.html('Added to Cart');
                setTimeout(() => {
                    button.html('Add to Cart');
                    button.prop('disabled', false);
                }, 2000);
                
                $('.cart-count').text(response.cart_count);
            },
            error: function(xhr) {
                button.html('Add to Cart');
                button.prop('disabled', false);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error adding product to cart',
                });
            }
        });
    });

    // Cancel order functionality
    $(document).on('click', '.cancel-order', function() {
        const orderId = $(this).data('order-id');
        const button = $(this);
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to request cancellation of this order?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, cancel it!'
        }).then((result) => {
            if (result.isConfirmed) {
                button.html('<span class="spinner-border spinner-border-sm" role="status"></span> Requesting...');
                button.prop('disabled', true);
                
                $.ajax({
                    url: '/orders/' + orderId + '/cancel',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Cancellation requested',
                        });
                        button.hide();
                        $('#cancelTimer').hide();
                        $('.badge').removeClass().addClass('badge bg-secondary').text('Cancelled');
                    },
                    error: function(xhr) {
                        button.html('Cancel Request');
                        button.prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error requesting cancellation: ' + (xhr.responseJSON?.message || 'Unknown error'),
                        });
                    }
                });
            }
        });
    });

    // Refund request functionality
    $(document).on('click', '.refund-order', function() {
        const orderId = $(this).data('order-id');
        const button = $(this);
        
        Swal.fire({
            title: 'Request Refund?',
            text: 'Do you want to request a refund for this order?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, request refund!'
        }).then((result) => {
            if (result.isConfirmed) {
                button.html('<span class="spinner-border spinner-border-sm" role="status"></span> Requesting...');
                button.prop('disabled', true);
                
                $.ajax({
                    url: '/orders/' + orderId + '/refund',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Refund requested',
                        });
                        button.hide();
                        $('#refundTimer').hide();
                        $('.refund-order').after('<span class="text-danger font-weight-bold">Requested for Refund</span>');
                    },
                    error: function(xhr) {
                        button.html('Refund Request');
                        button.prop('disabled', false);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error requesting refund: ' + (xhr.responseJSON?.message || 'Unknown error'),
                        });
                    }
                });
            }
        });
    });

    function loadTrackingData(orderId) {
        $.get(`/api/orders/${orderId}/tracking`, function(data) {
            updateProgressBar(data.status);
            
            if(data.tracking_info) {
                $('#trackingNumber').text(data.tracking_info.tracking_number || 'N/A');
                $('#trackingCarrier').text(data.tracking_info.carrier || 'N/A');
                $('#trackingDelivery').text(data.tracking_info.estimated_delivery ? 
                    new Date(data.tracking_info.estimated_delivery).toLocaleString() : 'N/A');
                $('#trackingLocation').text(data.tracking_info.location || 'N/A');
            }
        });
    }

    function updateProgressBar(status) {
        const steps = [
            {status: 'pending', label: 'Pending', class: 'bg-secondary'},
            {status: 'processing', label: 'Processing', class: 'bg-info'},
            {status: 'shipped', label: 'Shipped', class: 'bg-primary'},
            {status: 'in_transit', label: 'In Transit', class: 'bg-warning'},
            {status: 'out_for_delivery', label: 'Out for Delivery', class: 'bg-warning'},
            {status: 'delivered', label: 'Delivered', class: 'bg-success'}
        ];
        
        let progress = 0;
        let stepsHtml = '';
        let currentStep = 0;
        
        steps.forEach(function(step, index) {
            const isActive = status === step.status || (index < currentStep && status === steps[currentStep].status);
            if (status === step.status) {
                progress = ((index + 1) / steps.length) * 100;
                currentStep = index;
            }
            
            stepsHtml += `
                <li class="${isActive ? 'active' : ''}">
                    <span class="step-icon ${step.class}">${index + 1}</span>
                    <span class="step-text">${step.label}</span>
                </li>
            `;
        });
        
        $('#progressBar').css('width', progress + '%');
        $('#trackingSteps').html(stepsHtml);
    }

    function startCancelTimer(createdAt) {
        const created = new Date(createdAt);
        const endTime = new Date(created.getTime() + 12 * 60 * 60 * 1000); // 12 hours from created_at
        
        function updateTimer() {
            const now = new Date();
            const timeLeft = endTime - now;
            
            if (timeLeft <= 0) {
                $('#cancelTimer').hide();
                $('.cancel-order').hide();
                return;
            }
            
            const hours = Math.floor(timeLeft / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
            
            $('#cancelTimer').text(`You can make Cancel Request within ${hours}h ${minutes}m ${seconds}s remaining`);
            setTimeout(updateTimer, 1000);
        }
        
        updateTimer();
    }

    function startRefundTimer(createdAt) {
        const created = new Date(createdAt);
        const endTime = new Date(created.getTime() + 12 * 60 * 60 * 1000); // 12 hours from created_at
        
        function updateTimer() {
            const now = new Date();
            const timeLeft = endTime - now;
            
            if (timeLeft <= 0) {
                $('#refundTimer').hide();
                $('.refund-order').hide();
                return;
            }
            
            const hours = Math.floor(timeLeft / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
            
            $('#refundTimer').text(`You can make Refund Request within ${hours}h ${minutes}m ${seconds}s remaining`);
            setTimeout(updateTimer, 1000);
        }
        
        updateTimer();
    }
});
</script>
@endsection