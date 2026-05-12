<x-guest-layout>
    <div class="mb-6 text-sm text-textMuted leading-relaxed">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                </div>
                <input id="email" class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-info focus:border-info transition-colors placeholder:text-slate-400" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@company.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
        </div>

        <button type="submit" class="w-full py-4 px-6 bg-sidebarDark text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 hover:shadow-xl active:scale-[0.98]">
            Email Password Reset Link
        </button>
    </form>

    <!-- Back to Login -->
    <div class="mt-8 pt-8 border-t border-slate-100 text-center">
        <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-bold text-slate-900 hover:text-info transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
            Back to Sign In
        </a>
    </div>
</x-guest-layout>
