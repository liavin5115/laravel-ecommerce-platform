<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private function getOrg()
    {
        return auth()->user()->organizations()->first();
    }

    public function index()
    {
        $org = $this->getOrg();

        $stats = [
            'total_revenue' => $org ? Order::where('organization_id', $org->id)->whereNotIn('status', ['cancelled'])->sum('grand_total') : 0,
            'total_orders' => $org ? Order::where('organization_id', $org->id)->count() : 0,
            'total_products' => $org ? Product::whereHas('store', fn ($q) => $q->where('organization_id', $org->id))->count() : 0,
            'total_customers' => $org ? Customer::where('organization_id', $org->id)->count() : 0,
        ];

        $recentOrders = $org
            ? Order::with(['customer', 'items'])
                ->where('organization_id', $org->id)
                ->latest('placed_at')
                ->take(5)
                ->get()
            : collect();

        return view('dashboard', compact('stats', 'recentOrders', 'org'));
    }

    public function products()
    {
        $org = $this->getOrg();

        $products = $org
            ? Product::with(['store', 'category', 'variants', 'images'])
                ->whereHas('store', fn ($q) => $q->where('organization_id', $org->id))
                ->latest()
                ->paginate(10)
            : collect();

        return view('dashboard.products', compact('products', 'org'));
    }

    public function orders()
    {
        $org = $this->getOrg();

        $orders = $org
            ? Order::with(['customer', 'items', 'payments'])
                ->where('organization_id', $org->id)
                ->latest('placed_at')
                ->paginate(10)
            : collect();

        return view('dashboard.orders', compact('orders', 'org'));
    }

    public function orderShow(Order $order)
    {
        $order->load(['customer', 'items', 'payments', 'shipments']);
        return view('dashboard.orders.show', compact('order'));
    }

    public function orderUpdateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,processing,shipped,delivered,cancelled']);
        $order->update(['status' => $request->status]);
        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated.');
    }

    public function customers()
    {
        $org = $this->getOrg();

        $customers = $org
            ? Customer::with(['orders', 'addresses'])
                ->where('organization_id', $org->id)
                ->latest()
                ->paginate(10)
            : collect();

        return view('dashboard.customers', compact('customers', 'org'));
    }

    public function tickets()
    {
        $org = $this->getOrg();

        $tickets = $org
            ? SupportTicket::with(['customer'])
                ->where('organization_id', $org->id)
                ->latest()
                ->paginate(10)
            : collect();

        return view('dashboard.tickets.index', compact('tickets'));
    }

    public function ticketShow(SupportTicket $ticket)
    {
        $ticket->load(['customer', 'messages.sender']);
        return view('dashboard.tickets.show', compact('ticket'));
    }

    public function ticketReply(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
            'status' => 'required|in:open,in_progress,closed',
        ]);

        TicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        $ticket->update(['status' => $request->status]);

        return redirect()->route('dashboard.tickets.show', $ticket)->with('success', 'Reply sent.');
    }
}
