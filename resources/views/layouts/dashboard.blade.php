<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        background: '#F8FAFC',
                        sidebar: '#F1F5F9',
                        sidebarDark: '#1E293B',
                        textMain: '#0F172A',
                        textMuted: '#64748B',
                        borderLight: '#E2E8F0',
                        success: '#22C55E',
                        successBg: '#DCFCE7',
                        warning: '#EAB308',
                        warningBg: '#FEF08A',
                        info: '#3B82F6',
                        infoBg: '#DBEAFE',
                        danger: '#EF4444',
                        dangerBg: '#FEE2E2',
                    }
                }
            }
        }
    </script>
    <style data-purpose="custom-styles">
        body {
            background-color: theme('colors.background');
            font-family: theme('fontFamily.sans');
            color: theme('colors.textMain');
        }
        
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
    @yield('head')
</head>
<body class="flex h-screen overflow-hidden antialiased">
    <!-- BEGIN: Sidebar -->
    <aside class="w-64 bg-sidebar border-r border-borderLight flex flex-col hidden md:flex shrink-0 h-full">
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 mb-4 mt-2">
            <a href="/" class="flex items-center">
                <svg class="w-8 h-8 text-slate-800 mr-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                </svg>
                <span class="text-xl font-bold text-slate-900 tracking-tight">
                    @if(auth()->user()->is_super_admin)
                        Platform Admin
                    @elseif(auth()->user()->organizations()->exists())
                        {{ auth()->user()->organizations()->first()->name }}
                    @else
                        {{ config('app.name', 'Marketplace') }}
                    @endif
                </span>
            </a>
        </div>
        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto no-scrollbar px-4 space-y-1">
            @php
                $activeDashboard = session('active_dashboard');
                if (!$activeDashboard) {
                    if (auth()->user()->is_super_admin) {
                        $activeDashboard = 'admin';
                    } elseif (auth()->user()->organizations()->exists()) {
                        $activeDashboard = 'seller';
                    } else {
                        $activeDashboard = 'buyer';
                    }
                }
            @endphp

            @if($activeDashboard === 'admin' && auth()->user()->is_super_admin)
                <!-- Super Admin Links -->
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('super-admin.dashboard') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('super-admin.dashboard') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('super-admin.dashboard') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Dashboard
                </a>
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('super-admin.organizations.*') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('super-admin.organizations.index') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('super-admin.organizations.*') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Organizations
                </a>
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('super-admin.seller-requests.*') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('super-admin.seller-requests.index') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('super-admin.seller-requests.*') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Seller Requests
                </a>
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('profile.edit') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('profile.edit') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('profile.edit') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Profile
                </a>
            @elseif($activeDashboard === 'seller' && auth()->user()->organizations()->exists())
                <!-- Seller Links -->
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('dashboard') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('dashboard') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Dashboard
                </a>
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('dashboard.products') || request()->is('admin/products*') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('dashboard.products') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.products') || request()->is('admin/products*') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Products
                </a>
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('dashboard.orders') || request()->routeIs('admin.orders.*') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('dashboard.orders') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.orders') || request()->routeIs('admin.orders.*') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Orders
                </a>
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('dashboard.customers') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('dashboard.customers') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.customers') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Customers
                </a>
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('dashboard.stores') || request()->is('admin/stores*') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('dashboard.stores') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.stores') || request()->is('admin/stores*') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Stores
                </a>
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('dashboard.categories') || request()->is('admin/categories*') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('dashboard.categories') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.categories') || request()->is('admin/categories*') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Categories
                </a>
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('dashboard.coupons') || request()->is('admin/coupons*') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('dashboard.coupons') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.coupons') || request()->is('admin/coupons*') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Coupons
                </a>
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('dashboard.tickets') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('dashboard.tickets') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard.tickets') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Tickets
                </a>
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('profile.edit') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('profile.edit') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('profile.edit') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Profile
                </a>
            @else
                <!-- Buyer Links (Fallback) -->
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('buyer.dashboard') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('buyer.dashboard') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('buyer.dashboard') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Dashboard
                </a>
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('buyer.orders*') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('buyer.orders') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('buyer.orders*') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    My Orders
                </a>
                <a class="flex items-center px-3 py-2.5 {{ request()->routeIs('profile.edit') ? 'bg-sidebarDark text-white' : 'text-textMuted hover:bg-white hover:text-slate-900' }} rounded-lg font-medium transition-colors" href="{{ route('profile.edit') }}">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('profile.edit') ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Profile
                </a>
            @endif
        </nav>
        
        <!-- Logout -->
        <div class="p-4 border-t border-borderLight">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-3 py-2.5 text-textMuted hover:bg-red-50 hover:text-red-600 rounded-lg font-medium transition-colors">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>
    <!-- END: Sidebar -->

    <!-- BEGIN: Main Content Area -->
    <main class="flex-1 flex flex-col h-full relative">
        <!-- BEGIN: Top Header -->
        <header class="h-16 bg-white/50 backdrop-blur-sm border-b border-borderLight flex items-center justify-between px-6 shrink-0 sticky top-0 z-10">
            <!-- Search Bar -->
            <div class="flex-1 max-w-lg relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </div>
                <input class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-lg leading-5 bg-slate-50 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-slate-300 focus:border-slate-300 sm:text-sm transition-colors" placeholder="Search" type="text"/>
            </div>
            <!-- Right Actions -->
            <div class="ml-4 flex items-center space-x-4">
                <button class="relative p-2 text-slate-400 hover:text-slate-500 rounded-full hover:bg-slate-100 transition-colors">
                    <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </button>
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <div @click="open = !open" class="flex items-center cursor-pointer p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm shadow-indigo-200">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="ml-2 text-sm font-medium text-slate-700 hidden sm:block">{{ auth()->user()->name }}</span>
                        <svg class="ml-2 h-4 w-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                    </div>

                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl border border-slate-100 shadow-xl shadow-slate-200/50 py-2 z-50 overflow-hidden"
                         style="display: none;">
                        <div class="px-4 py-2 border-b border-slate-50 mb-1">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Account</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                            <svg class="w-4 h-4 mr-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            Profile Settings
                        </a>
                        <div class="border-t border-slate-50 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- END: Top Header -->

        <!-- BEGIN: Page Content -->
        <div class="flex-1 overflow-y-auto p-6 md:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-successBg border border-success/20 text-success text-sm font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="p-4 rounded-xl bg-infoBg border border-info/20 text-info text-sm font-medium flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                        {{ session('info') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
        <!-- END: Page Content -->
    </main>
    <!-- END: Main Content Area -->
    @include('partials.chat-widget')
    @yield('scripts')
</body>
</html>
