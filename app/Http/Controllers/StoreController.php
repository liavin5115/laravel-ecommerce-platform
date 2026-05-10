<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function show(Store $store)
    {
        abort_if(!$store->is_active, 404);

        $products = Product::with(['images', 'store'])
            ->where('store_id', $store->id)
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('stores.show', compact('store', 'products'));
    }
}
