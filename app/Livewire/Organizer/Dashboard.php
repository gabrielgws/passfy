<?php

namespace App\Livewire\Organizer;

use Livewire\Component;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $totalEvents;
    public $totalTicketsSold;
    public $totalRevenue;
    public $upcomingEvents;

    public function mount()
    {
        $this->totalEvents = Event::where('user_id', auth()->id())->count();

        $this->totalTicketsSold = OrderItem::whereHas('ticket.event', function ($query) {
            $query->where('user_id', auth()->id());
        })->sum('quantity');

        $this->totalRevenue = OrderItem::whereHas('ticket.event', function ($query) {
            $query->where('user_id', auth()->id());
        })->sum(DB::raw('quantity * price'));

        $this->upcomingEvents = Event::where('user_id', auth()->id())
            ->where('end_date', '>=', now())
            ->orderBy('start_date')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.organizer.dashboard');
    }
}
