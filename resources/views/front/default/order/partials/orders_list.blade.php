@forelse ($orders as $order)
<div class="border rounded p-3 mb-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <small class="text-muted">Order ID: {{ $order->id }}</small><br>
            <small class="text-muted">Order Date: {{ $order->created_at->format('M d, Y') }}</small>
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
            <img src="{{ asset('storage/' . ($galleryImage ?? 'default.png')) }}"
                 alt="{{ $item->product->name }}" class="me-3 rounded" width="70">
            <div>
                <h6 class="mb-1">{{ $item->product->name }}</h6>
                <small class="text-muted">SKU: {{ $item->product->id }}</small>
            </div>
        </div>
        <div class="text-end">
            <div class="text-muted">Price: {{ number_format($item->price, 2) }}</div>
            <div class="text-muted">Qty: {{ $item->quantity }}</div>
            <strong>Total: {{ number_format($item->price * $item->quantity, 2) }}</strong>
        </div>
    </div>
    @endforeach

    <div class="d-flex justify-content-between mt-3">
        <div>
            @if($order->order_note)
                <p class="mb-0"><strong>Note:</strong> {{ $order->order_note }}</p>
            @endif
        </div>
        <div>
            <strong>Shipping:</strong> {{ number_format($order->shipping_cost, 2) }}<br>
            <strong>Total:</strong> {{ number_format($order->total, 2) }}
        </div>
    </div>
</div>
@empty
    <p class="text-muted">No orders found for this tab.</p>
@endforelse
