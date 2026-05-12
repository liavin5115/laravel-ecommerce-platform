<div class="flex flex-col h-full">
    <!-- Messages Thread -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4 no-scrollbar" id="widget-chat-thread">
        @foreach($ticket->messages as $msg)
            @php
                // Check if sender is staff (organization member) or customer
                // If sender email matches customer email, it's a customer message
                $isStaff = $msg->sender?->email !== $ticket->customer?->email;
            @endphp
            <div class="flex {{ $isStaff ? 'justify-end' : 'justify-start' }}">
                <div class="flex gap-2 max-w-[85%] {{ $isStaff ? 'flex-row-reverse' : 'flex-row' }}">
                    <div class="flex-shrink-0 w-6 h-6 rounded-full {{ $isStaff ? 'bg-sidebarDark text-white' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center text-[8px] font-bold border border-white">
                        {{ strtoupper(substr($msg->sender?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="space-y-0.5">
                        <div class="px-3 py-2 rounded-2xl text-xs shadow-sm {{ $isStaff ? 'bg-sidebarDark text-white rounded-tr-none' : 'bg-slate-100 text-slate-700 rounded-tl-none' }}">
                            {{ $msg->message }}
                        </div>
                        <div class="text-[8px] text-slate-400 {{ $isStaff ? 'text-right' : 'text-left' }} px-1">
                            {{ $msg->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Quick Reply Form -->
    <div class="p-4 bg-white border-t border-slate-50">
        <form @submit.prevent="submitReply($event)" class="relative">
            @csrf
            <div class="flex items-end gap-2 bg-slate-50 border border-slate-200 rounded-2xl p-1.5 focus-within:border-indigo-500 transition-all">
                <input type="text" name="message" required 
                       class="flex-1 bg-transparent border-none focus:ring-0 text-xs placeholder:text-slate-400 py-1" 
                       placeholder="Reply...">
                <button type="submit" class="p-2 bg-sidebarDark text-white rounded-xl hover:bg-slate-800 transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </button>
            </div>
            <input type="hidden" name="status" value="{{ $ticket->status }}">
        </form>
    </div>
</div>

<script>
    (function() {
        var objDiv = document.getElementById("widget-chat-thread");
        if (objDiv) objDiv.scrollTop = objDiv.scrollHeight;
    })();
</script>
