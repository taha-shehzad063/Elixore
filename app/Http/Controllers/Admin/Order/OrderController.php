<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CheckoutOption;
use App\Models\Order;
class OrderController extends Controller
{
    public function index()
    {
        $options = CheckoutOption::orderBy('type')->get();
        return view('admin.checkout-options.index', compact('options'));
    }

    // Show create form
    public function create()
    {
        return view('admin.checkout-options.create');
    }

    // Store new option
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'key' => 'required|string',
            'label' => 'nullable|string',
            'shipping_cost' => 'nullable|numeric',
            'message' => 'nullable|string',
                        'bank_name'      => 'nullable|string',

            'account_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'status' => 'nullable|in:0,1',
        ]);

        CheckoutOption::create($request->all());

        return redirect()->route('admin.checkout-options.index')->with('success', 'Checkout option added.');
    }

    // Show edit form
    public function edit($id)
    {
        $option = CheckoutOption::findOrFail($id);
        return view('admin.checkout-options.edit', compact('option'));
    }

    // Update existing option
    public function update(Request $request, $id)
    {
        $option = CheckoutOption::findOrFail($id);

        $request->validate([
            'type' => 'required|string',
            'key' => 'required|string',
            'label' => 'nullable|string',
            'shipping_cost' => 'nullable|numeric',
            'message' => 'nullable|string',
            'account_name' => 'nullable|string',
                        'bank_name'      => 'nullable|string',

            'account_number' => 'nullable|string',
            'status' => 'nullable|in:0,1',
        ]);

        $option->update($request->all());

        return redirect()->route('admin.checkout-options.index')->with('success', 'Checkout option updated.');
    }

    // Delete option
    public function destroy($id)
    {
        $option = CheckoutOption::findOrFail($id);
        $option->delete();

        return redirect()->back()->with('success', 'Checkout option deleted.');
    }
  public function orders()
    {
        // Eager load relationships to avoid N+1 queries
        $orders = Order::with(['items.product', 'shippingAddress', 'billingAddress'])->get();
        return view('admin.frontend.order.index', compact('orders'));
    }

    /**
     * Update the status of the specified order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $order->update(['status' => $request->status]);
        return redirect()->route('admin.orders')->with('success', 'Order status updated successfully.');
    }

}
