<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class BuyerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Find recent orders based on the user's email matching customer emails
        $recentOrders = Order::with(['organization', 'items', 'payments'])
            ->whereHas('customer', function ($query) use ($user) {
                $query->where('email', $user->email);
            })
            ->latest('placed_at')
            ->take(5)
            ->get();

        return view('buyer.dashboard', compact('recentOrders'));
    }

    public function orders()
    {
        $user = auth()->user();
        
        $orders = Order::with(['organization', 'items', 'payments'])
            ->whereHas('customer', function ($query) use ($user) {
                $query->where('email', $user->email);
            })
            ->latest('placed_at')
            ->paginate(10);

        return view('buyer.orders', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $user = auth()->user();
        
        // Ensure the order belongs to the user
        if ($order->customer->email !== $user->email) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['organization', 'items', 'payments', 'shipments', 'address']);
        
        return view('buyer.orders-show', compact('order'));
    }
}
