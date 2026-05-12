@php
    $activeDashboard = session('active_dashboard', 'buyer');
    $isSeller = $activeDashboard === 'seller';
    
    // Fetch recent tickets for the widget preview
    if ($isSeller) {
        $org = auth()->user()->organizations()->first();
        $recentTickets = $org ? \App\Models\SupportTicket::where('organization_id', $org->id)->latest()->take(3)->get() : collect();
        $widgetTitle = "Store Support";
        $widgetSubtitle = "Customer Requests";
    } else {
        $recentTickets = \App\Models\SupportTicket::whereHas('customer', function($q) {
            $q->where('email', auth()->user()->email);
        })->where('status', 'open')->latest()->take(5)->get();
        
        // If no open tickets, show some recent closed ones
        if ($recentTickets->isEmpty()) {
            $recentTickets = \App\Models\SupportTicket::whereHas('customer', function($q) {
                $q->where('email', auth()->user()->email);
            })->latest()->take(3)->get();
        }
        $widgetTitle = "Help Center";
        $widgetSubtitle = "My Open Tickets";
    }
@endphp

<div x-data="{
    open: false,
    view: 'list',
    showNotification: true,
    ticketId: null,
    ticketSubject: '',
    chatContent: '',
    loading: false,

    async loadTicket(id, subject) {
        this.loading = true;
        this.view = 'chat';
        this.ticketId = id;
        this.ticketSubject = subject;
        try {
            const response = await fetch(`/dashboard/tickets/${id}/messages`);
            this.chatContent = await response.text();
            this.$nextTick(() => {
                const thread = document.getElementById('widget-chat-thread');
                if (thread) thread.scrollTop = thread.scrollHeight;
            });
        } catch (e) {
            console.error('Failed to load chat');
        } finally {
            this.loading = false;
        }
    },

    async submitReply(event) {
        const form = event.target;
        const formData = new FormData(form);
        try {
            await fetch(`/dashboard/tickets/${this.ticketId}/reply`, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
            });
            form.reset();
            this.loadTicket(this.ticketId, this.ticketSubject);
        } catch (e) {
            console.error('Failed to send reply');
        }
    },

    async createTicket(event) {
        const form = event.target;
        const formData = new FormData(form);
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
            });
            const data = await response.json();
            if (data.success) {
                this.loadTicket(data.ticket.id, data.ticket.subject);
            }
        } catch (e) {
            console.error('Failed to create ticket');
        }
    }
}" 
@open-chat-widget.window="open = true; view = 'create'; showNotification = false"
class="fixed bottom-8 right-8 z-[100]">
    
    <!-- Floating Button -->
    <button @click="open = !open; showNotification = false" 
            class="w-16 h-16 bg-sidebarDark text-white rounded-full flex items-center justify-center shadow-[0_15px_35px_rgba(30,41,59,0.3)] hover:scale-110 active:scale-95 transition-all relative group z-20">
        <svg x-show="!open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
        </svg>
        <svg x-show="open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
            <path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
        </svg>
        <span x-show="showNotification" class="absolute top-0 right-0 h-4 w-4 bg-red-500 border-2 border-white rounded-full shadow-sm animate-bounce"></span>
    </button>

    <!-- Chat Window -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10 scale-90"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-10 scale-90"
         class="absolute bottom-0 right-0 w-80 sm:w-96 bg-white rounded-[2.5rem] border border-slate-100 shadow-[0_30px_60px_rgba(0,0,0,0.15)] overflow-hidden flex flex-col pointer-events-auto mb-20" 
         style="display: none; height: 550px;">
        
        <!-- Header -->
        <div class="bg-sidebarDark p-5 text-white shrink-0 relative">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-indigo-500/20 rounded-xl flex items-center justify-center border border-indigo-400/30">
                        <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 x-show="view !== 'chat'" class="font-bold text-sm leading-tight">{{ $widgetTitle }}</h4>
                        <h4 x-show="view === 'chat'" class="font-bold text-xs leading-tight truncate w-40" x-text="ticketSubject"></h4>
                        <p class="text-[10px] text-indigo-200/70 font-bold uppercase tracking-widest mt-0.5">
                            <span x-show="view !== 'chat'">{{ $widgetSubtitle }}</span>
                            <span x-show="view === 'chat'">Active Conversation</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button x-show="view !== 'list'" @click="view = 'list'" class="p-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </button>
                    <button @click="open = false" class="p-2 bg-white/10 hover:bg-white/20 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto no-scrollbar bg-white relative">
            <!-- Loading Overlay -->
            <div x-show="loading" class="absolute inset-0 bg-white/80 backdrop-blur-[2px] z-50 flex items-center justify-center" style="display: none;">
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 border-4 border-indigo-100 border-t-indigo-600 rounded-full animate-spin"></div>
                    <span class="text-[10px] font-bold text-slate-400 mt-2 uppercase tracking-widest">Syncing Chat</span>
                </div>
            </div>

            <!-- List View -->
            <div x-show="view === 'list'" class="p-6 space-y-4 h-full bg-slate-50/50">
                <div class="flex items-center justify-between mb-2">
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $isSeller ? 'Customer Requests' : 'My Support Tickets' }}</div>
                    <a href="{{ route('dashboard.tickets') }}" class="text-[10px] font-bold text-indigo-600 hover:underline">Full View</a>
                </div>
                
                <div class="space-y-2.5">
                    @forelse($recentTickets as $ticket)
                        <button @click="loadTicket('{{ $ticket->id }}', '{{ $ticket->subject }}')" class="w-full text-left group">
                            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-indigo-100 transition-all">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[9px] font-bold px-2 py-0.5 {{ $ticket->status === 'open' ? 'bg-infoBg text-info' : 'bg-slate-100 text-slate-500' }} rounded-full uppercase">{{ $ticket->status }}</span>
                                    <span class="text-[9px] text-slate-400 font-medium">{{ $ticket->created_at->diffForHumans() }}</span>
                                </div>
                                <h5 class="text-[11px] font-bold text-slate-900 group-hover:text-indigo-600 transition-colors truncate">{{ $ticket->subject }}</h5>
                            </div>
                        </button>
                    @empty
                        <div class="text-center py-12 flex flex-col items-center">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-50 mb-3 text-slate-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            </div>
                            <p class="text-[11px] text-textMuted italic uppercase font-bold tracking-tight">No active tickets</p>
                        </div>
                    @endforelse
                </div>

                @if(!$isSeller)
                    <button @click="view = 'create'" class="w-full mt-4 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-xs hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 active:scale-[0.98]">
                        Start New Ticket
                    </button>
                @endif
            </div>

            <!-- Create View -->
            <div x-show="view === 'create'" class="p-6 h-full bg-slate-50/50" style="display: none;">
                <form @submit.prevent="createTicket($event)" action="{{ route('dashboard.tickets.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Subject</label>
                        <input type="text" name="subject" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:ring-indigo-500 transition-all" placeholder="How can we help?">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Reference (Order/Product ID)</label>
                        <input type="text" name="reference_id" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:ring-indigo-500 transition-all" placeholder="#ORD-XXXXX">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Priority</label>
                        <select name="priority" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:ring-indigo-500 transition-all">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Message</label>
                        <textarea name="message" rows="4" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-sm focus:ring-indigo-500 transition-all" placeholder="Describe your issue..."></textarea>
                    </div>
                    <button type="submit" class="w-full py-4 bg-sidebarDark text-white rounded-2xl font-bold text-xs hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 active:scale-[0.98]">
                        Send Request
                    </button>
                </form>
            </div>

            <!-- Chat View -->
            <div x-show="view === 'chat'" class="h-full bg-white flex flex-col" style="display: none;">
                <div class="flex-1 overflow-hidden" x-html="chatContent"></div>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-3 bg-slate-50 border-t border-slate-100 shrink-0 text-center">
            <div class="flex items-center justify-center space-x-2 text-[9px] text-slate-400 font-bold uppercase tracking-widest">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                <span>System Online</span>
                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                <span>V2.0 Core</span>
            </div>
        </div>
    </div>
</div>
