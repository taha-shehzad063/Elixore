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
    // Clear the order note from session on every cart load
    Session::forget('order_note');

    $cart = Cart::where('user_id', Auth::id())->where('status', 'active')->first();
    $items = $cart ? $cart->items()->with('product')->get() : collect();

  
    
    return view('front.default.cart.cart', compact('cart', 'items'));
}
    public function add(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::firstOrCreate([
            'user_id' => Auth::id(),
            'status' => 'active'
        ]);

        $product = Product::findOrFail($request->product_id);

        $item = CartItem::updateOrCreate(
            ['cart_id' => $cart->id, 'product_id' => $product->id],
            [
                'quantity' => $request->quantity,
                'price' => $product->price
            ]
        );

        return response()->json(['status' => true, 'message' => 'Added to cart!']);
    }

    public function update(Request $request, $itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $item->update(['quantity' => $request->quantity]);
        return response()->json(['status' => true]);
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
    $userId = Auth::id();

    // Check if product already in cart
    $exists = CartItem::whereHas('cart', function ($q) use ($userId) {
        $q->where('user_id', $userId);
    })->where('product_id', $productId)->exists();

    if ($exists) {
        return response()->json(['status' => false, 'message' => 'Already in cart']);
    }

    // Get product and its price
    $product = Product::findOrFail($productId);

    // Get or create the user's cart
    $cart = Cart::firstOrCreate(['user_id' => $userId]);

    // Add item to cart including price
    $cart->items()->create([
        'product_id' => $productId,
        'quantity' => $request->input('quantity', 1),
        'price' => $product->price,
    ]);

    return response()->json(['status' => true, 'message' => 'Product added to cart']);
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
