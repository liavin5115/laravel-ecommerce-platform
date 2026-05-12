@extends('layouts.dashboard')

@section('title', 'Customers')

@section('content')
<!-- Page Title -->
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900">Customers</h1>
        <p class="text-sm text-textMuted mt-1">{{ $customers instanceof \Illuminate\Pagination\AbstractPaginator ? $customers->total() : $customers->count() }} customers found</p>
    </div>
</div>

<!-- Customers Table Card -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Customer</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Email</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Phone</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Orders</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Location</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Joined</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($customers as $customer)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($customer->name, 0, 1)) }}
                                </div>
                                <div class="ml-3">
                                    <div class="font-bold text-slate-900">{{ $customer->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $customer->email }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $customer->phone ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-infoBg text-info border border-info/10 text-xs font-bold">
                                {{ $customer->orders->count() }} Orders
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            @if($customer->addresses->first())
                                <span class="text-xs">{{ $customer->addresses->first()->city }}, {{ $customer->addresses->first()->country }}</span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs">{{ $customer->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            No customers found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers instanceof \Illuminate\Pagination\AbstractPaginator && $customers->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $customers->links() }}
        </div>
    @endif
</div>
@endsection
