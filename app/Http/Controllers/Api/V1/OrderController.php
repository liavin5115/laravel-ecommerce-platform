<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Http\Requests\V1\StoreOrderRequest;
use App\Http\Resources\V1\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['customer', 'items'])
            ->latest('placed_at')
            ->simplePaginate(15);
            
        return OrderResource::collection($orders);
    }

    public function show(Order $order)
    {
        $order->loadMissing(['customer', 'items']);
        return new OrderResource($order);
    }

    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

        $order = DB::transaction(function () use ($validated) {
            $subtotal = 0;
            $itemsData = [];

            // Calculate totals and gather item data
            foreach ($validated['items'] as $itemReq) {
                $variant = ProductVariant::findOrFail($itemReq['product_variant_id']);
                
                // Prevent over-ordering (simplified, without locking for now)
                abort_if($variant->stock_quantity < $itemReq['quantity'], 422, "Insufficient stock for {$variant->name}");
                
                $lineTotal = $variant->price * $itemReq['quantity'];
                $subtotal += $lineTotal;

                $itemsData[] = [
                    'id' => Str::uuid()->toString(),
                    'product_variant_id' => $variant->id,
                    'product_name' => $variant->product->name . ' - ' . $variant->name,
                    'sku' => $variant->sku,
                    'quantity' => $itemReq['quantity'],
                    'unit_price' => $variant->price,
                    'total_price' => $lineTotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                
                // Deduct stock (simple approach)
                $variant->decrement('stock_quantity', $itemReq['quantity']);
            }

            $order = Order::create([
                'organization_id' => $validated['organization_id'],
                'customer_id' => $validated['customer_id'],
                'address_id' => $validated['address_id'] ?? null,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'status' => 'pending',
                'subtotal' => $subtotal,
                'grand_total' => $subtotal, // simplified, assuming no tax/shipping yet
                'placed_at' => now(),
            ]);

            // Insert items
            foreach ($itemsData as &$itemData) {
                $itemData['order_id'] = $order->id;
            }
            OrderItem::insert($itemsData);

            return $order;
        });

        $order->load(['customer', 'items']);
        
        return new OrderResource($order);
    }
}
