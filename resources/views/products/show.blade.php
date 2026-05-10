@extends('layouts.front')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')
<div class="bg-white">
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-8">
            <!-- Image gallery -->
            <div class="flex flex-col-reverse">
                <div class="aspect-[4/3] w-full overflow-hidden rounded-2xl bg-gray-100">
                    @if($product->images->count() > 0)
                        <img src="{{ $product->images->first()->path }}" alt="{{ $product->name }}" class="h-full w-full object-cover object-center sm:rounded-lg">
                    @else
                        <div class="flex h-full items-center justify-center text-gray-400">
                            <svg class="h-24 w-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product info -->
            <div class="mt-10 px-4 sm:px-0 lg:mt-0">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $product->name }}</h1>
                
                <div class="mt-3">
                    <h2 class="sr-only">Product information</h2>
                    <p class="text-3xl tracking-tight text-gray-900">${{ number_format($product->price, 2) }}</p>
                    @if($product->compare_price)
                        <p class="text-lg text-gray-500 line-through">${{ number_format($product->compare_price, 2) }}</p>
                    @endif
                </div>

                <!-- Reviews snippet -->
                <div class="mt-3">
                    <h3 class="sr-only">Reviews</h3>
                    <div class="flex items-center">
                        <div class="flex items-center text-yellow-400">
                            <!-- Star SVGs -->
                            @for($i=0; $i<5; $i++)
                                <svg class="h-5 w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z" clip-rule="evenodd" /></svg>
                            @endfor
                        </div>
                        <p class="ml-3 text-sm text-gray-500">{{ $product->reviews->count() }} reviews</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="sr-only">Description</h3>
                    <div class="space-y-6 text-base text-gray-700">
                        <p>{{ $product->description ?? 'No description provided.' }}</p>
                    </div>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="mt-6" x-data="{ quantity: 1, variantId: '{{ $product->variants->first()?->id }}' }">
                    @csrf
                    <input type="hidden" name="variant_id" :value="variantId">
                    <input type="hidden" name="quantity" :value="quantity">

                    <!-- Variants -->
                    @if($product->variants->count() > 0)
                        <div class="mt-8">
                            <div class="flex items-center justify-between">
                                <h2 class="text-sm font-medium text-gray-900">Options</h2>
                            </div>
                            <fieldset class="mt-2">
                                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-6">
                                    @foreach($product->variants as $variant)
                                        <label class="flex items-center justify-center rounded-md border py-3 px-3 text-sm font-medium uppercase hover:bg-gray-50 cursor-pointer focus:outline-none sm:flex-1"
                                            :class="variantId === '{{ $variant->id }}' ? 'border-indigo-500 ring-2 ring-indigo-500 bg-indigo-50' : 'border-gray-200 text-gray-900 bg-white'">
                                            <input type="radio" name="variant_radio" value="{{ $variant->id }}" class="sr-only" x-model="variantId">
                                            <span id="variant-label-{{ $variant->id }}">{{ $variant->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </fieldset>
                        </div>
                    @endif

                    <div class="mt-10 flex border-t border-gray-200 pt-10">
                        <button type="submit" class="flex max-w-xs flex-1 items-center justify-center rounded-xl bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50 sm:w-full transition-colors shadow-lg shadow-indigo-500/30">
                            Add to Cart
                        </button>
                        <button type="button" class="ml-4 flex items-center justify-center rounded-xl px-3 py-3 text-gray-400 hover:bg-gray-100 hover:text-gray-500 transition-colors">
                            <svg class="h-6 w-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            <span class="sr-only">Add to favorites</span>
                        </button>
                    </div>
                </form>

                <section aria-labelledby="details-heading" class="mt-12">
                    <h2 id="details-heading" class="sr-only">Additional details</h2>
                    <div class="divide-y divide-gray-200 border-t border-gray-200">
                        <div class="py-6">
                            <h3 class="text-sm font-medium text-gray-900">Store Information</h3>
                            <div class="mt-2 text-sm text-gray-500">
                                Sold by <strong>{{ $product->store?->name ?? 'Unknown Store' }}</strong>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Related products -->
        @if($relatedProducts->count() > 0)
        <section aria-labelledby="related-heading" class="mt-24 border-t border-gray-200 pt-16">
            <h2 id="related-heading" class="text-xl font-bold text-gray-900">Customers also bought</h2>
            <div class="mt-8 grid grid-cols-1 gap-y-12 sm:grid-cols-2 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8">
                @foreach($relatedProducts as $related)
                    <x-product-card :product="$related" />
                @endforeach
            </div>
        </section>
        @endif
    </div>
</div>
@endsection
