<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Order {{ $order->order_number }}</h2>
            <a href="{{ route('dashboard.orders') }}" class="text-sm text-indigo-600 hover:text-indigo-800">← Back to Orders</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-sm text-green-700 dark:text-green-400">{{ session('success') }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Order Info -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase mb-3">Order Info</h3>
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Status</dt>
                            <dd>
                                @php $colors = ['pending'=>'bg-yellow-100 text-yellow-800','processing'=>'bg-blue-100 text-blue-800','shipped'=>'bg-indigo-100 text-indigo-800','delivered'=>'bg-green-100 text-green-800','cancelled'=>'bg-red-100 text-red-800']; @endphp
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $colors[$order->status] ?? '' }}">{{ ucfirst($order->status) }}</span>
                            </dd>
                        </div>
                        <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Date</dt><dd class="text-gray-900 dark:text-white">{{ $order->placed_at?->format('M d, Y') }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Total</dt><dd class="font-bold text-gray-900 dark:text-white">${{ number_format($order->grand_total, 2) }}</dd></div>
                    </dl>
                    <!-- Status Update -->
                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        @csrf @method('PATCH')
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Update Status</label>
                        <div class="flex gap-2">
                            <select name="status" class="flex-1 rounded-lg text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                    <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">Save</button>
                        </div>
                    </form>
                </div>

                <!-- Customer -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase mb-3">Customer</h3>
                    <dl class="space-y-2 text-sm">
                        <div><dt class="text-gray-500 dark:text-gray-400">Name</dt><dd class="text-gray-900 dark:text-white">{{ $order->customer?->name ?? '—' }}</dd></div>
                        <div><dt class="text-gray-500 dark:text-gray-400">Email</dt><dd class="text-gray-900 dark:text-white">{{ $order->customer?->email ?? '—' }}</dd></div>
                        <div><dt class="text-gray-500 dark:text-gray-400">Phone</dt><dd class="text-gray-900 dark:text-white">{{ $order->customer?->phone ?? '—' }}</dd></div>
                    </dl>
                </div>

                <!-- Payment -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase mb-3">Payment</h3>
                    @if($order->payments->count())
                        @php $payment = $order->payments->first(); @endphp
                        <dl class="space-y-2 text-sm">
                            <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Gateway</dt><dd class="text-gray-900 dark:text-white">{{ ucfirst($payment->gateway) }}</dd></div>
                            <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Status</dt>
                                <dd><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($payment->status) }}</span></dd>
                            </div>
                            <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Transaction</dt><dd class="text-gray-900 dark:text-white font-mono text-xs">{{ $payment->transaction_id }}</dd></div>
                        </dl>
                    @else
                        <p class="text-sm text-gray-400">No payment recorded.</p>
                    @endif
                </div>
            </div>

            <!-- Items -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase mb-4">Order Items</h3>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">SKU</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Qty</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Unit Price</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $item->product_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 font-mono">{{ $item->sku }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">${{ number_format($item->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <dl class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 space-y-1 text-sm text-right">
                    <div class="flex justify-end gap-8"><dt class="text-gray-500">Subtotal</dt><dd class="w-24 text-gray-900 dark:text-white">${{ number_format($order->subtotal, 2) }}</dd></div>
                    <div class="flex justify-end gap-8"><dt class="text-gray-500">Tax</dt><dd class="w-24 text-gray-900 dark:text-white">${{ number_format($order->tax_total, 2) }}</dd></div>
                    <div class="flex justify-end gap-8 font-bold"><dt class="text-gray-900 dark:text-white">Grand Total</dt><dd class="w-24 text-gray-900 dark:text-white">${{ number_format($order->grand_total, 2) }}</dd></div>
                </dl>
            </div>

            <!-- Shipments -->
            @if($order->shipments->count())
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase mb-4">Shipments</h3>
                    @foreach($order->shipments as $shipment)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-sm">
                            <div><span class="font-medium text-gray-900 dark:text-white">{{ $shipment->courier }}</span> — <span class="font-mono text-gray-500">{{ $shipment->tracking_number }}</span></div>
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $shipment->shipment_status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">{{ ucfirst(str_replace('_', ' ', $shipment->shipment_status)) }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
