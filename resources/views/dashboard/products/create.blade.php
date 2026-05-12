@extends('layouts.dashboard')

@section('title', 'Create Product')

@section('content')
<div class="mb-6">
    <a href="{{ route('dashboard.products') }}" class="inline-flex items-center text-sm text-textMuted hover:text-slate-900 transition-colors mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        Back to Products
    </a>
    <h1 class="text-2xl font-bold text-slate-900">Create Product</h1>
    <p class="text-textMuted text-sm mt-1">Fill in the details to add a new product to your store.</p>
</div>

<form method="POST" action="{{ route('admin.products.store') }}" x-data="productForm()">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Basic Info -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Product Information</h3>
                
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors placeholder:text-slate-400" 
                               placeholder="e.g. Wireless Noise Cancelling Headphones">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="product_type" class="block text-sm font-bold text-slate-700 mb-2">Product Type <span class="text-danger">*</span></label>
                            <select name="product_type" id="product_type" required 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors">
                                <option value="physical">Physical Product</option>
                                <option value="digital">Digital Product</option>
                            </select>
                        </div>
                        <div>
                            <label for="store_id" class="block text-sm font-bold text-slate-700 mb-2">Store <span class="text-danger">*</span></label>
                            <select name="store_id" id="store_id" required 
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors">
                                <option value="">Select Store</option>
                                @foreach($stores as $store)<option value="{{ $store->id }}">{{ $store->name }}</option>@endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Description</label>
                        <textarea name="description" id="description" rows="6" 
                                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors placeholder:text-slate-400" 
                                  placeholder="Describe your product in detail...">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Variants Section -->
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Product Variants</h3>
                    <button type="button" @click="addVariant()" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-all border border-slate-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        Add Variant
                    </button>
                </div>
                
                <div class="space-y-4">
                    <template x-for="(variant, index) in variants" :key="index">
                        <div class="p-6 bg-slate-50/50 border border-slate-200 rounded-2xl relative group">
                            <button type="button" @click="removeVariant(index)" class="absolute -top-2 -right-2 bg-white border border-slate-200 text-red-500 p-1.5 rounded-full hover:bg-red-50 hover:text-red-600 transition-all shadow-sm opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            </button>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Variant Name</label>
                                    <input type="text" :name="'variants[' + index + '][name]'" x-model="variant.name" placeholder="Color, Size, etc." required 
                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-info focus:border-info transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">SKU</label>
                                    <input type="text" :name="'variants[' + index + '][sku]'" x-model="variant.sku" placeholder="SKU-001" required 
                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-info focus:border-info transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Price</label>
                                    <input type="number" :name="'variants[' + index + '][price]'" x-model="variant.price" step="0.01" min="0" placeholder="0.00" required 
                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-info focus:border-info transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Stock</label>
                                    <input type="number" :name="'variants[' + index + '][stock_quantity]'" x-model="variant.stock_quantity" min="0" placeholder="0" required 
                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-info focus:border-info transition-colors">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Weight (kg)</label>
                                    <input type="number" :name="'variants[' + index + '][weight]'" x-model="variant.weight" step="0.01" min="0" placeholder="0.00" 
                                           class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-info focus:border-info transition-colors">
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <div x-show="variants.length === 0" class="text-center py-10 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/30">
                        <p class="text-sm text-textMuted italic">No variants added. Click "Add Variant" if this product has multiple options.</p>
                    </div>
                </div>
            </div>
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
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)<option value="{{ $cat->id }}">{{ $cat->name }}</option>@endforeach
                        </select>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-bold text-slate-700 mb-2">Base Price <span class="text-danger">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-sm font-bold">$</div>
                            <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price') }}" required 
                                   class="w-full pl-8 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors placeholder:text-slate-400" 
                                   placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <label for="compare_price" class="block text-sm font-bold text-slate-700 mb-2">Compare Price (Discount)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-sm font-bold">$</div>
                            <input type="number" name="compare_price" id="compare_price" step="0.01" min="0" value="{{ old('compare_price') }}" 
                                   class="w-full pl-8 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors placeholder:text-slate-400" 
                                   placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Product Image</h3>
                
                <div>
                    <label for="image_url" class="block text-sm font-bold text-slate-700 mb-2">URL</label>
                    <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}" 
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors placeholder:text-slate-400" 
                           placeholder="https://example.com/image.jpg">
                    <p class="mt-2 text-xs text-textMuted italic">Paste a link to your product image.</p>
                </div>
            </div>

            <button type="submit" class="w-full py-4 px-6 bg-sidebarDark text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 hover:shadow-xl active:scale-[0.98]">
                Publish Product
            </button>
            <a href="{{ route('dashboard.products') }}" class="block w-full py-4 text-center text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
                Cancel
            </a>
        </div>
    </div>
</form>

<script>
    function productForm() {
        return {
            variants: [],
            addVariant() { 
                this.variants.push({ name: '', sku: '', price: '', stock_quantity: '', weight: '' }); 
            },
            removeVariant(index) { 
                this.variants.splice(index, 1); 
            }
        }
    }
</script>
@endsection
