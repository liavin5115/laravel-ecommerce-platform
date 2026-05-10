<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Coupon: {{ $coupon->code }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}">
                    @csrf @method('PUT')
                    @if($errors->any())<div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg"><ul class="list-disc list-inside text-sm text-red-600">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
                    <div class="space-y-6">
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Code *</label><input type="text" name="code" value="{{ old('code', $coupon->code) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white uppercase"></div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Discount Type *</label><select name="discount_type" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"><option value="percentage" @selected($coupon->discount_type==='percentage')>Percentage</option><option value="fixed" @selected($coupon->discount_type==='fixed')>Fixed</option></select></div>
                            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Discount Value *</label><input type="number" name="discount_value" step="0.01" min="0" value="{{ old('discount_value', $coupon->discount_value) }}" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Minimum Order ($)</label><input type="number" name="minimum_order" step="0.01" min="0" value="{{ old('minimum_order', $coupon->minimum_order) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                            <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Usage Limit</label><input type="number" name="usage_limit" min="0" value="{{ old('usage_limit', $coupon->usage_limit) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                        </div>
                        <div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expires At</label><input type="date" name="expires_at" value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></div>
                    </div>
                    <div class="mt-8 flex items-center gap-4">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/30">Update Coupon</button>
                        <a href="{{ route('dashboard.coupons') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
