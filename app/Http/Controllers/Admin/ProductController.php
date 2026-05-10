<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function create()
    {
        $org = auth()->user()->organizations()->first();
        $stores = $org ? Store::where('organization_id', $org->id)->get() : collect();
        $categories = Category::all();

        return view('dashboard.products.create', compact('stores', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'store_id' => 'required|exists:stores,id',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'product_type' => 'required|in:physical,digital',
            'image_url' => 'nullable|url',
            'variants' => 'nullable|array',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.sku' => 'required|string|max:255|unique:product_variants,sku',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.weight' => 'nullable|numeric|min:0',
        ]);

        $slug = Str::slug($request->name);
        $count = Product::where('slug', 'like', $slug . '%')->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $product = Product::create([
            'store_id' => $request->store_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'product_type' => $request->product_type,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'is_active' => true,
        ]);

        if ($request->image_url) {
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $request->image_url,
                'sort_order' => 0,
            ]);
        }

        if ($request->variants) {
            foreach ($request->variants as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $v['name'],
                    'sku' => $v['sku'],
                    'price' => $v['price'],
                    'stock_quantity' => $v['stock_quantity'],
                    'weight' => $v['weight'] ?? null,
                ]);
            }
        }

        return redirect()->route('dashboard.products')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $org = auth()->user()->organizations()->first();
        $stores = $org ? Store::where('organization_id', $org->id)->get() : collect();
        $categories = Category::all();
        $product->load(['images', 'variants']);

        return view('dashboard.products.edit', compact('product', 'stores', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'store_id' => 'required|exists:stores,id',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'product_type' => 'required|in:physical,digital',
            'is_active' => 'boolean',
            'image_url' => 'nullable|url',
        ]);

        $product->update([
            'store_id' => $request->store_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'product_type' => $request->product_type,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->image_url) {
            $product->images()->delete();
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $request->image_url,
                'sort_order' => 0,
            ]);
        }

        return redirect()->route('dashboard.products')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->variants()->delete();
        $product->images()->delete();
        $product->delete();

        return redirect()->route('dashboard.products')->with('success', 'Product deleted successfully.');
    }
}
