<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Platform Administration') }}
            </h2>
            <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wider">Super Admin</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Global Revenue -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Platform Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">${{ number_format($stats['total_revenue'], 2) }}</p>
                </div>

                <!-- Total Orgs -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Organizations</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_organizations'] }}</p>
                </div>

                <!-- Total Products -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Products Listing</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_products'] }}</p>
                </div>

                <!-- Total Users -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-orange-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_users'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Recent Organizations -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Organizations</h3>
                        <a href="{{ route('super-admin.organizations.index') }}" class="text-indigo-600 hover:text-indigo-500 text-sm font-semibold">View All</a>
                    </div>
                    <div class="space-y-4">
                        @foreach($recentOrgs as $org)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-gray-700/50">
                                <div>
                                    <p class="font-bold text-gray-900 dark:text-white">{{ $org->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $org->stores->count() }} Stores • {{ $org->plan_type }}</p>
                                </div>
                                <span class="px-2 py-1 rounded-md text-xs font-bold {{ $org->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $org->is_active ? 'Active' : 'Suspended' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Global Orders -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Recent Platform Orders</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-xs text-gray-500 uppercase">
                                    <th class="pb-3 font-semibold">Order</th>
                                    <th class="pb-3 font-semibold">Store</th>
                                    <th class="pb-3 font-semibold">Total</th>
                                    <th class="pb-3 font-semibold text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                @foreach($recentOrders as $order)
                                    <tr class="border-t border-gray-100 dark:border-gray-700">
                                        <td class="py-3 font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</td>
                                        <td class="py-3 text-gray-500">{{ $order->organization?->name }}</td>
                                        <td class="py-3 font-bold">${{ number_format($order->grand_total, 2) }}</td>
                                        <td class="py-3 text-right">
                                            <span class="text-xs">{{ ucfirst($order->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
