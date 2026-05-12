@if(auth()->check())
    @php
        $hasSeller = auth()->user()->organizations()->exists();
        $isAdmin = auth()->user()->is_super_admin;
        $showSwitcher = $hasSeller || $isAdmin;
        
        $activeDashboard = session('active_dashboard');
        if (!$activeDashboard) {
            if ($isAdmin) $activeDashboard = 'admin';
            elseif ($hasSeller) $activeDashboard = 'seller';
            else $activeDashboard = 'buyer';
        }
    @endphp

    @if($showSwitcher)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900 mb-1">Switch Dashboard</h3>
                    <p class="text-xs text-slate-500">Access other dashboards you have permission for</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if($isAdmin)
                        <a href="{{ route('dashboard.switch', 'admin') }}"
                           class="px-4 py-2 text-sm font-medium rounded-xl transition-colors {{ $activeDashboard === 'admin' ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            <svg class="w-4 h-4 inline-block mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.608 3.35 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            Admin
                        </a>
                    @endif
                    @if($hasSeller)
                        <a href="{{ route('dashboard.switch', 'seller') }}"
                           class="px-4 py-2 text-sm font-medium rounded-xl transition-colors {{ $activeDashboard === 'seller' ? 'bg-sidebarDark text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                            <svg class="w-4 h-4 inline-block mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                            Seller
                        </a>
                    @endif
                    <a href="{{ route('dashboard.switch', 'buyer') }}"
                       class="px-4 py-2 text-sm font-medium rounded-xl transition-colors {{ $activeDashboard === 'buyer' ? 'bg-success text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        <svg class="w-4 h-4 inline-block mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        Buyer
                    </a>
                </div>
            </div>
        </div>
    @endif
@endif