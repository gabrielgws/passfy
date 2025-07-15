<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EventManager extends Component
{
    public $events;
    public $showFormModal = false;
    public $editingId = null;
    public $confirmingDelete = false;
    public $deleteId = null;
    protected $listeners = ['closeEventForm' => 'closeModal', 'eventSaved' => 'eventSaved'];

    public function mount()
    {
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $this->events = Event::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();
    }

    public function showCreateModal()
    {
        $this->editingId = null;
        $this->showFormModal = true;
    }

    public function showEditModal($id)
    {
        $this->editingId = $id;
        $this->showFormModal = true;
    }

    public function closeModal()
    {
        $this->showFormModal = false;
        $this->editingId = null;
    }

    public function eventSaved()
    {
        $this->showFormModal = false;
        $this->editingId = null;
        $this->loadEvents();
        session()->flash('success', 'Evento salvo com sucesso!');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteEvent()
    {
        $event = Event::where('user_id', Auth::id())->findOrFail($this->deleteId);
        $event->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->loadEvents();
        session()->flash('success', 'Evento removido com sucesso!');
    }

    public function render()
    {
        return view('livewire.event-manager');
    }
}
