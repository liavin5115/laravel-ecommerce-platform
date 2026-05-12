@extends('layouts.dashboard')

@section('title', 'Profile Settings')

@section('content')
<!-- Page Title -->
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-slate-900">Profile Settings</h1>
        <p class="text-sm text-textMuted mt-1">Manage your account information and security preferences</p>
    </div>
</div>

<div class="grid grid-cols-1 gap-8 max-w-4xl">
    <!-- Profile Information -->
    <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <!-- Update Password -->
    <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <!-- Delete Account -->
    <div class="bg-white p-8 rounded-2xl border border-danger/10 shadow-sm">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection

@section('head')
<style>
    /* Premium overrides for standard form elements within profile */
    input[type="text"], input[type="email"], input[type="password"], select {
        @apply rounded-xl border-slate-200 bg-slate-50 focus:ring-info focus:border-info transition-colors;
    }
    button[type="submit"]:not(.text-red-600) {
        @apply px-6 py-2.5 bg-sidebarDark text-white rounded-xl font-medium hover:bg-slate-800 transition-colors shadow-sm;
    }
</style>
@endsection
