<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit: {{ $product->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.products.update', $product) }}">
                    @csrf @method('PUT')

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product Name *</label>
                            <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product Type *</label>
                            <select name="product_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="physical" @selected($product->product_type === 'physical')>Physical</option>
                                <option value="digital" @selected($product->product_type === 'digital')>Digital</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Store *</label>
                            <select name="store_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($stores as $store)<option value="{{ $store->id }}" @selected($product->store_id === $store->id)>{{ $store->name }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                            <select name="category_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($categories as $cat)<option value="{{ $cat->id }}" @selected($product->category_id === $cat->id)>{{ $cat->name }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price *</label>
                            <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $product->price) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Compare Price</label>
                            <input type="number" name="compare_price" step="0.01" min="0" value="{{ old('compare_price', $product->compare_price) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Image URL</label>
                        <input type="url" name="image_url" value="{{ old('image_url', $product->images->first()?->path) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <label class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" @checked($product->is_active) class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Active (visible on storefront)</span>
                        </label>
                    </div>

                    <!-- Existing Variants (read-only summary) -->
                    @if($product->variants->count())
                        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Current Variants</h3>
                            <div class="space-y-2">
                                @foreach($product->variants as $variant)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-sm">
                                        <div>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $variant->name }}</span>
                                            <span class="text-gray-500 dark:text-gray-400 ml-2">SKU: {{ $variant->sku }}</span>
                                        </div>
                                        <div class="flex items-center gap-4 text-gray-500 dark:text-gray-400">
                                            <span>${{ number_format($variant->price, 2) }}</span>
                                            <span>Stock: {{ $variant->stock_quantity }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 flex items-center gap-4">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-lg shadow-indigo-500/30">Update Product</button>
                        <a href="{{ route('dashboard.products') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">Cancel</a>
                    </div>
                </form>

                <!-- Delete -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">Delete Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
