<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\CheckoutOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use DB;
use App\Traits\UploadsImages;
use Illuminate\Validation\Rule;
use App\Notifications\OrderPlacedNotification;


use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
            use UploadsImages;
public function checkout()
{
    $countries = [];

    try {
        $response = Http::get('https://www.apicountries.com/countries');
        if ($response->successful()) {
            $countries = $response->json();
        }
    } catch (\Exception $e) {
        // Log error if needed
    }

    $user = Auth::user();
    $cart = Cart::where('user_id', $user->id)->where('status', 'active')->first();
    $items = $cart ? $cart->items()->with('product')->get() : collect();
    $addresses = Address::where('user_id', $user->id)->get();
    $checkoutOptions = CheckoutOption::where('status', 1)->get();

$cartSubtotal = $cart?->total ?? 0;

// If subtotal is 0, calculate manually from products
if ($cartSubtotal == 0 && $items->isNotEmpty()) {
    $cartSubtotal = $items->reduce(function ($carry, $item) {
        $productPrice = $item->product->sale_price ?? $item->product->price ?? 0;
        return $carry + ($productPrice * $item->quantity);
    }, 0);
}
// dd($cartSubtotal);
    return view('front.default.checkout.checkout', compact(
        'items',
        'cartSubtotal',
        'addresses',
        'cart',
        'countries',
        'checkoutOptions'
    ));
}


