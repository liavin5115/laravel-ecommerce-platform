<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function index()
    {
        $org = auth()->user()->organizations()->first();
        $stores = $org ? Store::where('organization_id', $org->id)->latest()->paginate(10) : collect();
        return view('dashboard.stores.index', compact('stores'));
    }

    public function create()
    {
        return view('dashboard.stores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
        ]);

        $org = auth()->user()->organizations()->first();

        Store::create([
            'organization_id' => $org->id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'logo_url' => $request->logo_url,
            'is_active' => true,
        ]);

        return redirect()->route('dashboard.stores')->with('success', 'Store created.');
    }

    public function edit(Store $store)
    {
        return view('dashboard.stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $store->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'logo_url' => $request->logo_url,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('dashboard.stores')->with('success', 'Store updated.');
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()->route('dashboard.stores')->with('success', 'Store deleted.');
    }
}
