@extends('layouts.dashboard')

@section('title', 'Seller Onboarding Requests')

@section('content')
<!-- Page Title -->
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900">Seller Requests</h1>
        <p class="text-sm text-textMuted mt-1">Review and approve new marketplace merchant requests</p>
    </div>
</div>

<!-- Requests Table Card -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    @if($requests->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-sm font-semibold text-slate-800">Applicant</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-800">Business Details</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-800">Plan</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-800">Status</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-800 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @foreach($requests as $req)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $req->user->name }}</div>
                                <div class="text-xs text-textMuted">{{ $req->user->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $req->org_name }}</div>
                                <div class="text-xs text-textMuted">{{ $req->store_name }} • <span class="capitalize">{{ $req->business_type }}</span></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-bold uppercase tracking-wider border border-indigo-100">
                                    {{ $req->plan }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-warningBg text-yellow-700 border-warning/20',
                                        'approved' => 'bg-successBg text-success border-success/20',
                                        'rejected' => 'bg-dangerBg text-danger border-danger/20',
                                    ];
                                    $class = $statusClasses[$req->status] ?? 'bg-slate-100 text-slate-600 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $class }} border">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($req->status === 'pending')
                                    <div class="flex items-center justify-end space-x-3">
                                        <form action="{{ route('super-admin.seller-requests.approve', $req) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-success hover:text-green-700 font-bold transition-colors">Approve</button>
                                        </form>
                                        <div class="w-px h-4 bg-slate-200"></div>
                                        <form action="{{ route('super-admin.seller-requests.reject', $req) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-danger hover:text-red-700 font-bold transition-colors">Reject</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">Processed</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
                {{ $requests->links() }}
            </div>
        @endif
    @else
        <div class="p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 text-slate-300 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 mb-2">No pending requests</h3>
            <p class="text-slate-500 max-w-sm mx-auto">There are no new seller onboarding requests to review at this time.</p>
        </div>
    @endif
</div>
@endsection