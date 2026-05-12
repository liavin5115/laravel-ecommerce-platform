@extends('layouts.dashboard')

@section('title', 'My Account')

@section('content')
@include('partials.dashboard-switcher')

<!-- Page Title -->
<div class="flex items-center justify-between">
    <h1 class="text-2xl font-semibold text-slate-900">My Account</h1>
    <div class="text-sm text-textMuted">
        Welcome back, <span class="font-medium text-slate-900">{{ auth()->user()->name }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Welcome Card -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900 mb-2">Hello, {{ auth()->user()->name }}!</h2>
            <p class="text-slate-600 mb-6">
                From your account dashboard, you can easily track your recent orders, manage shipping addresses, and update your profile information.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('buyer.orders') }}" class="px-5 py-2.5 bg-sidebarDark text-white rounded-xl font-medium hover:bg-slate-800 transition-colors">
                    View My Orders
                </a>
                <a href="{{ route('profile.edit') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl font-medium hover:bg-slate-50 transition-colors">
                    Edit Profile
                </a>
                @if(!auth()->user()->organizations()->exists() && !\App\Models\SellerRequest::where('user_id', auth()->id())->where('status', 'pending')->exists())
                    <a href="{{ route('stores.onboarding') }}" class="px-5 py-2.5 bg-success text-white rounded-xl font-medium hover:bg-green-600 transition-colors">
                        Become a Seller
                    </a>
                @elseif(\App\Models\SellerRequest::where('user_id', auth()->id())->where('status', 'pending')->exists())
                    <span class="px-5 py-2.5 bg-slate-100 text-slate-500 rounded-xl font-medium border border-slate-200 cursor-not-allowed">
                        Seller Request Pending
                    </span>
                @endif
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Recent Orders</h2>
                <a href="{{ route('buyer.orders') }}" class="text-sm font-medium text-info hover:text-blue-700">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-sm font-semibold text-slate-800">Order ID</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-800">Status</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-800">Total</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-800">Date</th>
                            <th class="px-6 py-4 text-sm font-semibold text-slate-800 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-900">#{{ $order->order_number }}</td>
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
                                <td class="px-6 py-4 text-slate-600">{{ $order->placed_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a class="text-sm text-info hover:text-blue-700 font-medium" href="{{ route('buyer.orders.show', $order) }}">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    You haven't placed any orders yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Sidebar -->
    <div class="space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Quick Links</h3>
            <ul class="space-y-3">
                <li>
                    <a href="{{ route('buyer.orders') }}" class="flex items-center text-slate-600 hover:text-info transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        My Orders
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile.edit') }}" class="flex items-center text-slate-600 hover:text-info transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        Account Settings
                    </a>
                </li>
                <li class="opacity-50">
                    <span class="flex items-center text-slate-500 cursor-not-allowed">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        Addresses <span class="ml-auto text-[10px] font-bold uppercase">Coming Soon</span>
                    </span>
                </li>
                <li class="opacity-50">
                    <span class="flex items-center text-slate-500 cursor-not-allowed">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        Support Tickets <span class="ml-auto text-[10px] font-bold uppercase">Coming Soon</span>
                    </span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection