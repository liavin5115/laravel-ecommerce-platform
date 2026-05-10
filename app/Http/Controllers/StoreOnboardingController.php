<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Store;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class StoreOnboardingController extends Controller
{
    public function index()
    {
        // If user already has an organization, redirect to dashboard
        if (auth()->user()->organizations()->exists()) {
            return redirect()->route('dashboard')->with('info', 'You already have an active store.');
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

        try {
            DB::beginTransaction();

            $user = auth()->user();

            // 1. Create Organization
            $organization = Organization::create([
                'name' => $request->org_name,
                'slug' => Str::slug($request->org_name) . '-' . Str::random(5),
                'email' => $user->email,
                'plan_type' => $request->plan,
                'is_active' => true,
            ]);

            // 2. Create Store
            Store::create([
                'organization_id' => $organization->id,
                'name' => $request->store_name,
                'slug' => Str::slug($request->store_name) . '-' . Str::random(5),
                'description' => "Welcome to {$request->store_name}! We specialize in {$request->business_type} products.",
                'is_active' => true,
            ]);

            // 3. Attach User to Organization as Admin
            $adminRole = Role::where('name', 'admin')->first();
            
            DB::table('organization_user')->insert([
                'organization_id' => $organization->id,
                'user_id' => $user->id,
                'role_id' => $adminRole?->id ?? Role::first()->id,
                'joined_at' => now()->toDateTimeString(),
            ]);

            DB::commit();

            return redirect()->route('dashboard')->with('success_launch', true);

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Store Onboarding Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);
            return back()->withErrors(['error' => 'Failed to create your store. Please try again. ' . $e->getMessage()]);
        }
    }
}
