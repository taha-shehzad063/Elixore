@forelse ($orders as $order)
<div class="border rounded p-3 mb-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <small class="text-muted">Placed on {{ $order->created_at->format('M d, Y') }}</small>
        </div>
        <div>
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
    </div>

    @foreach ($order->items as $item)
    <div class="d-flex justify-content-between align-items-center border-top py-3">
        <div class="d-flex align-items-center">
            @php
                $galleryImage = optional($item->product->galleries->first())->image;
            @endphp
            <img src="{{ asset(($galleryImage ?? 'default.png')) }}"
                 alt="{{ $item->product->name }}" 
                 class="me-3 rounded" 
                 width="70"
                 style="object-fit: cover;">
            <div>
                <h6 class="mb-1">{{ $item->product->name }}</h6>
<small class="text-muted">
    Description: 
    {{ \Illuminate\Support\Str::limit(strip_tags($item->product->description), 83, '..') }}
</small>
            </div>
        </div>
        <div class="text-end">
            <div class="text-muted">{{ number_format($item->price, 2) }}</div>
            <div class="text-muted">Qty: {{ $item->quantity }}</div>
            <strong>{{ number_format($item->price * $item->quantity, 2) }}</strong>
        </div>
    </div>
    @endforeach

    <div class="d-flex justify-content-between mt-3">
        <div>
            @if($order->order_note)
                <p class="mb-0"><strong>Note:</strong> {{ $order->order_note }}</p>
            @endif
        </div>
       <div class="text-end">
    <strong>Shipping: {{ number_format($order->shipping_cost, 2) }}</strong><br>
    <strong>Total: {{ number_format($order->total, 2) }}</strong><br>

    @if ($order->is_cancel)
        <small class="text-danger">Requested for Cancel</small>
    @elseif ($order->is_refund)
        <small class="text-warning">Requested for Refund</small>
    @else
        <a href="{{ route('orders.show', $order->hashed_id) }}"
   class="order-detail-link mt-2 d-inline-block btn btn-sm"
   style="background-color: #71cd14 !important; color: #fff !important;">
    View Order Details
</a>


    @endif
</div>

    </div>
</div>
@empty
<div class="no-orders-container text-center">
    <img src="https://img.freepik.com/free-vector/no-data-concept-illustration_114360-2506.jpg?size=626&ext=jpg&ga=GA1.2.569389782.1660639394" alt="No Orders Found" class="mb-4" style="max-width: 200px;">
    <h4 class="fw-bold text-muted mb-3">No Orders Found</h4>
    <p class="text-muted mb-4">You haven't placed any orders in this category yet. Start shopping now!</p>
    <a href="{{ route('shop.index') }}" class="btn btn-primary px-4" style="background-color: #71cd14; border-color: #71cd14;">Continue Shopping</a>
</div>
@endforelse