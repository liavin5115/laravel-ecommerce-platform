@extends('layouts.dashboard')

@section('title', 'Order Details ' . $order->order_number)

@section('content')
<!-- Page Title & Actions -->
<div class="flex items-center justify-between">
    <div>
        <a href="{{ route('dashboard.orders') }}" class="text-sm font-medium text-info hover:text-blue-700 flex items-center mb-2 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            Back to Orders
        </a>
        <h1 class="text-2xl font-semibold text-slate-900">Order {{ $order->order_number }}</h1>
        <p class="text-sm text-textMuted mt-1">Placed on {{ $order->placed_at?->format('F j, Y \a\t h:i A') }}</p>
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
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Items Table -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="text-lg font-semibold text-slate-900">Order Items</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-xs font-semibold text-slate-800 uppercase">Product</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-800 uppercase text-center">Qty</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-800 uppercase text-right">Unit Price</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-800 uppercase text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($order->items as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $item->product_name }}</div>
                                    <div class="text-xs text-textMuted font-mono">{{ $item->sku }}</div>
                                </td>
                                <td class="px-6 py-4 text-center font-medium text-slate-700">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right text-slate-600">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-6 py-4 text-right font-bold text-slate-900">${{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-6 bg-slate-50/50 border-t border-slate-100">
                <div class="flex flex-col items-end space-y-2">
                    <div class="flex justify-between w-full max-w-[240px] text-sm">
                        <span class="text-textMuted">Subtotal</span>
                        <span class="font-medium text-slate-900">${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between w-full max-w-[240px] text-sm">
                        <span class="text-textMuted">Tax</span>
                        <span class="font-medium text-slate-900">${{ number_format($order->tax_total, 2) }}</span>
                    </div>
                    <div class="flex justify-between w-full max-w-[240px] pt-2 border-t border-slate-200">
                        <span class="font-bold text-slate-900">Grand Total</span>
                        <span class="font-bold text-indigo-600 text-lg">${{ number_format($order->grand_total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipments -->
        @if($order->shipments->count())
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h3 class="text-lg font-semibold text-slate-900">Shipments</h3>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($order->shipments as $shipment)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex items-center">
                                <div class="p-2 bg-white rounded-lg border border-slate-200 mr-3 text-slate-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 text-sm">{{ $shipment->courier }}</p>
                                    <p class="text-xs text-textMuted font-mono">{{ $shipment->tracking_number }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $shipment->shipment_status === 'delivered' ? 'bg-successBg text-success' : 'bg-infoBg text-info' }} border border-current opacity-70">
                                {{ str_replace('_', ' ', $shipment->shipment_status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-8">
        <!-- Order Status Update -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Update Order Status</h3>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="space-y-4">
                @csrf @method('PATCH')
                <select name="status" class="w-full rounded-xl text-sm border-slate-200 bg-slate-50 focus:ring-info focus:border-info transition-colors">
                    @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                        <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full py-2.5 bg-sidebarDark text-white rounded-xl font-medium hover:bg-slate-800 transition-colors shadow-sm">
                    Save Changes
                </button>
            </form>
        </div>

        <!-- Customer & Payment -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-8">
            <div>
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Customer Details</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 mr-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $order->customer?->name ?? '—' }}</p>
                            <p class="text-xs text-textMuted">{{ $order->customer?->email ?? '—' }}</p>
                        </div>
                    </div>
                    @if($order->customer?->phone)
                        <p class="text-xs text-textMuted flex items-center">
                            <svg class="w-3.5 h-3.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            {{ $order->customer->phone }}
                        </p>
                    @endif
                </div>
            </div>

            <div>
                <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Payment Information</h3>
                @if($order->payments->count())
                    @php $payment = $order->payments->first(); @endphp
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-textMuted">Method</span>
                            <span class="font-bold text-slate-900 uppercase">{{ $payment->gateway }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-textMuted">Status</span>
                            <span class="font-bold {{ $payment->status === 'paid' ? 'text-success' : 'text-warning' }} uppercase">{{ $payment->status }}</span>
                        </div>
                        <div class="pt-2">
                            <p class="text-[10px] text-textMuted uppercase font-bold tracking-tighter">Transaction ID</p>
                            <p class="text-xs font-mono text-slate-700 truncate">{{ $payment->transaction_id }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-xs text-slate-400 italic">No payment recorded.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
