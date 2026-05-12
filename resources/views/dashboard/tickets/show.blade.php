@extends('layouts.dashboard')

@section('title', 'Ticket Chat')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center">
        <a href="{{ route('dashboard.tickets') }}" class="mr-4 p-2 text-slate-400 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-900">{{ $ticket->subject }}</h1>
            <div class="flex items-center text-xs mt-0.5 space-x-2">
                <span class="text-textMuted">ID: #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-textMuted">Customer: {{ $ticket->customer?->name ?? 'Unknown' }}</span>
            </div>
        </div>
    </div>
    <div class="flex items-center gap-3">
        @php 
            $statusColors = [
                'open' => 'bg-infoBg text-info border-info/10',
                'in_progress' => 'bg-warningBg text-warning border-warning/10',
                'closed' => 'bg-successBg text-success border-success/10'
            ];
        @endphp
        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusColors[$ticket->status] ?? 'bg-slate-100 text-slate-600' }} border">
            {{ str_replace('_', ' ', $ticket->status) }}
        </span>
    </div>
</div>

<div class="max-w-4xl mx-auto h-[calc(100vh-250px)] flex flex-col">
    <!-- Chat Area -->
    <div class="flex-1 bg-white rounded-t-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col relative">
        <!-- Messages Thread -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6 no-scrollbar" id="chat-thread">
            @foreach($ticket->messages as $msg)
                @php
                    // Check if sender is staff (organization member) or customer
                    // If sender email matches customer email, it's a customer message
                    $isStaff = $msg->sender?->email !== $ticket->customer?->email;
                @endphp
                <div class="flex {{ $isStaff ? 'justify-end' : 'justify-start' }}">
                    <div class="flex gap-3 max-w-[80%] {{ $isStaff ? 'flex-row-reverse' : 'flex-row' }}">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $isStaff ? 'bg-sidebarDark text-white' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center text-[10px] font-bold shadow-sm border border-white">
                            {{ strtoupper(substr($msg->sender?->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="space-y-1">
                            <div class="px-5 py-3 rounded-[2rem] text-sm shadow-sm {{ $isStaff ? 'bg-sidebarDark text-white rounded-tr-none' : 'bg-slate-100 text-slate-700 rounded-tl-none' }}">
                                {{ $msg->message }}
                            </div>
                            <div class="text-[10px] text-textMuted {{ $isStaff ? 'text-right' : 'text-left' }} px-2">
                                {{ $msg->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Scroll Anchor -->
        <div id="anchor"></div>

        <!-- Input Area -->
        <div class="p-4 bg-white border-t border-slate-50 sticky bottom-0">
            <form method="POST" action="{{ route('dashboard.tickets.reply', $ticket) }}" class="relative">
                @csrf
                <div class="flex items-end gap-3 bg-slate-50 border border-slate-200 rounded-[2rem] p-2 focus-within:border-info focus-within:ring-4 focus-within:ring-info/5 transition-all">
                    <div class="flex-1 px-3 py-1">
                        <textarea name="message" rows="1" required 
                                  class="w-full bg-transparent border-none focus:ring-0 text-sm placeholder:text-slate-400 no-scrollbar resize-none py-2" 
                                  placeholder="Type your message here..."
                                  oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"'></textarea>
                    </div>
                    
                    <div class="flex items-center gap-2 pr-2 pb-1">
                        <select name="status" class="bg-transparent border-none focus:ring-0 text-[10px] font-bold text-slate-500 uppercase cursor-pointer hover:text-slate-900 transition-colors">
                            @foreach(['open','in_progress','closed'] as $s)
                                <option value="{{ $s }}" @selected($ticket->status === $s)>{{ str_replace('_',' ',$s) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="p-2.5 bg-sidebarDark text-white rounded-full hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 active:scale-[0.9] group">
                            <svg class="w-4 h-4 transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Footer Detail -->
    <div class="bg-slate-50 rounded-b-3xl border-x border-b border-slate-100 p-4 text-center">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">End of conversation · Support Team Platform</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.onload = function() {
        var objDiv = document.getElementById("chat-thread");
        objDiv.scrollTop = objDiv.scrollHeight;
    };
</script>
@endsection
