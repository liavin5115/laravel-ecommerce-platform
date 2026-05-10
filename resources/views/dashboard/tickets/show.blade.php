<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Ticket: {{ $ticket->subject }}</h2>
            <a href="{{ route('dashboard.tickets') }}" class="text-sm text-indigo-600 hover:text-indigo-800">← Back</a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $ticket->subject }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">by {{ $ticket->customer?->name ?? 'Unknown' }} · {{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @php $pc = ['low'=>'bg-gray-100 text-gray-800','medium'=>'bg-yellow-100 text-yellow-800','high'=>'bg-red-100 text-red-800']; @endphp
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $pc[$ticket->priority] ?? '' }}">{{ ucfirst($ticket->priority) }}</span>
                        @php $sc = ['open'=>'bg-blue-100 text-blue-800','in_progress'=>'bg-yellow-100 text-yellow-800','closed'=>'bg-green-100 text-green-800']; @endphp
                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $sc[$ticket->status] ?? '' }}">{{ ucfirst(str_replace('_',' ',$ticket->status)) }}</span>
                    </div>
                </div>

                <!-- Messages Thread -->
                <div class="space-y-4 mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    @foreach($ticket->messages as $msg)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">{{ strtoupper(substr($msg->sender?->name ?? 'U', 0, 1)) }}</span>
                            </div>
                            <div class="flex-1 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $msg->sender?->name ?? 'System' }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $msg->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $msg->message }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Reply -->
                <form method="POST" action="{{ route('dashboard.tickets.reply', $ticket) }}" class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Reply</label>
                    <textarea name="message" rows="3" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white" placeholder="Type your reply..."></textarea>
                    <div class="mt-3 flex items-center gap-4">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">Send Reply</button>
                        <select name="status" class="rounded-lg text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            @foreach(['open','in_progress','closed'] as $s)
                                <option value="{{ $s }}" @selected($ticket->status === $s)>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
