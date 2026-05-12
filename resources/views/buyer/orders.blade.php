@extends('layouts.dashboard')

@section('title', 'My Orders')

@section('content')
<!-- Page Title -->
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900">My Orders</h1>
        <p class="text-sm text-textMuted mt-1">Track and manage your recent purchases</p>
    </div>
</div>

<!-- Orders Table Card -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-sm font-semibold text-slate-800">Order ID</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-800">Store</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-800">Status</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-800">Total</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-800">Date</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-800 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($orders as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-900">#{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $order->organization->name ?? 'Unknown Store' }}</td>
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
                            <td class="px-6 py-4 font-bold text-slate-900">${{ number_format($order->grand_total, 2) }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $order->placed_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('buyer.orders.show', $order) }}" class="text-info hover:text-blue-700 font-medium transition-colors">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                {{ $orders->links() }}
            </div>
        @endif
    @else
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 mb-2">No orders yet</h3>
            <p class="text-slate-500 mb-6 max-w-sm mx-auto">Looks like you haven't made any purchases yet. Explore our marketplace to find amazing products!</p>
            <a href="{{ route('public.products.index') }}" class="inline-flex items-center px-6 py-3 bg-sidebarDark text-white rounded-xl font-medium hover:bg-slate-800 transition-colors">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection