@extends('layouts.dashboard')

@section('title', 'Order Details #' . $order->order_number)

@section('content')
<!-- Page Title & Actions -->
<div class="flex items-center justify-between">
    <div>
        <a href="{{ route('buyer.orders') }}" class="text-sm font-medium text-info hover:text-blue-700 flex items-center mb-2 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            Back to Orders
        </a>
        <h1 class="text-2xl font-semibold text-slate-900">Order #{{ $order->order_number }}</h1>
        <p class="text-sm text-textMuted mt-1">Placed on {{ $order->placed_at->format('F j, Y \a\t h:i A') }}</p>
    </div>
    <div class="flex items-center space-x-3">
        @php
            $statusClasses = [
                'pending' => 'bg-warningBg text-yellow-700 border-warning/20',
                'processing' => 'bg-infoBg text-info border-info/20',
                'shipped' => 'bg-infoBg text-info border-info/20',
                'delivered' => 'bg-successBg text-success border-success/20',
                'cancelled' => 'bg-dangerBg text-danger border-danger/20',
            ];
            $class = $statusClasses[$order->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
        @endphp
        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold {{ $class }} border">
            {{ ucfirst($order->status) }}
        </span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Details -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Items Section -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="text-lg font-semibold text-slate-900">Items Purchased</h3>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($order->items as $item)
                    <div class="p-6 flex items-center">
                        <div class="h-16 w-16 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-bold text-slate-900">{{ $item->product_name }}</h4>
                                    <p class="text-xs text-textMuted mt-1">SKU: {{ $item->sku }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-slate-900">${{ number_format($item->total_price, 2) }}</p>
                                    <p class="text-xs text-textMuted mt-1">{{ $item->quantity }} x ${{ number_format($item->unit_price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tracking Section -->
        @if($order->shipments->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h3 class="text-lg font-semibold text-slate-900">Shipment Tracking</h3>
                </div>
                <div class="p-6 space-y-6">
                    @foreach($order->shipments as $shipment)
                        <div class="flex items-start space-x-4">
                            <div class="p-3 bg-infoBg/30 rounded-xl text-info">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">{{ $shipment->courier }}</p>
                                <p class="text-sm text-textMuted mt-1">Tracking Number: <span class="text-slate-700 font-mono">{{ $shipment->tracking_number }}</span></p>
                                <div class="mt-3">
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold uppercase tracking-wider">
                                        {{ str_replace('_', ' ', $shipment->shipment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar Details -->
    <div class="space-y-8">
        <!-- Order Summary Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900 mb-6">Order Summary</h3>
            <div class="space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-textMuted">Subtotal</span>
                    <span class="font-medium text-slate-900">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-textMuted">Shipping</span>
                    <span class="font-medium text-slate-900">${{ number_format($order->shipping_total, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-textMuted">Tax</span>
                    <span class="font-medium text-slate-900">${{ number_format($order->tax_total, 2) }}</span>
                </div>
                @if($order->discount_total > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-success font-medium">Discount</span>
                        <span class="font-medium text-success">-${{ number_format($order->discount_total, 2) }}</span>
                    </div>
                @endif
                <div class="pt-4 border-t border-slate-100 flex justify-between">
                    <span class="font-bold text-slate-900 text-lg">Total</span>
                    <span class="font-bold text-indigo-600 text-lg">${{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Store & Shipping Info -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-8">
            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Purchased From</h4>
                <div class="flex items-center">
                    <div class="h-8 w-8 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500 mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>
                    <span class="font-bold text-slate-900">{{ $order->organization->name ?? 'Store' }}</span>
                </div>
            </div>

            <div>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Shipping Address</h4>
                @if($order->address)
                    <div class="text-sm text-slate-600 space-y-1">
                        <p class="font-bold text-slate-900">{{ $order->address->recipient_name }}</p>
                        <p>{{ $order->address->address_line }}</p>
                        <p>{{ $order->address->city }}, {{ $order->address->province }} {{ $order->address->postal_code }}</p>
                        <p>{{ $order->address->country }}</p>
                        <p class="pt-2 text-textMuted"><span class="font-medium text-slate-700">Phone:</span> {{ $order->address->phone }}</p>
                    </div>
                @else
                    <p class="text-sm text-slate-400">No address details.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection