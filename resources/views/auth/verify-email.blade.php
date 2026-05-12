<x-guest-layout>
    <div class="mb-6 text-sm text-textMuted leading-relaxed text-center">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 font-bold text-sm text-success text-center bg-successBg p-4 rounded-2xl border border-success/10">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-8 space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full py-4 px-6 bg-sidebarDark text-white rounded-2xl font-bold text-sm hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 hover:shadow-xl active:scale-[0.98]">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="text-sm font-bold text-slate-700 hover:text-danger transition-colors underline decoration-2 underline-offset-4 decoration-slate-200 hover:decoration-danger/30">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