public function saveCartTotal(Request $request)
{
    $request->validate([
        'total' => 'required|numeric|min:0',
    ]);

    $user = Auth::user();

    $cart = Cart::where('user_id', $user->id)->where('status', 'active')->first();

    if (!$cart) {
        return redirect()->back()->with('error', 'Cart not found.');
    }

    $cart->total = $request->input('total');
    $cart->save();

    return redirect()->route('checkout')->with('success', 'Total saved successfully!');
}

    public function placeOrder(Request $request)
    {
            $total = $request->input('total'); // Use the total sent from the form
        
        $shippingOption = CheckoutOption::where('type', 'shipping')
            ->where('key', $request->shipping_method)
            ->first();

        $shippingCost = $shippingOption && $shippingOption->shipping_cost !== null
            ? $shippingOption->shipping_cost
            : 0;

        // dd($shippingCost);
                $validShippingMethods = CheckoutOption::where('type', 'shipping')->pluck('key')->toArray();
                $validPaymentMethods = CheckoutOption::where('type', 'payment')->pluck('key')->toArray();

        // dd($request->all());
        $request->validate([
            'shipping_method' => ['required', Rule::in($validShippingMethods)],
            'payment_method' => ['required', Rule::in($validPaymentMethods)],
            'address_option' => 'required|in:same,billing',
            'shipping_address.name' => 'required|string',
            'shipping_address.phone' => 'required|string',
            'shipping_address.address' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.country' => 'required|string',
        ]);

        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->where('status', 'active')->first();
  
        if (!$cart || $cart->items->count() === 0) {
            return response()->json([
                'status' => false,
                'message' => 'Your cart is empty or not found. Please add items to your cart first.'
            ]);
        }

        if ($request->payment_method === 'Cash on Delivery (COD)') {
            $items = $cart->items;

            // Save shipping address
            $shipping = Address::create([
                'user_id' => $user->id,
                'type' => 'shipping',
                'name' => $request->shipping_address['name'],
                'phone' => $request->shipping_address['phone'],
                'address' => $request->shipping_address['address'],
                'city' => $request->shipping_address['city'],
                'state' => $request->shipping_address['state'] ?? null,
                'zip' => $request->shipping_address['zip'] ?? null,
                'country' => $request->shipping_address['country'],
            ]);

            // Save billing address if different
            $billing = null;
            if ($request->address_option == 'billing') {
                $billing = Address::create([
                    'user_id' => $user->id,
                    'type' => 'billing',
                    'name' => $request->billing_address['name'],
                    'phone' => $request->billing_address['phone'],
                    'address' => $request->billing_address['address'],
                    'city' => $request->billing_address['city'],
                    'state' => $request->billing_address['state'] ?? null,
                    'zip' => $request->billing_address['zip'] ?? null,
                    'country' => $request->billing_address['country'],
                ]);
            }


            $totalQty = $items->sum('quantity');
            
            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address_id' => $shipping->id,
                'billing_address_id' => $billing ? $billing->id : $shipping->id,
                'shipping_method' => $request->shipping_method,
                    'shipping_cost' => $shippingCost,

                'payment_method' => $request->payment_method,
                'total' => $total,
                'total_quantity' => $totalQty,
                'order_note' => $request->order_note,
                'status' => 'pending',
            ]);

            // Create order items
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            // Clear cart
            $cart->items()->delete();
            $cart->delete();

            // If payment is not COD, ask for proof upload
            if ($request->payment_method !== 'Cash on Delivery (COD)') {
                return response()->json([
                    'status' => true,
                    'show_proof_modal' => true,
                    'order_id' => $order->id,
                    'message' => 'Order placed! Please upload your payment proof.'
                ]);
            }
            $user->notify(new OrderPlacedNotification($order->load('items.product.galleries')));

            // If COD, just thank you
            return response()->json([
                'status' => true,
                'show_proof_modal' => false,
                'message' => 'Order placed successfully! Thank you for your order.You will be get an Email Confirmation.'
            ]);
        }

        // For non-COD, just return the order data (do NOT create order yet)
        return response()->json([
            'status' => true,
            'show_proof_modal' => true,
            'order_data' => $request->all(),
            'message' => 'Please upload your payment proof to complete your order.'
        ]);
    }
    public function uploadProof(Request $request)
    {
        
        
        $request->validate([
            'proof_image' => 'required|image|max:2048',
            'order_data' => 'required'
        ]);
        
        $orderData = json_decode($request->order_data, true);
        $total = $orderData['total'] ?? 0;
        // dd($total);
            $shippingOption = CheckoutOption::where('type', 'shipping')
                        ->where('key', $orderData['shipping_method'])
                        ->first();

            $shippingCost = $shippingOption ? ($shippingOption->shipping_cost ?? 0) : 0;
                    // dd($shippingCost);
                    // Validate required fields in $orderData as needed

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $cart = Cart::where('user_id', $user->id)->where('status', 'active')->first();

            if (!$cart || $cart->items->count() === 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your cart is empty or not found. Please add items to your cart first.'
                ]);
            }
            $items = $cart->items;

            // Save shipping address
            $shipping = Address::create([
                'user_id' => $user->id,
                'type' => 'shipping',
                'name' => $orderData['shipping_address']['name'],
                'phone' => $orderData['shipping_address']['phone'],
                'address' => $orderData['shipping_address']['address'],
                'city' => $orderData['shipping_address']['city'],
                'state' => $orderData['shipping_address']['state'] ?? null,
                
                'zip' => $orderData['shipping_address']['zip'] ?? null,
                'country' => $orderData['shipping_address']['country'],
            ]);

            // Save billing address if different
            $billing = null;
            if ($orderData['address_option'] == 'billing') {
                $billing = Address::create([
                    'user_id' => $user->id,
                    'type' => 'billing',
                    'name' => $orderData['billing_address']['name'],
                    'phone' => $orderData['billing_address']['phone'],
                    'address' => $orderData['billing_address']['address'],
                    'city' => $orderData['billing_address']['city'],
                    'state' => $orderData['billing_address']['state'] ?? null,
                    'zip' => $orderData['billing_address']['zip'] ?? null,
                    'country' => $orderData['billing_address']['country'],
                ]);
            }

                     $totalQty = $items->sum('quantity');

            // Save proof image
            $path = $this->uploadImage($request->file('proof_image'), 'proof_image');

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address_id' => $shipping->id,
                'billing_address_id' => $billing ? $billing->id : $shipping->id,
                'shipping_method' => $orderData['shipping_method'],
                'payment_method' => $orderData['payment_method'],
                'total' => $total,
                'shipping_cost' => $shippingCost,

                'total_quantity' => $totalQty,
                'order_note' => $orderData['order_note'] ?? null,
                'status' => 'awaiting_verification',
                'payment_proof' => $path,
            ]);
            // dd($order);
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }
            $user->notify(new OrderPlacedNotification($order->load('items.product.galleries')));

            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            return response()->json(['status' => true, 'message' => 'Proof uploaded! Thank you for your order.You will be get an Email Confirmation']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Order failed!']);
        }
    }

    public function index(Request $request)
{
    $status = $request->input('status', 'all');

    $orders = Order::with(['items.product.galleries'])
        ->where('user_id', Auth::id())
        ->when($status !== 'all', function ($query) use ($status) {
            if ($status === 'pending') {
                $query->whereIn('status', ['pending', 'awaiting_verification']);
            } else {
                $query->where('status', $status);
            }
        })
        ->orderByDesc('created_at')
        ->get();

    $suggestedProducts = Product::with('galleries')->inRandomOrder()->take(6)->get();

    if ($request->ajax()) {
        return view('front.default.order.partials.orders_list', compact('orders'))->render();
    }

    return view('front.default.order.index', compact('orders', 'suggestedProducts'));
}

}
