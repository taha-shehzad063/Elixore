<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .email-container {
            max-width: 650px;
            margin: 30px auto;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .email-header {
            background: linear-gradient(90deg,#71cd14 0%,#eafbe2 100%);
            padding: 20px;
            color: #fff;
            text-align: center;
        }

        .email-body {
            padding: 25px;
        }

        .email-body h2 {
            color: #71cd14;
            margin-bottom: 20px;
        }

        .order-info p,
        .shipping-info p {
            margin: 5px 0;
            line-height: 1.6;
        }

        .product-list {
            margin: 20px 0;
        }

        .product-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
        }

        .product-item img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            margin-right: 15px;
            border-radius: 5px;
            border: 1px solid #eee;
        }

        .payment-proof img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .delivery-msg {
            font-size: 16px;
            font-weight: bold;
            margin-top: 30px;
            text-align: center;
            color: #333;
        }

        .delivery-partners {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 15px;
            gap: 15px;
        }

        .delivery-partners img {
            height: 40px;
        }

        .email-footer {
            background-color: #eafbe2;
            padding: 20px;
            font-size: 14px;
            color: #777;
            text-align: center;
            border-top: 1px solid #d4ecd1;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h1>Your Order Has Been Received</h1>
    </div>

    <div class="email-body">
        <h2>Hi {{ $user->name }},</h2>

        <p>Thank you for placing your order with us. Below are your order details:</p>

        <div class="order-info">
            <p><strong>Total:</strong> Rs{{ number_format($order->total, 2) }}</p>
            <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>

        @if($order->payment_proof)
            <div class="payment-proof" style="margin-top: 20px;">
                <p><strong>Payment Proof:</strong></p>
                <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Payment Proof Image">
            </div>
        @endif

        @if($order->items && $order->items->count())
            <h3 style="margin-top: 30px; color: #71cd14;">Ordered Products</h3>
            <div class="product-list">
                @foreach($order->items as $item)
                    <div class="product-item">
                        <img src="{{ asset('storage/' . ($item->product->galleries->first()->image ?? 'default.jpg')) }}" alt="{{ $item->product->name }}">
                        <div>
                            <strong>{{ $item->product->name }}</strong><br>
                            Quantity: {{ $item->quantity }}<br>
                            Price: Rs{{ number_format($item->price, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @php
            $address = $order->shippingAddress;
        @endphp

        @if($address)
            <h3 style="margin-top: 30px; color: #71cd14;">Shipping Address</h3>
            <div class="shipping-info">
                @if($address->name)<p><strong>Name:</strong> {{ $address->name }}</p>@endif
                @if($address->phone)<p><strong>Phone:</strong> {{ $address->phone }}</p>@endif
                @if($address->address)<p><strong>Address:</strong> {{ $address->address }}</p>@endif
                @if($address->city)<p><strong>City:</strong> {{ $address->city }}</p>@endif
                @if($address->state)<p><strong>State:</strong> {{ $address->state }}</p>@endif
                @if($address->zip)<p><strong>ZIP:</strong> {{ $address->zip }}</p>@endif
                @if($address->country)<p><strong>Country:</strong> {{ $address->country }}</p>@endif
            </div>
        @endif

        <p style="margin-top: 20px;">We will start processing your order shortly. You will receive another email once your order is shipped.</p>

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

    <div class="email-footer">
        Regards,<br>
        <strong>Your Store Team</strong><br>
        <small>If you have any questions, contact us anytime.</small>
    </div>
</div>
</body>
</html>
