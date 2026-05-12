@extends('layouts.dashboard')

@section('title', 'Support Tickets')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Support Tickets</h1>
        <p class="text-textMuted text-sm mt-1">
            {{ $activeDashboard === 'seller' ? 'Manage and respond to customer support requests.' : 'Track your conversations with our support team.' }}
        </p>
    </div>
    @if($activeDashboard === 'buyer')
        <a href="#new-ticket" @click="$dispatch('open-chat-widget')" class="inline-flex items-center px-4 py-2 bg-sidebarDark text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition-all shadow-sm shadow-slate-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            New Ticket
        </a>
    @endif
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Ticket</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $activeDashboard === 'seller' ? 'Customer' : 'Organization' }}</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Priority</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($tickets as $ticket)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-900">{{ $ticket->subject }}</div>
                            <div class="text-xs text-textMuted mt-0.5">ID: #{{ substr($ticket->id, 0, 8) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs font-bold mr-3 border border-slate-200">
                                    {{ substr($activeDashboard === 'seller' ? ($ticket->customer?->name ?? '?') : ($ticket->organization?->name ?? '?'), 0, 1) }}
                                </div>
                                <div class="text-sm text-slate-700 font-medium">
                                    {{ $activeDashboard === 'seller' ? ($ticket->customer?->name ?? '—') : ($ticket->organization?->name ?? '—') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php 
                                $priorityColors = [
                                    'low' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    'medium' => 'bg-warningBg text-warning border-warning/10',
                                    'high' => 'bg-dangerBg text-danger border-danger/10'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $priorityColors[$ticket->priority] ?? 'bg-slate-100 text-slate-600' }} border">
                                {{ $ticket->priority }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php 
                                $statusColors = [
                                    'open' => 'bg-infoBg text-info border-info/10',
                                    'in_progress' => 'bg-warningBg text-warning border-warning/10',
                                    'closed' => 'bg-successBg text-success border-success/10'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusColors[$ticket->status] ?? 'bg-slate-100 text-slate-600' }} border">
                                {{ str_replace('_', ' ', $ticket->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-textMuted">
                            {{ $ticket->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <a href="{{ route('dashboard.tickets.show', $ticket) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-50 hover:border-slate-300 transition-all">
                                Open Chat
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-sm text-textMuted">
                            No support tickets found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tickets instanceof \Illuminate\Pagination\AbstractPaginator && $tickets->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $tickets->links() }}
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Listen for custom event to open chat widget
    window.addEventListener('open-chat-widget', () => {
        // Since the widget is in another scope, we might need a global state or simple trigger
        // For now, if Alpine is global, we can try to find the component
        // But a simpler way is to just let the user click the actual bubble.
    });
</script>
@endsection
