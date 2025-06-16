<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_events' => $user->events()->count(),
            'active_events' => $user->events()->where('status', 'published')->count(),
            'total_tickets_sold' => $user->events()->withSum('ticketTypes', 'sold')->sum('ticket_types_sum_sold'),
            'total_revenue' => $user->events()->withSum(['orders' => function ($query) {
                $query->where('status', 'paid');
            }], 'subtotal')->sum('orders_sum_subtotal'),
        ];

        $recentOrders = $user->events()
            ->with(['orders' => function ($query) {
                $query->with(['user', 'tickets'])
                    ->where('status', 'paid')
                    ->latest()
                    ->limit(10);
            }])
            ->get()
            ->pluck('orders')
            ->flatten();

        return Inertia::render('Dashboard/Index', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
        ]);
    }

    public function events()
    {
        $events = auth()->user()->events()
            ->withCount('orders')
            ->withSum('ticketTypes', 'sold')
            ->latest()
            ->paginate(10);

        return Inertia::render('Dashboard/Events', [
            'events' => $events,
        ]);
    }

    public function eventDetails(Event $event)
    {
        $this->authorize('view', $event);

        $event->load(['ticketTypes', 'orders' => function ($query) {
            $query->with(['user', 'tickets.ticketType'])
                ->latest();
        }]);

        $stats = [
            'total_tickets' => $event->ticketTypes->sum('quantity'),
            'tickets_sold' => $event->ticketTypes->sum('sold'),
            'tickets_available' => $event->ticketTypes->sum(function ($type) {
                return $type->quantity - $type->sold;
            }),
            'total_revenue' => $event->orders()->where('status', 'paid')->sum('subtotal'),
            'total_orders' => $event->orders()->where('status', 'paid')->count(),
        ];

        return Inertia::render('Dashboard/EventDetails', [
            'event' => $event,
            'stats' => $stats,
        ]);
    }

    public function orders()
    {
        $orders = auth()->user()->orders()
            ->with(['event', 'tickets'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Dashboard/Orders', [
            'orders' => $orders,
        ]);
    }
}
