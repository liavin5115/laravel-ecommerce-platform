<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Support Tickets') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead><tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Priority</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Action</th>
                        </tr></thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($tickets as $ticket)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $ticket->subject }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $ticket->customer?->name ?? '—' }}</td>
                                    <td class="px-6 py-4">
                                        @php $pc = ['low'=>'bg-gray-100 text-gray-800','medium'=>'bg-yellow-100 text-yellow-800','high'=>'bg-red-100 text-red-800']; @endphp
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $pc[$ticket->priority] ?? '' }}">{{ ucfirst($ticket->priority) }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php $sc = ['open'=>'bg-blue-100 text-blue-800','in_progress'=>'bg-yellow-100 text-yellow-800','closed'=>'bg-green-100 text-green-800']; @endphp
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $sc[$ticket->status] ?? '' }}">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $ticket->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4"><a href="{{ route('dashboard.tickets.show', $ticket) }}" class="text-indigo-600 hover:text-indigo-900 text-sm">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">No support tickets.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($tickets instanceof \Illuminate\Pagination\AbstractPaginator && $tickets->hasPages())
                    <div class="mt-6">{{ $tickets->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
