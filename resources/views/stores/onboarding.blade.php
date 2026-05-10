@extends('layouts.front')

@section('title', 'Open Your Store - Marketplace Onboarding')

@section('content')
<div class="min-h-screen bg-[#0a0a0a] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden" x-data="onboarding()">
    <!-- Animated background -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-600/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <div class="max-w-xl w-full space-y-8 bg-white/5 backdrop-blur-xl p-10 rounded-3xl border border-white/10 shadow-2xl relative z-10">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-600 mb-6 shadow-lg shadow-indigo-600/40">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-white">Launch Your Store</h2>
            <p class="mt-2 text-sm text-gray-400">Step <span x-text="step"></span> of 3: <span x-text="stepTitle"></span></p>
        </div>

        <!-- Progress Bar -->
        <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden mt-8">
            <div class="bg-indigo-500 h-full transition-all duration-500 ease-out" :style="'width: ' + (step * 33.33) + '%'"></div>
        </div>

        <form action="{{ route('stores.onboarding.store') }}" method="POST" class="mt-10 space-y-8" id="onboardingForm">
            @csrf

            <!-- Step 1: Brand Identity -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Organization Name</label>
                        <input type="text" name="org_name" x-model="formData.org_name" required 
                            class="block w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" 
                            placeholder="e.g. Acme Corporation">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Store Name</label>
                        <input type="text" name="store_name" x-model="formData.store_name" required 
                            class="block w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" 
                            placeholder="e.g. Acme Tech Store">
                    </div>
                </div>
            </div>

            <!-- Step 2: Business Type -->
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="grid grid-cols-1 gap-4">
                    <template x-for="type in businessTypes" :key="type.id">
                        <div @click="formData.business_type = type.id" 
                            class="relative p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200"
                            :class="formData.business_type === type.id ? 'border-indigo-500 bg-indigo-500/10' : 'border-white/10 hover:border-white/20 bg-white/5'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="p-2 rounded-lg bg-white/10" :class="formData.business_type === type.id ? 'text-indigo-400' : 'text-gray-400'">
                                        <span x-html="type.icon"></span>
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold" x-text="type.name"></p>
                                        <p class="text-gray-400 text-xs" x-text="type.desc"></p>
                                    </div>
                                </div>
                                <div x-show="formData.business_type === type.id">
                                    <svg class="h-5 w-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                </div>
                            </div>
                            <input type="radio" name="business_type" :value="type.id" x-model="formData.business_type" class="sr-only">
                        </div>
                    </template>
                </div>
            </div>

            <!-- Step 3: Plan Selection -->
            <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="grid grid-cols-1 gap-4">
                    <template x-for="p in plans" :key="p.id">
                        <div @click="formData.plan = p.id" 
                            class="relative p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200"
                            :class="formData.plan === p.id ? 'border-indigo-500 bg-indigo-500/10' : 'border-white/10 hover:border-white/20 bg-white/5'">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-white font-semibold" x-text="p.name"></p>
                                    <p class="text-gray-400 text-xs" x-text="p.price"></p>
                                </div>
                                <div x-show="formData.plan === p.id">
                                    <svg class="h-5 w-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                </div>
                            </div>
                            <input type="radio" name="plan" :value="p.id" x-model="formData.plan" class="sr-only">
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex items-center justify-between mt-12 pt-8 border-t border-white/10">
                <button type="button" @click="prevStep" x-show="step > 1" class="text-sm font-semibold text-gray-400 hover:text-white transition">
                    Back
                </button>
                <div x-show="step === 1"></div> <!-- Spacer -->
                
                <button type="button" @click="nextStep" x-show="step < 3" :disabled="!isStepValid"
                    class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-600/30 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                    Next Step
                </button>

                <button type="submit" x-show="step === 3" :disabled="isSubmitting"
                    class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold shadow-lg shadow-indigo-600/30 hover:scale-105 active:scale-95 transition-all">
                    <span x-text="isSubmitting ? 'Launching...' : 'Launch My Store 🚀'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function onboarding() {
    return {
        step: 1,
        isSubmitting: false,
        formData: {
            org_name: '',
            store_name: '',
            business_type: 'physical',
            plan: 'basic'
        },
        businessTypes: [
            { id: 'physical', name: 'Physical Goods', desc: 'Clothes, electronics, handmade items.', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>' },
            { id: 'digital', name: 'Digital Assets', desc: 'Software, e-books, course materials.', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' },
            { id: 'both', name: 'Hybrid', desc: 'A mix of physical and digital products.', icon: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>' }
        ],
        plans: [
            { id: 'basic', name: 'Basic Plan', price: 'Free to start' },
            { id: 'pro', name: 'Pro Merchant', price: '$29/month' },
            { id: 'enterprise', name: 'Enterprise', price: 'Contact Sales' }
        ],
        get stepTitle() {
            return ['Brand Identity', 'Business Focus', 'Choose Your Plan'][this.step - 1];
        },
        get isStepValid() {
            if (this.step === 1) return this.formData.org_name && this.formData.store_name;
            return true;
        },
        nextStep() {
            if (this.isStepValid) this.step++;
        },
        prevStep() {
            this.step--;
        }
    }
}
</script>

<style>
@keyframes pulse {
    0%, 100% { opacity: 0.2; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(1.1); }
}
.animate-pulse {
    animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
@endsection
