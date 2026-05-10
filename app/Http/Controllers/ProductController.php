<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['images', 'store', 'category'])
            ->where('is_active', true);

        // Search filter
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $products = $query->latest()->paginate(12);
        $categories = \App\Models\Category::whereNull('parent_id')->with('children')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        abort_if(!$product->is_active, 404);
        
        $product->loadMissing(['store', 'category', 'images', 'variants.inventoryMovements', 'reviews.customer']);
        
        $relatedProducts = Product::with(['images', 'store'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();
            
        return view('products.show', compact('product', 'relatedProducts'));
    }
}
