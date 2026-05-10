<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Coupons') }}</h2>
            <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">+ New Coupon</a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-sm text-green-700 dark:text-green-400">{{ session('success') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead><tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Min Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Usage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Expires</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($coupons as $coupon)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-mono font-bold text-gray-900 dark:text-white">{{ $coupon->code }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($coupon->discount_type) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $coupon->discount_type === 'percentage' ? $coupon->discount_value . '%' : '$' . number_format($coupon->discount_value, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">${{ number_format($coupon->minimum_order, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?: '∞' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $coupon->expires_at?->format('M d, Y') ?? 'Never' }}</td>
                                    <td class="px-6 py-4 text-sm space-x-2">
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="inline" onsubmit="return confirm('Delete coupon?');">@csrf @method('DELETE')<button class="text-red-600 hover:text-red-900">Delete</button></form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">No coupons yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($coupons instanceof \Illuminate\Pagination\AbstractPaginator && $coupons->hasPages())
                    <div class="mt-6">{{ $coupons->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
