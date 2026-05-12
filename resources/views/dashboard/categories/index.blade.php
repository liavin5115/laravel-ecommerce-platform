@extends('layouts.dashboard')

@section('title', 'Categories')

@section('content')
<!-- Page Title -->
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900">Categories</h1>
        <p class="text-sm text-textMuted mt-1">Organize your products into logical sections</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-5 py-2.5 bg-sidebarDark text-white rounded-xl font-medium hover:bg-slate-800 transition-colors shadow-sm">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        Add New Category
    </a>
</div>

<!-- Categories Table Card -->
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Name</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Parent</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Slug</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800">Status</th>
                    <th class="px-6 py-4 text-sm font-semibold text-slate-800 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($categories as $category)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $category->name }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            @if($category->parent)
                                <span class="px-2 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs">
                                    {{ $category->parent->name }}
                                </span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-mono text-xs">{{ $category->slug }}</td>
                        <td class="px-6 py-4">
                            @if($category->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-successBg text-success border border-success/20">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-500 border border-slate-200">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-info hover:text-blue-700 font-medium transition-colors">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger hover:text-red-700 font-medium transition-colors">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories instanceof \Illuminate\Pagination\AbstractPaginator && $categories->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/30">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
