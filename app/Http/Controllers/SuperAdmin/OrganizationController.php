<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::with(['stores', 'users'])->latest()->paginate(10);
        return view('super-admin.organizations.index', compact('organizations'));
    }

    public function toggleStatus(Organization $organization)
    {
        $organization->update(['is_active' => !$organization->is_active]);
        
        $status = $organization->is_active ? 'activated' : 'suspended';
        return back()->with('success', "Organization {$organization->name} has been {$status}.");
    }

    public function updatePlan(Request $request, Organization $organization)
    {
        $request->validate(['plan_type' => 'required|in:free,basic,pro,enterprise']);
        $organization->update(['plan_type' => $request->plan_type]);
        
        return back()->with('success', "Plan for {$organization->name} updated to {$request->plan_type}.");
    }
}
