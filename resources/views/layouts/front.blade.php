<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Marketplace'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Navigation -->
    <nav x-data="{ open: false }" class="fixed w-full z-50 top-0 border-b border-gray-200 bg-white/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-purple-500">
                            Marketplace
                        </a>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden sm:flex sm:items-center sm:space-x-8">
                    <a href="{{ route('public.products.index') }}" class="text-sm font-medium {{ request()->routeIs('public.products.index') ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-900' }} transition">Explore</a>
                    <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                        <button class="text-sm font-medium text-gray-500 hover:text-gray-900 transition flex items-center gap-1">
                            Categories
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" class="absolute left-0 mt-0 w-48 bg-white shadow-2xl rounded-xl py-2 border border-gray-100 z-50">
                            @foreach(\App\Models\Category::whereNull('parent_id')->get() as $cat)
                                <a href="{{ route('public.products.index', ['category' => $cat->slug]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600">{{ $cat->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <a href="{{ route('cart.index') }}" class="relative text-gray-500 hover:text-gray-900 transition">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-indigo-600 text-[10px] font-bold text-white">{{ count(session('cart')) }}</span>
                        @endif
                    </a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-full transition shadow-lg shadow-indigo-500/30">Dashboard</a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition">Log in</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-full transition shadow-lg shadow-indigo-500/30">Sign up</a>
                        @endif
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div :class="{'block': open, 'hidden': ! open}" class="sm:hidden bg-white border-b border-gray-200">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('public.products.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('public.products.index') ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50' }} text-base font-medium">Explore</a>
                <a href="{{ route('cart.index') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">Cart ({{ session('cart') ? count(session('cart')) : 0 }})</a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">Dashboard</a>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50">Log in</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-200 bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center">
            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} Marketplace. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>
