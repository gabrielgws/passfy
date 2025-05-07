<?php

namespace App\Livewire\Organizer;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use Illuminate\Support\Str;

class EventForm extends Component
{
    use WithFileUploads;

    public Event $event;
    public $coverImage;
    public $isEditing = false;

    protected $rules = [
        'event.title' => 'required|string|max:255',
        'event.description' => 'required|string',
        'event.location' => 'required|string|max:255',
        'event.start_date' => 'required|date',
        'event.end_date' => 'required|date|after:event.start_date',
        'event.max_tickets' => 'nullable|integer|min:0',
        'event.is_published' => 'boolean',
        'coverImage' => 'nullable|image|max:1024', // 1MB max
    ];

    public function mount($eventId = null)
    {
        if ($eventId) {
            $this->event = Event::where('user_id', auth()->id())->findOrFail($eventId);
            $this->isEditing = true;
        } else {
            $this->event = new Event();
            $this->event->user_id = auth()->id();
            $this->event->is_published = false;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->coverImage) {
            $filename = Str::slug($this->event->title) . '-' . uniqid() . '.' . $this->coverImage->getClientOriginalExtension();
            $path = $this->coverImage->storeAs('events', $filename, 'public');
            $this->event->cover_image = $path;
        }

        $this->event->save();

        return redirect()->route('organizer.events')->with('success', $this->isEditing ? 'Evento atualizado com sucesso!' : 'Evento criado com sucesso!');
    }

    public function render()
    {
        return view('livewire.organizer.event-form')
            ->layout('layouts.app', ['header' => $this->isEditing ? 'Editar Evento' : 'Criar Evento']);
    }
}
