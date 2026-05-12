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

    public function show(Request $request)
    {
        $user = auth()->check() ? auth()->user() : null;
        $preferred = $request->get('dashboard', session('active_dashboard'));

        if ($preferred && $this->canAccessDashboard($preferred, $user)) {
            session(['active_dashboard' => $preferred]);
            return $this->redirectToDashboard($preferred);
        }

        // Auto-detect based on permissions
        if ($user->is_super_admin) {
            return redirect()->route('super-admin.dashboard');
        }
        if ($user->organizations()->exists()) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('buyer.dashboard');
    }

    public function switch($dashboard = null)
    {
        $user = auth()->user();
        
        if ($dashboard && $this->canAccessDashboard($dashboard, $user)) {
            session(['active_dashboard' => $dashboard]);
        }

        return $this->redirectToDashboard($dashboard, $user);
    }

    private function canAccessDashboard($dashboard, $user)
    {
        if ($dashboard === 'buyer') {
            return true; // Everyone is a buyer
        }
        if ($dashboard === 'seller') {
            return $user->organizations()->exists();
        }
        if ($dashboard === 'admin') {
            return (bool) $user->is_super_admin;
        }
        return false;
    }

    private function redirectToDashboard($dashboard, $user = null)
    {
        $user = $user ?? auth()->user();
        $dashboard = $dashboard ?? session('active_dashboard');

        switch ($dashboard) {
            case 'buyer':
                return redirect()->route('buyer.dashboard');
            case 'seller':
                if ($user->organizations()->exists()) {
                    return redirect()->route('dashboard');
                }
                break;
            case 'admin':
                if ($user->is_super_admin) {
                    return redirect()->route('super-admin.dashboard');
                }
                break;
        }

        // Fallback logic
        if ($user->is_super_admin) {
            return redirect()->route('super-admin.dashboard');
        }
        if ($user->organizations()->exists()) {
            return redirect()->route('dashboard');
        }
        return redirect()->route('buyer.dashboard');
    }

    public function index(Request $request)
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
        $activeDashboard = session('active_dashboard', 'buyer');
        
        if ($activeDashboard === 'seller') {
            $org = $this->getOrg();
            $tickets = $org
                ? SupportTicket::with(['customer'])
                    ->where('organization_id', $org->id)
                    ->latest()
                    ->paginate(10)
                : collect();
        } else {
            $tickets = SupportTicket::with(['organization'])
                ->whereHas('customer', function($query) {
                    $query->where('email', auth()->user()->email);
                })
                ->latest()
                ->paginate(10);
        }

        return view('dashboard.tickets.index', compact('tickets', 'activeDashboard'));
    }

    public function ticketStore(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'reference_id' => 'nullable|string|max:100',
        ]);

        // Determine organization context
        $org = auth()->user()->organizations()->first();

        // For buyers (no organization), use first organization as support recipient
        if (!$org) {
            $org = \App\Models\Organization::first();
        }

        // Find or create customer - buyers may not have organization_id
        $customer = Customer::firstOrCreate(
            ['email' => auth()->user()->email],
            ['name' => auth()->user()->name, 'organization_id' => $org->id]
        );

        $ticket = SupportTicket::create([
            'organization_id' => $org->id,
            'customer_id' => $customer->id,
            'subject' => $request->subject . ($request->reference_id ? " (Ref: #{$request->reference_id})" : ""),
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        TicketMessage::create([
            'support_ticket_id' => $ticket->id,
            'sender_user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // If AJAX request, return ticket data for widget
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $ticket->load(['customer', 'messages.sender', 'organization']);
            return response()->json([
                'success' => true,
                'ticket' => $ticket,
                'redirect' => route('dashboard.tickets.show', $ticket),
            ]);
        }

        return redirect()->route('dashboard.tickets.show', $ticket)->with('success', 'Support ticket created successfully.');
    }

    public function ticketShow(SupportTicket $ticket)
    {
        // Authorization: user must be seller or customer
        $user = auth()->user();
        $isSeller = $user->organizations()->exists() &&
            $user->organizations()->first()->id === $ticket->organization_id;
        $isCustomer = $ticket->customer && $ticket->customer->email === $user->email;

        if (!$isSeller && !$isCustomer) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $ticket->load(['customer', 'messages.sender']);
        return view('dashboard.tickets.show', compact('ticket'));
    }

    public function ticketMessages(SupportTicket $ticket)
    {
        // Authorization: user must be seller or customer
        $user = auth()->user();
        $isSeller = $user->organizations()->exists() &&
            $user->organizations()->first()->id === $ticket->organization_id;
        $isCustomer = $ticket->customer && $ticket->customer->email === $user->email;

        if (!$isSeller && !$isCustomer) {
            abort(403, 'Unauthorized access to this ticket.');
        }

        $ticket->load(['customer', 'messages.sender', 'organization']);
        return view('partials.ticket-chat-content', compact('ticket'));
    }

    public function ticketReply(Request $request, SupportTicket $ticket)
    {
        // Authorization: user must be seller or customer
        $user = auth()->user();
        $isSeller = $user->organizations()->exists() &&
            $user->organizations()->first()->id === $ticket->organization_id;
        $isCustomer = $ticket->customer && $ticket->customer->email === $user->email;

        if (!$isSeller && !$isCustomer) {
            abort(403, 'Unauthorized access to this ticket.');
        }

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
