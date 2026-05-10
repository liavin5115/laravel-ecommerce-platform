<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Store: {{ $store->name }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.stores.update', $store) }}">
                    @csrf @method('PUT')
                    @if($errors->any())<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg"><ul class="list-disc list-inside text-sm text-red-600">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                    <div class="space-y-6">
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Store Name *</label><input type="text" name="name" value="{{ old('name', $store->name) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo URL</label><input type="url" name="logo_url" value="{{ old('logo_url', $store->logo_url) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label><textarea name="description" rows="4" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description', $store->description) }}</textarea></div>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" @checked($store->is_active) class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Active</span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-8 flex items-center gap-4">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30">Update Store</button>
                        <a href="{{ route('dashboard.stores') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
