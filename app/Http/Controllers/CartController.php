<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $variant = ProductVariant::with('product.images', 'product.store')->find($item['variant_id']);
            if ($variant) {
                $lineTotal = $variant->price * $item['quantity'];
                $cartItems[] = [
                    'key' => $key,
                    'variant' => $variant,
                    'quantity' => $item['quantity'],
                    'line_total' => $lineTotal,
                ];
                $subtotal += $lineTotal;
            }
        }

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'integer|min:1',
        ]);

        $variant = ProductVariant::findOrFail($request->variant_id);
        $cart = session()->get('cart', []);
        $key = $request->variant_id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $request->input('quantity', 1);
        } else {
            $cart[$key] = [
                'variant_id' => $variant->id,
                'quantity' => $request->input('quantity', 1),
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item added to cart!');
    }

    public function update(Request $request, string $key)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function remove(string $key)
    {
        $cart = session()->get('cart', []);
        unset($cart[$key]);
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
