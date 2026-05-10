@extends('layouts.front')
@section('title', 'Order Confirmed - ' . config('app.name'))
@section('content')
<div class="bg-white">
    <div class="mx-auto max-w-3xl px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-6">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Thank you for your order!</h1>
            <p class="mt-2 text-lg text-gray-500">Your order <span class="font-semibold text-indigo-600">{{ $order->order_number }}</span> has been placed successfully.</p>
        </div>

        <div class="mt-12 bg-gray-50 border border-gray-100 rounded-2xl p-6 sm:p-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Details</h2>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div><dt class="text-gray-500">Order Number</dt><dd class="font-medium text-gray-900">{{ $order->order_number }}</dd></div>
                <div><dt class="text-gray-500">Date</dt><dd class="font-medium text-gray-900">{{ $order->placed_at->format('M d, Y h:i A') }}</dd></div>
                <div><dt class="text-gray-500">Payment</dt><dd class="font-medium text-gray-900">{{ ucfirst($order->payments->first()?->gateway ?? '—') }}</dd></div>
                <div><dt class="text-gray-500">Status</dt><dd><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">{{ ucfirst($order->status) }}</span></dd></div>
            </dl>

            <div class="mt-6 border-t border-gray-200 pt-6">
                <h3 class="text-sm font-medium text-gray-900 mb-3">Items</h3>
                <ul class="divide-y divide-gray-200">
                    @foreach($order->items as $item)
                        <li class="flex justify-between py-3 text-sm">
                            <div>
                                <span class="font-medium text-gray-900">{{ $item->product_name }}</span>
                                <span class="text-gray-500 ml-2">× {{ $item->quantity }}</span>
                            </div>
                            <span class="font-medium text-gray-900">${{ number_format($item->total_price, 2) }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <dl class="mt-4 space-y-2 border-t border-gray-200 pt-4 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Subtotal</dt><dd class="font-medium text-gray-900">${{ number_format($order->subtotal, 2) }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Tax</dt><dd class="font-medium text-gray-900">${{ number_format($order->tax_total, 2) }}</dd></div>
                <div class="flex justify-between text-base font-medium text-gray-900 pt-2 border-t border-gray-200"><dt>Total</dt><dd>${{ number_format($order->grand_total, 2) }}</dd></div>
            </dl>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection
