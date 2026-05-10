<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\V1\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with(['store', 'category', 'images', 'variants'])
            ->where('is_active', true)
            ->simplePaginate(15);
            
        return ProductResource::collection($products);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        abort_if(!$product->is_active, 404);
        $product->loadMissing(['store', 'category', 'images', 'variants']);
        
        return new ProductResource($product);
    }
}
