<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Create Category</h2></x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    @if($errors->any())<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg"><ul class="list-disc list-inside text-sm text-red-600">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                    <div class="space-y-6">
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category Name *</label><input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="e.g. Electronics"></div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Parent Category</label>
                            <select name="parent_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                <option value="">None</option>
                                @foreach($parentCategories as $parent)<option value="{{ $parent->id }}">{{ $parent->name }}</option>@endforeach
                            </select>
                        </div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Icon Class (optional)</label><input type="text" name="icon" value="{{ old('icon') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="e.g. heroicon-o-device-mobile"></div>
                    </div>
                    <div class="mt-8 flex items-center gap-4">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30">Create Category</button>
                        <a href="{{ route('dashboard.categories') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
