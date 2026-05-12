@extends('layouts.dashboard')

@section('title', 'Seller Dashboard')

@section('content')
@include('partials.dashboard-switcher')

<!-- Page Title -->
<div class="flex items-center justify-between">
    <h1 class="text-2xl font-semibold text-slate-900">Dashboard</h1>
    <div class="text-sm text-textMuted">
        Welcome back, <span class="font-medium text-slate-900">{{ auth()->user()->name }}</span>
    </div>
</div>

<!-- BEGIN: Metric Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
    <!-- Card 1: Total Revenue -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
        <div class="flex items-center space-x-3 mb-4">
            <div class="p-2.5 bg-successBg/50 rounded-xl text-success">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </div>
            <h3 class="text-sm font-medium text-slate-600">Total Revenue</h3>
        </div>
        <p class="text-2xl font-bold text-slate-900">${{ number_format($stats['total_revenue'], 2) }}</p>
    </div>
    <!-- Card 2: Total Orders -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
        <div class="flex items-center space-x-3 mb-4">
            <div class="p-2.5 bg-infoBg/50 rounded-xl text-info">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </div>
            <h3 class="text-sm font-medium text-slate-600">Total Orders</h3>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_orders']) }}</p>
    </div>
    <!-- Card 3: Total Products -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
        <div class="flex items-center space-x-3 mb-4">
            <div class="p-2.5 bg-warningBg/50 rounded-xl text-warning">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </div>
            <h3 class="text-sm font-medium text-slate-600">Total Products</h3>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_products']) }}</p>
    </div>
    <!-- Card 4: Total Customers -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
        <div class="flex items-center space-x-3 mb-4">
            <div class="p-2.5 bg-dangerBg/50 rounded-xl text-danger">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </div>
            <h3 class="text-sm font-medium text-slate-600">Total Customers</h3>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_customers']) }}</p>
    </div>
</div>
<!-- END: Metric Cards -->

<!-- BEGIN: Recent Orders Section -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <!-- Section Header -->
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-900">Recent Orders</h2>
        <a href="{{ route('dashboard.orders') }}" class="text-sm font-medium text-info hover:text-blue-700 transition-colors">View All</a>
    </div>
    <!-- Data Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Order ID</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Customer</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Status</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Total</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Date</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($recentOrders as $order)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $order->customer?->name ?? '—' }}</td>
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
                        <td class="px-6 py-4 text-slate-900">${{ number_format($order->grand_total, 2) }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $order->placed_at?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-6 py-4 text-right">
                            <a class="text-sm text-info hover:text-blue-700 font-medium" href="{{ route('admin.orders.show', $order) }}">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            No recent orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- END: Recent Orders Section -->
@endsection

@section('scripts')
    @if(session('success_launch'))
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        window.addEventListener('load', () => {
            const duration = 5 * 1000;
            const animationEnd = Date.now() + duration;
            const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }

            const interval = setInterval(function() {
                const timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                const particleCount = 50 * (timeLeft / duration);
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } });
                confetti({ ...defaults, particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } });
            }, 250);
        });
    </script>
    @endif
@endsection
