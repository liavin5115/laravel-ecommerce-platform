@extends('layouts.front')

@section('title', 'Marketplace - Explore Products')

@section('content')
<div class="bg-white">
    <!-- Header/Hero -->
    <div class="bg-gray-50 border-b border-gray-200">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900">Explore Marketplace</h1>
            <p class="mt-4 text-base text-gray-500">Discover a curated collection of premium digital and physical products from top creators.</p>
            
            <!-- Search & Filters Bar -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                <form action="{{ route('public.products.index') }}" method="GET" class="flex-1 flex gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search products...">
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg shadow-indigo-500/30 transition">
                        Search
                    </button>
                </form>
                
                <div class="flex gap-2">
                    <div class="relative inline-block text-left" x-data="{ open: false }">
                        <button @click="open = !open" type="button" class="inline-flex items-center gap-x-1.5 rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            {{ request('category') ? ucfirst(request('category')) : 'Categories' }}
                            <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-xl bg-white shadow-2xl ring-1 ring-black ring-opacity-5 focus:outline-none">
                            <div class="py-1">
                                <a href="{{ route('public.products.index') }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-50 {{ !request('category') ? 'bg-indigo-50 text-indigo-700 font-medium' : '' }}">All Categories</a>
                                @foreach($categories as $category)
                                    <a href="{{ route('public.products.index', ['category' => $category->slug]) }}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-50 {{ request('category') == $category->slug ? 'bg-indigo-50 text-indigo-700 font-medium' : '' }}">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        @if($products->isEmpty())
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-100 mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">No products found</h2>
                <p class="mt-2 text-gray-500">We couldn't find any products matching your criteria. Try adjusting your search or filters.</p>
                <div class="mt-8">
                    <a href="{{ route('public.products.index') }}" class="text-indigo-600 font-semibold hover:text-indigo-500">Clear all filters &rarr;</a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            
            <div class="mt-16 border-t border-gray-100 pt-10">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
