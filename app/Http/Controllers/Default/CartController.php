<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Carbon;
class CartController extends Controller
{
public function index()
    {
       
        // Clear order note on cart load
        Session::forget('order_note');

        // Fetch cart based on session_id or user_id if authenticated
        $cart = Cart::where(function ($q) {
            $q->where('session_id', Session::getId());
            if (Auth::check()) {
                $q->orWhere('user_id', Auth::id());
            }
        })->where('status', 'active')->first();

        $items = $cart ? $cart->items()->with('product')->get() : collect();

        return view('front.default.cart.cart', compact('cart', 'items'));
    }

public function add(Request $request)
{
    // dd($request->all());
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity'   => 'nullable|integer|min:1',
        'total_price'=> 'nullable|numeric|min:0',
        'selected_color' => 'nullable|array' // multiple colors
    ]);

    $search = [
        'session_id' => Session::getId(),
        'status'     => 'active'
    ];

    // Get or create active cart
    $cart = Cart::where($search)->first();
    if (!$cart) {
        $cart = Cart::create([
            'session_id' => Session::getId(),
            'user_id'    => Auth::check() ? Auth::id() : null,
            'status'     => 'active'
        ]);
    } elseif (Auth::check() && $cart->user_id !== Auth::id()) {
        $cart->update(['user_id' => Auth::id()]);
    }

    $product = Product::findOrFail($request->product_id);
    $price = $request->filled('total_price') ? $request->total_price : $product->price;

    // Handle multiple color selection
    $availableColors = explode(',', $product->color);
    $selectedColors = $request->input('selected_color', [trim($availableColors[0])]); // default first color

    // Ensure all selected colors are valid
    $selectedColors = array_filter($selectedColors, fn($c) => in_array(trim($c), $availableColors));

    // Store as comma-separated string
    $selectedColorsString = implode(',', $selectedColors);

    // Add or update the cart item
   $qwdvnbqw= CartItem::updateOrCreate(
        [
            'cart_id'    => $cart->id,
            'product_id' => $product->id
        ],
        [
            'quantity'       => $request->quantity ?? 1,
            'price'          => $price,
            'selected_color' => $selectedColorsString
        ]
    );
    return response()->json(['status' => true, 'message' => 'Added to cart!']);
}





 public function update(Request $request, $itemId)
{
    $item = CartItem::findOrFail($itemId);
    $product = $item->product;

    // Get available colors
    $colors = explode(',', $product->color);

    // Get selected colors from request, fallback to first color if none
    $selectedColors = $request->selected_color ?? [trim($colors[0])];

    // Ensure it's an array
    if (!is_array($selectedColors)) {
        $selectedColors = [$selectedColors];
    }

    // Store as comma-separated string (or JSON if you prefer)
    $selectedColorsString = implode(',', $selectedColors);

    // Update cart item
    $item->update([
        'quantity'       => $request->quantity ?? $item->quantity,
        'price'          => $product->price, // price per item
        'selected_color' => $selectedColorsString,
    ]);

    return response()->json(['status' => true, 'message' => 'Cart item updated successfully!']);
}



    public function remove($itemId)
    {
        CartItem::findOrFail($itemId)->delete();
        return response()->json(['status' => true]);
    }

    public function note(Request $request)
    {
        // Save note to session or cart (customize as needed)
        session(['order_note' => $request->order_note]);
        return response()->json(['status' => true]);
    }
  public function addwishlist(Request $request)
    {
        $productId = $request->input('product_id');

        // Validate request
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        // Check if product already in wishlist
        $exists = CartItem::whereHas('cart', function ($q) {
            $q->where('session_id', Session::getId());
            if (Auth::check()) {
                $q->orWhere('user_id', Auth::id());
            }
        })->where('product_id', $productId)->exists();

        if ($exists) {
            return response()->json(['status' => false, 'message' => 'Already in wishlist']);
        }

        // Get product and its price
        $product = Product::findOrFail($productId);

        // Get or create the cart based on session_id, include user_id only if authenticated
        $cart = Cart::firstOrCreate(
            [
                'session_id' => Session::getId(),
                'status' => 'active'
            ],
            [
                'user_id' => Auth::check() ? Auth::id() : null
            ]
        );

        // If user is authenticated and cart has no user_id, update it
        if (Auth::check() && is_null($cart->user_id)) {
            $cart->update(['user_id' => Auth::id()]);
        }

        // Add item to wishlist including price
        $cart->items()->create([
            'product_id' => $productId,
            'quantity' => $request->input('quantity', 1),
            'price' => $product->price,
        ]);

        return response()->json(['status' => true, 'message' => 'Product added to wishlist']);
    }
public function updatecart(Request $request, $id)
{
    $item = CartItem::findOrFail($id);
    $item->quantity = $request->quantity;
    $item->save();

    return response()->json(['status' => true]);
}
public function saveNote(Request $request)
{
    $request->validate([
        'order_note' => 'nullable|string|max:1000'
    ]);

    session(['order_note' => $request->order_note]);

    return response()->json([
        'status' => true,
        'note' => $request->order_note,
        'message' => 'Note saved'
    ]);
}

public function deleteNote()
{
    session()->forget('order_note');

    return response()->json([
        'status' => true,
        'message' => 'Note deleted'
    ]);
}

}
