<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $org = auth()->user()->organizations()->first();
        $coupons = $org
            ? Coupon::where('organization_id', $org->id)->latest()->paginate(10)
            : collect();

        return view('dashboard.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('dashboard.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'minimum_order' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $org = auth()->user()->organizations()->first();

        Coupon::create([
            'organization_id' => $org->id,
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'minimum_order' => $request->minimum_order ?? 0,
            'usage_limit' => $request->usage_limit ?? 0,
            'used_count' => 0,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('dashboard.coupons')->with('success', 'Coupon created.');
    }

    public function edit(Coupon $coupon)
    {
        return view('dashboard.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'minimum_order' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'expires_at' => 'nullable|date',
        ]);

        $coupon->update([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'minimum_order' => $request->minimum_order ?? 0,
            'usage_limit' => $request->usage_limit ?? 0,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('dashboard.coupons')->with('success', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('dashboard.coupons')->with('success', 'Coupon deleted.');
    }
}
