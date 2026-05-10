<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Create Product') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.products.store') }}" x-data="productForm()">
                    @csrf

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
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Product Type *</label>
                            <select name="product_type" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="physical">Physical</option>
                                <option value="digital">Digital</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Store *</label>
                            <select name="store_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Store</option>
                                @foreach($stores as $store)<option value="{{ $store->id }}">{{ $store->name }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                            <select name="category_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price *</label>
                            <input type="number" name="price" step="0.01" min="0" value="{{ old('price') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Compare Price</label>
                            <input type="number" name="compare_price" step="0.01" min="0" value="{{ old('compare_price') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Image URL</label>
                        <input type="url" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                    </div>

                    <!-- Variants -->
                    <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Product Variants</h3>
                            <button type="button" @click="addVariant()" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Add Variant
                            </button>
                        </div>
                        <template x-for="(variant, index) in variants" :key="index">
                            <div class="p-4 mb-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300" x-text="'Variant #' + (index + 1)"></span>
                                    <button type="button" @click="removeVariant(index)" class="text-red-500 hover:text-red-700 text-sm">Remove</button>
                                </div>
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                    <div>
                                        <input type="text" :name="'variants[' + index + '][name]'" x-model="variant.name" placeholder="Name" required class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <input type="text" :name="'variants[' + index + '][sku]'" x-model="variant.sku" placeholder="SKU" required class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <input type="number" :name="'variants[' + index + '][price]'" x-model="variant.price" step="0.01" min="0" placeholder="Price" required class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <input type="number" :name="'variants[' + index + '][stock_quantity]'" x-model="variant.stock_quantity" min="0" placeholder="Stock" required class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <input type="number" :name="'variants[' + index + '][weight]'" x-model="variant.weight" step="0.01" min="0" placeholder="Weight" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-8 flex items-center gap-4">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition shadow-lg shadow-indigo-500/30">Create Product</button>
                        <a href="{{ route('dashboard.products') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function productForm() {
            return {
                variants: [],
                addVariant() { this.variants.push({ name: '', sku: '', price: '', stock_quantity: '', weight: '' }); },
                removeVariant(index) { this.variants.splice(index, 1); }
            }
        }
    </script>
</x-app-layout>
