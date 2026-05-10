<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'subtotal' => $this->subtotal,
            'tax_total' => $this->tax_total,
            'shipping_total' => $this->shipping_total,
            'discount_total' => $this->discount_total,
            'grand_total' => $this->grand_total,
            'placed_at' => $this->placed_at,
            'customer' => [
                'id' => $this->customer?->id,
                'name' => $this->customer?->name,
                'email' => $this->customer?->email,
            ],
            'items' => $this->items->map(fn($item) => [
                'id' => $item->id,
                'product_name' => $item->product_name,
                'sku' => $item->sku,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'total_price' => $item->total_price,
            ]),
        ];
    }
}
