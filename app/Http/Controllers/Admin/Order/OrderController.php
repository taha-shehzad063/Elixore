<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CheckoutOption;
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
}
