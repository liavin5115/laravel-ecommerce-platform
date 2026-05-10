@extends('layouts.front')
@section('title', 'Checkout - ' . config('app.name'))
@section('content')
<div class="bg-white">
    <div class="mx-auto max-w-2xl px-4 pt-16 pb-24 sm:px-6 lg:max-w-7xl lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Checkout</h1>

        @if(session('error'))
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('checkout.process') }}" class="mt-12 lg:grid lg:grid-cols-12 lg:gap-x-12 xl:gap-x-16">
            @csrf

            <!-- Shipping Info -->
            <section class="lg:col-span-7">
                <h2 class="text-lg font-medium text-gray-900 mb-6">Shipping Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                        <input type="text" name="address_line" value="{{ old('address_line') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                        <input type="text" name="city" value="{{ old('city') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Province/State *</label>
                        <input type="text" name="province" value="{{ old('province') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code *</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method *</label>
                        <select name="payment_method" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="stripe">Credit Card (Stripe)</option>
                            <option value="paypal">PayPal</option>
                            <option value="manual">Bank Transfer</option>
                        </select>
                    </div>
                </div>
            </section>

            <!-- Order Summary -->
            <section class="mt-10 lg:mt-0 lg:col-span-5">
                <div class="rounded-2xl bg-gray-50 border border-gray-100 p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h2>
                    <ul class="divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                            <li class="flex py-4">
                                <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden bg-gray-100">
                                    @if($item['variant']->product->images->count())
                                        <img src="{{ $item['variant']->product->images->first()->path }}" class="h-full w-full object-cover">
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <h3 class="text-sm font-medium text-gray-900">{{ $item['variant']->product->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $item['variant']->name }} × {{ $item['quantity'] }}</p>
                                </div>
                                <p class="text-sm font-medium text-gray-900">${{ number_format($item['line_total'], 2) }}</p>
                            </li>
                        @endforeach
                    </ul>
                    <dl class="mt-4 space-y-3 border-t border-gray-200 pt-4 text-sm text-gray-600">
                        <div class="flex justify-between"><dt>Subtotal</dt><dd class="font-medium text-gray-900">${{ number_format($subtotal, 2) }}</dd></div>
                        <div class="flex justify-between"><dt>Tax (10%)</dt><dd class="font-medium text-gray-900">${{ number_format($tax, 2) }}</dd></div>
                        <div class="flex justify-between border-t border-gray-200 pt-3 text-base font-medium text-gray-900"><dt>Total</dt><dd>${{ number_format($grandTotal, 2) }}</dd></div>
                    </dl>
                    <button type="submit" class="mt-6 w-full rounded-xl bg-indigo-600 px-4 py-3 text-base font-medium text-white shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition">Place Order</button>
                </div>
            </section>
        </form>
    </div>
</div>
@endsection
