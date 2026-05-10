@extends('layouts.front')
@section('title', 'Shopping Cart - ' . config('app.name'))
@section('content')
<div class="bg-white">
    <div class="mx-auto max-w-2xl px-4 pt-16 pb-24 sm:px-6 lg:max-w-7xl lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Shopping Cart</h1>

        @if(session('success'))
            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
        @endif

        <div class="mt-12 lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-12 xl:gap-x-16">
            <section aria-labelledby="cart-heading" class="lg:col-span-7">
                <h2 id="cart-heading" class="sr-only">Items in your shopping cart</h2>
                <ul role="list" class="divide-y divide-gray-200 border-b border-t border-gray-200">
                    @forelse($cartItems as $item)
                        <li class="flex py-6 sm:py-10">
                            <div class="flex-shrink-0 w-24 h-24 rounded-lg overflow-hidden bg-gray-100">
                                @if($item['variant']->product->images->count())
                                    <img src="{{ $item['variant']->product->images->first()->path }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-gray-400"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                                @endif
                            </div>
                            <div class="ml-4 flex flex-1 flex-col justify-between sm:ml-6">
                                <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-700">{{ $item['variant']->product->name }}</h3>
                                        <p class="mt-1 text-sm text-gray-500">{{ $item['variant']->name }}</p>
                                        <p class="mt-1 text-sm font-medium text-gray-900">${{ number_format($item['variant']->price, 2) }}</p>
                                    </div>
                                    <div class="mt-4 sm:mt-0 sm:pr-9">
                                        <form method="POST" action="{{ route('cart.update', $item['key']) }}" class="flex items-center gap-2">
                                            @csrf @method('PATCH')
                                            <select name="quantity" onchange="this.form.submit()" class="rounded-md border-gray-300 text-sm py-1.5 focus:ring-indigo-500 focus:border-indigo-500">
                                                @for($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}" @selected($item['quantity'] == $i)>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </form>
                                        <form method="POST" action="{{ route('cart.remove', $item['key']) }}" class="absolute top-0 right-0">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="inline-flex p-2 text-gray-400 hover:text-red-500 transition"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                        </form>
                                    </div>
                                </div>
                                <p class="mt-4 text-sm font-medium text-gray-900">Line Total: ${{ number_format($item['line_total'], 2) }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Your cart is empty</h3>
                            <p class="mt-1 text-sm text-gray-500">Looks like you haven't added anything yet.</p>
                            <div class="mt-6"><a href="{{ url('/') }}" class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700">Start Shopping</a></div>
                        </li>
                    @endforelse
                </ul>
            </section>

            <section aria-labelledby="summary-heading" class="mt-16 rounded-2xl bg-gray-50 border border-gray-100 px-4 py-6 sm:p-6 lg:col-span-5 lg:mt-0 lg:p-8">
                <h2 id="summary-heading" class="text-lg font-medium text-gray-900">Order summary</h2>
                <dl class="mt-6 space-y-4 text-sm text-gray-600">
                    <div class="flex items-center justify-between">
                        <dt>Subtotal</dt>
                        <dd class="font-medium text-gray-900">${{ number_format($subtotal, 2) }}</dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                        <dt>Tax (10%)</dt>
                        <dd class="font-medium text-gray-900">${{ number_format($subtotal * 0.1, 2) }}</dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-4 text-base font-medium text-gray-900">
                        <dt>Order total</dt>
                        <dd>${{ number_format($subtotal * 1.1, 2) }}</dd>
                    </div>
                </dl>
                <div class="mt-6">
                    @if(count($cartItems) > 0)
                        <a href="{{ route('checkout.show') }}" class="block w-full text-center rounded-xl bg-indigo-600 px-4 py-3 text-base font-medium text-white shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition">Proceed to Checkout</a>
                    @else
                        <button disabled class="block w-full rounded-xl bg-gray-300 px-4 py-3 text-base font-medium text-gray-500 cursor-not-allowed">Checkout</button>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
