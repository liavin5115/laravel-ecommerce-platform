<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_revenue' => Order::whereNotIn('status', ['cancelled'])->sum('grand_total'),
            'total_organizations' => Organization::count(),
            'total_products' => Product::count(),
            'total_users' => User::count(),
        ];

        $recentOrgs = Organization::with('stores')->latest()->take(5)->get();
        $recentOrders = Order::with(['organization', 'customer'])->latest('placed_at')->take(5)->get();

        return view('super-admin.dashboard', compact('stats', 'recentOrgs', 'recentOrders'));
    }
}
