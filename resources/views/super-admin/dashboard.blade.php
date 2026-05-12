@extends('layouts.dashboard')

@section('title', 'Platform Administration')

@section('content')
@include('partials.dashboard-switcher')

<!-- Page Title -->
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900">Platform Overview</h1>
        <p class="text-sm text-textMuted mt-1">Global administration and monitoring</p>
    </div>
    <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider border border-indigo-200">Super Admin</span>
</div>

<!-- BEGIN: Metric Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
    <!-- Total Revenue -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
        <div class="flex items-center space-x-3 mb-4">
            <div class="p-2.5 bg-successBg/50 rounded-xl text-success">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </div>
            <h3 class="text-sm font-medium text-slate-600">Global Revenue</h3>
        </div>
        <p class="text-2xl font-bold text-slate-900">${{ number_format($stats['total_revenue'], 2) }}</p>
    </div>
    <!-- Total Organizations -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
        <div class="flex items-center space-x-3 mb-4">
            <div class="p-2.5 bg-infoBg/50 rounded-xl text-info">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </div>
            <h3 class="text-sm font-medium text-slate-600">Organizations</h3>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_organizations']) }}</p>
    </div>
    <!-- Total Products Listing -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
        <div class="flex items-center space-x-3 mb-4">
            <div class="p-2.5 bg-warningBg/50 rounded-xl text-warning">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </div>
            <h3 class="text-sm font-medium text-slate-600">Total Products</h3>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_products']) }}</p>
    </div>
    <!-- Total Users -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
        <div class="flex items-center space-x-3 mb-4">
            <div class="p-2.5 bg-dangerBg/50 rounded-xl text-danger">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </div>
            <h3 class="text-sm font-medium text-slate-600">Total Users</h3>
        </div>
        <p class="text-2xl font-bold text-slate-900">{{ number_format($stats['total_users']) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Organizations -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-slate-900">Recent Organizations</h3>
            <a href="{{ route('super-admin.organizations.index') }}" class="text-sm font-medium text-info hover:text-blue-700 transition-colors">View All</a>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($recentOrgs as $org)
                <div class="p-5 flex items-center justify-between hover:bg-slate-50/50 transition-colors">
                    <div>
                        <p class="font-bold text-slate-900">{{ $org->name }}</p>
                        <p class="text-xs text-textMuted mt-0.5">{{ $org->stores->count() }} Stores • <span class="uppercase">{{ $org->plan_type }}</span></p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $org->is_active ? 'bg-successBg text-success border-success/20' : 'bg-dangerBg text-danger border-danger/20' }} border">
                        {{ $org->is_active ? 'Active' : 'Suspended' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Platform Orders -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="text-lg font-semibold text-slate-900">Recent Platform Orders</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-800 uppercase">Order</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-800 uppercase">Store</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-800 uppercase">Total</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-800 uppercase text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @foreach($recentOrders as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $order->organization?->name }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">${{ number_format($order->grand_total, 2) }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-xs font-medium">{{ ucfirst($order->status) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
