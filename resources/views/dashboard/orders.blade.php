@extends('layouts.dashboard')

@section('title', 'Orders')

@section('content')
<!-- Page Title -->
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900">Orders</h1>
        <p class="text-sm text-textMuted mt-1">{{ $orders instanceof \Illuminate\Pagination\AbstractPaginator ? $orders->total() : $orders->count() }} orders total</p>
    </div>
</div>

<!-- Orders Table Card -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Order #</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Customer</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Status</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Payment</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Total</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Date</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($orders as $order)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">
                            {{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-900">{{ $order->customer?->name ?? '—' }}</div>
                            <div class="text-xs text-textMuted">{{ $order->customer?->email ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4">
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
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $class }} border">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($order->payments->count() > 0)
                                @php $payment = $order->payments->first(); @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $payment->status === 'paid' ? 'bg-successBg text-success' : 'bg-warningBg text-yellow-700' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">
                            ${{ number_format($order->grand_total, 2) }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $order->placed_at?->format('M d, Y') ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-info hover:text-blue-700 font-medium transition-colors">View Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders instanceof \Illuminate\Pagination\AbstractPaginator && $orders->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
