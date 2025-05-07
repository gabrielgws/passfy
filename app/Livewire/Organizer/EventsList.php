<?php

namespace App\Livewire\Organizer;

use Livewire\Component;
use App\Models\Event;
use Livewire\WithPagination;

class EventsList extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function render()
    {
        $events = Event::where('user_id', auth()->id())
            ->when($this->search, function ($query, $search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.organizer.events-list', [
            'events' => $events
        ])->layout('layouts.app', ['header' => 'Meus Eventos']);
    }

    public function deleteEvent($eventId)
    {
        $event = Event::where('user_id', auth()->id())->find($eventId);

        if (!$event) {
            $this->dispatch('error', ['message' => 'Evento não encontrado!']);
            return;
        }

        $event->delete();
        $this->dispatch('success', ['message' => 'Evento excluído com sucesso!']);
    }
}
