@extends('layouts.dashboard')

@section('title', 'Coupons')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Coupons</h1>
        <p class="text-textMuted text-sm mt-1">Manage discount codes and promotional offers.</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center px-4 py-2 bg-sidebarDark text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-all shadow-sm shadow-slate-200">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        New Coupon
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Discount</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Min. Order</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Usage</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Expires</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($coupons as $coupon)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1.5 bg-slate-100 text-slate-900 font-mono font-bold text-sm rounded-lg border border-slate-200">
                                {{ $coupon->code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900">
                                {{ $coupon->discount_type === 'percentage' ? $coupon->discount_value . '%' : '$' . number_format($coupon->discount_value, 2) }}
                            </div>
                            <div class="text-xs text-textMuted uppercase tracking-tight">{{ $coupon->discount_type }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            ${{ number_format($coupon->minimum_order, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-full max-w-[100px]">
                                <div class="flex items-center justify-between text-[10px] font-bold text-slate-400 mb-1 uppercase">
                                    <span>Used</span>
                                    <span>{{ round(($coupon->used_count / ($coupon->usage_limit ?: 100)) * 100) }}%</span>
                                </div>
                                <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-500 rounded-full" style="width: {{ ($coupon->used_count / ($coupon->usage_limit ?: 100)) * 100 }}%"></div>
                                </div>
                                <div class="mt-1 text-[10px] text-textMuted italic">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?: '∞' }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $isExpired = $coupon->expires_at && $coupon->expires_at->isPast();
                                $isFull = $coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit;
                            @endphp
                            @if($isExpired)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-dangerBg text-danger border border-danger/10 text-center">Expired</span>
                            @elseif($isFull)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-warningBg text-warning border border-warning/10 text-center">Maxed</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-successBg text-success border border-success/10 text-center">Active</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                            {{ $coupon->expires_at?->format('M d, Y') ?? 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="inline" onsubmit="return confirm('Delete coupon?');">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900">No coupons found</h3>
                                <p class="text-xs text-textMuted mt-1">Create your first discount code to start promoting.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($coupons instanceof \Illuminate\Pagination\AbstractPaginator && $coupons->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $coupons->links() }}
        </div>
    @endif
</div>
@endsection
