@extends('layouts.front')

@section('content')
<!-- Hero Section -->
<div class="relative overflow-hidden bg-[#0a0a0a] pt-16 sm:pt-24 lg:pt-32 pb-16 sm:pb-24 lg:pb-32">
    <!-- Decorative background elements -->
    <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
        <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
    </div>
    
    <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center relative z-10">
        <h1 class="mx-auto max-w-4xl font-display text-5xl font-bold tracking-tight text-white sm:text-7xl">
            Discover the best <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">digital & physical</span> products
        </h1>
        <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-gray-300">
            A premium multi-tenant marketplace for creators, builders, and makers. Start exploring our curated collection today.
        </p>
        <div class="mt-10 flex items-center justify-center gap-x-6">
            <a href="{{ route('public.products.index') }}" class="rounded-full bg-white px-8 py-3.5 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transition-all hover:scale-105">
                Start Exploring
            </a>
            <a href="{{ route('stores.onboarding') }}" class="text-sm font-semibold leading-6 text-white hover:text-indigo-300 transition-colors">
                Open a Store <span aria-hidden="true">→</span>
            </a>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div id="explore" class="bg-gray-50 dark:bg-[#0a0a0a] py-24 sm:py-32">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">Featured Products</h2>
            <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">Carefully curated items from our top vendors and creators.</p>
        </div>
        
        <div class="mt-16 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
            @forelse($products as $product)
                <x-product-card :product="$product" />
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">No products found</h3>
                    <p class="mt-1 text-gray-500 dark:text-gray-400">Check back later or explore different categories.</p>
                </div>
            @endforelse
        </div>
        
        @if($products->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
