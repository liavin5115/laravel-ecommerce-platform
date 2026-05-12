@extends('layouts.dashboard')

@section('title', 'Products')

@section('content')
<!-- Page Title -->
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900">Products</h1>
        <p class="text-sm text-textMuted mt-1">{{ $products instanceof \Illuminate\Pagination\AbstractPaginator ? $products->total() : $products->count() }} products found</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-5 py-2.5 bg-sidebarDark text-white rounded-xl font-medium hover:bg-slate-800 transition-colors shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        Add New Product
    </a>
</div>

<!-- Products Table Card -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Product</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Category</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Price</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Status</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Store</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($products as $product)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0">
                                    @if($product->images->count())
                                        <img class="h-full w-full object-cover" src="{{ $product->images->first()->path }}" alt="{{ $product->name }}">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-slate-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="font-bold text-slate-900">{{ $product->name }}</div>
                                    <div class="text-xs text-textMuted">{{ $product->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-medium">
                                {{ $product->category?->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">
                            ${{ number_format($product->price, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            @if($product->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-successBg text-success border border-success/20">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600 text-xs">
                            {{ $product->store?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-info hover:text-blue-700 font-medium transition-colors">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger hover:text-red-700 font-medium transition-colors">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            No products found. Start by adding your first product!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products instanceof \Illuminate\Pagination\AbstractPaginator && $products->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
