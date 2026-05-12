@extends('layouts.dashboard')

@section('title', 'Create Category')

@section('content')
<div class="mb-6">
    <a href="{{ route('dashboard.categories') }}" class="inline-flex items-center text-sm text-textMuted hover:text-slate-900 transition-colors mb-2">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        Back to Categories
    </a>
    <h1 class="text-2xl font-bold text-slate-900">Create Category</h1>
    <p class="text-textMuted text-sm mt-1">Organize your products with a new category.</p>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-8">
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-6">
            @csrf

            @if($errors->any())
                <div class="p-4 bg-dangerBg border border-danger/10 rounded-xl">
                    <ul class="list-disc list-inside text-sm text-danger font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                           class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors placeholder:text-slate-400" 
                           placeholder="e.g. Electronics & Gadgets">
                </div>

                <!-- Parent Category -->
                <div>
                    <label for="parent_id" class="block text-sm font-bold text-slate-700 mb-2">Parent Category</label>
                    <select name="parent_id" id="parent_id" 
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors">
                        <option value="">None (Top Level)</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id') == $parent->id)>{{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-bold text-slate-700 mb-2">Icon Class (optional)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </div>
                        <input type="text" name="icon" id="icon" value="{{ old('icon') }}" 
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors placeholder:text-slate-400" 
                               placeholder="e.g. heroicon-o-device-mobile">
                    </div>
                    <p class="mt-2 text-xs text-textMuted italic">Use Heroicon or FontAwesome class names.</p>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 flex items-center gap-4">
                <button type="submit" class="px-8 py-3 bg-sidebarDark text-white font-bold rounded-2xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 active:scale-[0.98]">
                    Create Category
                </button>
                <a href="{{ route('dashboard.categories') }}" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
