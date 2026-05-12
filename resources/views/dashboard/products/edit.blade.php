@extends('layouts.dashboard')

@section('title', 'Edit Product')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <a href="{{ route('dashboard.products') }}" class="inline-flex items-center text-sm text-textMuted hover:text-slate-900 transition-colors mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            Back to Products
        </a>
        <h1 class="text-2xl font-bold text-slate-900">Edit Product: {{ $product->name }}</h1>
        <p class="text-textMuted text-sm mt-1">Update product details, pricing, and availability.</p>
    </div>
    
    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product?');">
        @csrf @method('DELETE')
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 text-sm font-bold rounded-xl hover:bg-red-100 transition-all border border-red-100">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            Delete Product
        </button>
    </form>
</div>

<form method="POST" action="{{ route('admin.products.update', $product) }}">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Basic Info -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Product Information</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors placeholder:text-slate-400">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="product_type" class="block text-sm font-bold text-slate-700 mb-2">Product Type <span class="text-danger">*</span></label>
                            <select name="product_type" id="product_type" required 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors">
                                <option value="physical" @selected($product->product_type === 'physical')>Physical Product</option>
                                <option value="digital" @selected($product->product_type === 'digital')>Digital Product</option>
                            </select>
                        </div>
                        <div>
                            <label for="store_id" class="block text-sm font-bold text-slate-700 mb-2">Store <span class="text-danger">*</span></label>
                            <select name="store_id" id="store_id" required 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors">
                                @foreach($stores as $store)<option value="{{ $store->id }}" @selected($product->store_id === $store->id)>{{ $store->name }}</option>@endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="6" 
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors placeholder:text-slate-400">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <!-- Status Toggle -->
                    <div class="flex items-center space-x-3 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                        <div class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none" x-data="{ on: {{ $product->is_active ? 'true' : 'false' }} }">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" @checked($product->is_active) x-model="on" class="sr-only">
                            <span :class="on ? 'bg-indigo-600' : 'bg-slate-300'" class="absolute inset-0 rounded-full transition-colors duration-200"></span>
                            <span :class="on ? 'translate-x-6' : 'translate-x-1'" class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform duration-200 ease-in-out shadow-sm"></span>
                        </div>
                        <span class="text-sm font-bold text-slate-700">Product is Active (Visible on Storefront)</span>
                    </div>
                </div>
            </div>

            <!-- Current Variants (Static) -->
            @if($product->variants->count())
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-6">Product Variants</h3>
                    <div class="space-y-3">
                        @foreach($product->variants as $variant)
                            <div class="flex items-center justify-between p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ $variant->name }}</div>
                                    <div class="text-[10px] text-textMuted uppercase font-bold tracking-wider mt-1">SKU: {{ $variant->sku }}</div>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="text-right">
                                        <div class="text-xs font-bold text-slate-900">${{ number_format($variant->price, 2) }}</div>
                                        <div class="text-[10px] text-textMuted uppercase font-bold">Price</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs font-bold text-slate-900">{{ $variant->stock_quantity }}</div>
                                        <div class="text-[10px] text-textMuted uppercase font-bold">Stock</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Organization -->
        <div class="space-y-8">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Categorization</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="category_id" class="block text-sm font-bold text-slate-700 mb-2">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" required 
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors">
                            @foreach($categories as $cat)<option value="{{ $cat->id }}" @selected($product->category_id === $cat->id)>{{ $cat->name }}</option>@endforeach
                        </select>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-bold text-slate-700 mb-2">Base Price <span class="text-danger">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-sm font-bold">$</div>
                            <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required 
                                   class="w-full pl-8 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors placeholder:text-slate-400">
                        </div>
                    </div>

                    <div>
                        <label for="compare_price" class="block text-sm font-bold text-slate-700 mb-2">Compare Price</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-sm font-bold">$</div>
                            <input type="number" name="compare_price" id="compare_price" step="0.01" min="0" value="{{ old('compare_price', $product->compare_price) }}" 
                                   class="w-full pl-8 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors placeholder:text-slate-400">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Product Image</h3>
                
                <div>
                    <label for="image_url" class="block text-sm font-bold text-slate-700 mb-2">URL</label>
                    <input type="url" name="image_url" id="image_url" value="{{ old('image_url', $product->images->first()?->path) }}" 
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors placeholder:text-slate-400">
                    
                    @if($product->images->first())
                        <div class="mt-4 rounded-xl overflow-hidden border border-slate-100">
                            <img src="{{ $product->images->first()->path }}" alt="Preview" class="w-full h-40 object-cover">
                        </div>
                    @endif
                </div>
            </div>

            <button type="submit" class="w-full py-4 px-6 bg-sidebarDark text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 active:scale-[0.98]">
                Save Changes
            </button>
            <a href="{{ route('dashboard.products') }}" class="block w-full py-4 text-center text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
                Cancel
            </a>
        </div>
    </div>
</form>
@endsection
