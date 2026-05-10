<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'compare_price' => $this->compare_price,
            'product_type' => $this->product_type,
            'store' => [
                'id' => $this->store?->id,
                'name' => $this->store?->name,
            ],
            'category' => [
                'id' => $this->category?->id,
                'name' => $this->category?->name,
            ],
            'images' => $this->images->map(fn($image) => [
                'id' => $image->id,
                'path' => $image->path,
                'sort_order' => $image->sort_order,
            ]),
            'variants' => $this->variants->map(fn($variant) => [
                'id' => $variant->id,
                'sku' => $variant->sku,
                'name' => $variant->name,
                'price' => $variant->price,
                'stock_quantity' => $variant->stock_quantity,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
