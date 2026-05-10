<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $item) {
            $variant = ProductVariant::with('product.images', 'product.store')->find($item['variant_id']);
            if ($variant) {
                $lineTotal = $variant->price * $item['quantity'];
                $cartItems[] = [
                    'variant' => $variant,
                    'quantity' => $item['quantity'],
                    'line_total' => $lineTotal,
                ];
                $subtotal += $lineTotal;
            }
        }

        $tax = round($subtotal * 0.1, 2);
        $grandTotal = round($subtotal + $tax, 2);

        return view('checkout.index', compact('cartItems', 'subtotal', 'tax', 'grandTotal'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address_line' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'required|in:stripe,paypal,manual',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Determine organization from first product in cart
        $firstVariant = ProductVariant::with('product.store')->find(array_values($cart)[0]['variant_id']);
        $orgId = $firstVariant?->product?->store?->organization_id;

        if (!$orgId) {
            return back()->with('error', 'Unable to process order.');
        }

        try {
            $order = DB::transaction(function () use ($request, $cart, $orgId) {
                // Create or find customer
                $customer = Customer::firstOrCreate(
                    ['email' => $request->email, 'organization_id' => $orgId],
                    ['name' => $request->name, 'phone' => $request->phone]
                );

                // Create address
                $address = Address::create([
                    'customer_id' => $customer->id,
                    'label' => 'Shipping',
                    'recipient_name' => $request->name,
                    'phone' => $request->phone,
                    'address_line' => $request->address_line,
                    'city' => $request->city,
                    'province' => $request->province,
                    'postal_code' => $request->postal_code,
                    'country' => 'US',
                    'is_default' => false,
                ]);

                $subtotal = 0;
                $orderItems = [];

                foreach ($cart as $item) {
                    $variant = ProductVariant::with('product')->findOrFail($item['variant_id']);

                    if ($variant->stock_quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for {$variant->name}");
                    }

                    $lineTotal = $variant->price * $item['quantity'];
                    $subtotal += $lineTotal;

                    $orderItems[] = [
                        'variant' => $variant,
                        'quantity' => $item['quantity'],
                        'unit_price' => $variant->price,
                        'total_price' => $lineTotal,
                    ];

                    // Decrement stock
                    $variant->decrement('stock_quantity', $item['quantity']);
                }

                $tax = round($subtotal * 0.1, 2);
                $grandTotal = round($subtotal + $tax, 2);

                // Create order
                $order = Order::create([
                    'organization_id' => $orgId,
                    'customer_id' => $customer->id,
                    'address_id' => $address->id,
                    'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'status' => 'pending',
                    'subtotal' => $subtotal,
                    'tax_total' => $tax,
                    'shipping_total' => 0,
                    'discount_total' => 0,
                    'grand_total' => $grandTotal,
                    'placed_at' => now(),
                ]);

                // Create order items
                foreach ($orderItems as $oi) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_variant_id' => $oi['variant']->id,
                        'product_name' => $oi['variant']->product->name . ' - ' . $oi['variant']->name,
                        'sku' => $oi['variant']->sku,
                        'quantity' => $oi['quantity'],
                        'unit_price' => $oi['unit_price'],
                        'total_price' => $oi['total_price'],
                    ]);
                }

                // Create payment
                Payment::create([
                    'order_id' => $order->id,
                    'gateway' => $request->payment_method,
                    'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
                    'status' => 'paid',
                    'amount' => $grandTotal,
                    'gateway_payload' => json_encode(['method' => $request->payment_method, 'ref' => Str::random(8)]),
                    'paid_at' => now(),
                ]);

                return $order;
            });

            // Clear cart
            session()->forget('cart');

            return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function success(Order $order)
    {
        $order->load(['items', 'customer', 'payments']);
        return view('checkout.success', compact('order'));
    }
}
