<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreOnboardingController extends Controller
{
    public function index()
    {
        if (auth()->user()->organizations()->exists()) {
            return redirect()->route('dashboard')->with('info', 'You already have an active store.');
        }

        $pendingRequest = \App\Models\SellerRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($pendingRequest) {
            return redirect()->route('buyer.dashboard')->with('info', 'Your request to become a seller is currently pending approval.');
        }

        return view('stores.onboarding');
    }

    public function store(Request $request)
    {
        $request->validate([
            'org_name' => 'required|string|max:255',
            'store_name' => 'required|string|max:255',
            'business_type' => 'required|in:digital,physical,both',
            'plan' => 'required|in:basic,pro,enterprise',
        ]);

        $user = auth()->user();

        $pendingRequest = \App\Models\SellerRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingRequest) {
            return redirect()->route('buyer.dashboard')->with('error', 'You already have a pending request.');
        }

        try {
            \App\Models\SellerRequest::create([
                'user_id' => $user->id,
                'org_name' => $request->org_name,
                'store_name' => $request->store_name,
                'business_type' => $request->business_type,
                'plan' => $request->plan,
                'status' => 'pending',
            ]);

            return redirect()->route('buyer.dashboard')->with('success', 'Your request to become a seller has been submitted and is pending approval.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Seller Request Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            return back()->withErrors(['error' => 'Failed to submit your request. Please try again. ' . $e->getMessage()]);
        }
    }
}
