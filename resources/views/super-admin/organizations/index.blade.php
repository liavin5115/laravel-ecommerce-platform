@extends('layouts.dashboard')

@section('title', 'Manage Organizations')

@section('content')
<!-- Page Title -->
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900">Manage Organizations</h1>
        <p class="text-sm text-textMuted mt-1">Monitor and control marketplace organizations</p>
    </div>
</div>

<!-- Organizations Table Card -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Organization</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Plan</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Status</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Metrics</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($organizations as $org)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $org->name }}</div>
                            <div class="text-xs text-textMuted">{{ $org->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('super-admin.organizations.update-plan', $org) }}" method="POST" class="flex items-center">
                                @csrf
                                @method('PATCH')
                                <select name="plan_type" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-200 text-slate-700 rounded-lg focus:ring-info focus:border-info block p-1.5 transition-colors">
                                    @foreach(['free', 'basic', 'pro', 'enterprise'] as $plan)
                                        <option value="{{ $plan }}" {{ $org->plan_type == $plan ? 'selected' : '' }}>{{ ucfirst($plan) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $org->is_active ? 'bg-successBg text-success border-success/20' : 'bg-dangerBg text-danger border-danger/20' }} border">
                                {{ $org->is_active ? 'Active' : 'Suspended' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-4 text-xs text-textMuted">
                                <span class="flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                    {{ $org->stores->count() }} Stores
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                    {{ $org->users->count() }} Members
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <form action="{{ route('super-admin.organizations.toggle-status', $org) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm font-bold {{ $org->is_active ? 'text-danger hover:text-red-700' : 'text-success hover:text-green-700' }} transition-colors">
                                    {{ $org->is_active ? 'Suspend' : 'Activate' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            No organizations found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($organizations->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $organizations->links() }}
        </div>
    @endif
</div>
@endsection
