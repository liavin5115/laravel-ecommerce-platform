<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        background: '#F8FAFC',
                        sidebarDark: '#1E293B',
                        textMain: '#0F172A',
                        textMuted: '#64748B',
                        info: '#3B82F6',
                        success: '#22C55E',
                        danger: '#EF4444',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #F8FAFC;
            background-image: radial-gradient(at 0% 0%, hsla(210,100%,98%,1) 0, transparent 50%), 
                              radial-gradient(at 50% 0%, hsla(210,100%,96%,1) 0, transparent 50%), 
                              radial-gradient(at 100% 0%, hsla(210,100%,98%,1) 0, transparent 50%);
        }
    </style>
</head>
<body class="font-sans antialiased text-textMain min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-[440px]">
        <!-- Logo Area -->
        <div class="flex flex-col items-center mb-10">
            <a href="/" class="group transition-transform hover:scale-105">
                <div class="h-16 w-16 bg-sidebarDark rounded-2xl flex items-center justify-center shadow-xl shadow-slate-200 group-hover:shadow-slate-300 transition-all">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 12l10 5 10-5M2 17l10 5 10-5"></path>
                    </svg>
                </div>
            </a>
            <h2 class="mt-6 text-2xl font-bold tracking-tight text-slate-900">{{ config('app.name', 'Marketplace') }}</h2>
            <p class="mt-2 text-sm text-textMuted text-center">Welcome back to our premium marketplace platform.</p>
        </div>

        <!-- Auth Card -->
        <div class="bg-white p-8 md:p-10 rounded-[2.5rem] border border-slate-100 shadow-[0_20px_50px_rgba(0,0,0,0.05)]">
            {{ $slot }}
        </div>

        <!-- Footer -->
        <div class="mt-10 text-center text-xs text-textMuted">
            &copy; {{ date('Y') }} {{ config('app.name', 'Marketplace') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
