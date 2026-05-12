@extends('layouts.dashboard')

@section('title', 'Stores')

@section('content')
<!-- Page Title -->
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900">Stores</h1>
        <p class="text-sm text-textMuted mt-1">Manage your organization's store outlets</p>
    </div>
    <a href="{{ route('admin.stores.create') }}" class="inline-flex items-center px-5 py-2.5 bg-sidebarDark text-white rounded-xl font-medium hover:bg-slate-800 transition-colors shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        Add New Store
    </a>
</div>

<!-- Stores Table Card -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Store</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Slug</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Status</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($stores as $store)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-12 w-12 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0">
                                    @if($store->logo_url)
                                        <img class="h-full w-full object-cover" src="{{ $store->logo_url }}" alt="{{ $store->name }}">
                                    @else
                                        <div class="h-full w-full flex items-center justify-center text-slate-400">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="font-bold text-slate-900">{{ $store->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-mono text-xs">{{ $store->slug }}</td>
                        <td class="px-6 py-4">
                            @if($store->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-successBg text-success border border-success/20">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.stores.edit', $store) }}" class="text-info hover:text-blue-700 font-medium transition-colors">Edit</a>
                            <form action="{{ route('admin.stores.destroy', $store) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this store?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger hover:text-red-700 font-medium transition-colors">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            No stores found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stores instanceof \Illuminate\Pagination\AbstractPaginator && $stores->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $stores->links() }}
        </div>
    @endif
</div>
@endsection
