<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Role;
use App\Models\SellerRequest;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SellerRequestController extends Controller
{
    public function index()
    {
        $requests = SellerRequest::with('user')->latest()->paginate(10);
        return view('super-admin.seller-requests.index', compact('requests'));
    }

    public function approve(SellerRequest $sellerRequest)
    {
        if ($sellerRequest->status !== 'pending') {
            return back()->with('error', 'Request is already processed.');
        }

        try {
            DB::beginTransaction();

            $user = $sellerRequest->user;

            $organization = Organization::create([
                'name' => $sellerRequest->org_name,
                'slug' => Str::slug($sellerRequest->org_name) . '-' . Str::random(5),
                'email' => $user->email,
                'plan_type' => $sellerRequest->plan,
                'is_active' => true,
            ]);

            Store::create([
                'organization_id' => $organization->id,
                'name' => $sellerRequest->store_name,
                'slug' => Str::slug($sellerRequest->store_name) . '-' . Str::random(5),
                'description' => "Welcome to {$sellerRequest->store_name}! We specialize in {$sellerRequest->business_type} products.",
                'is_active' => true,
            ]);

            $adminRole = Role::where('name', 'admin')->first();

            DB::table('organization_user')->insert([
                'organization_id' => $organization->id,
                'user_id' => $user->id,
                'role_id' => $adminRole?->id ?? Role::first()->id,
                'joined_at' => now()->toDateTimeString(),
            ]);

            $sellerRole = Role::where('name', 'seller')->first();
            if ($sellerRole) {
                $user->update(['role_id' => $sellerRole->id]);
            }

            $sellerRequest->update(['status' => 'approved']);

            DB::commit();

            return back()->with('success', 'Seller request approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error approving request: ' . $e->getMessage());
        }
    }

    public function reject(SellerRequest $sellerRequest)
    {
        if ($sellerRequest->status !== 'pending') {
            return back()->with('error', 'Request is already processed.');
        }

        $sellerRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Seller request rejected.');
    }
}
