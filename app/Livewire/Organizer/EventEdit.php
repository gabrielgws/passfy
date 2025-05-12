<?php

namespace App\Livewire\Organizer;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use Carbon\Carbon;

class EventEdit extends Component
{
    use WithFileUploads;

    public $event_id;
    public $title = '';
    public $description = '';
    public $location = '';
    public $start_date = '';
    public $end_date = '';
    public $max_tickets = null;
    public $cover_image = null;
    public $is_published = false;
    public $current_cover_image = null;

    public function mount(Event $event)
    {
        $this->event_id = $event->id;
        $this->title = $event->title;
        $this->description = $event->description;
        $this->location = $event->location;

        $this->start_date = Carbon::parse($event->start_date)->format('Y-m-d\TH:i');
        $this->end_date = Carbon::parse($event->end_date)->format('Y-m-d\TH:i');

        $this->max_tickets = $event->max_tickets;
        $this->is_published = $event->is_published;
        $this->current_cover_image = $event->cover_image;
    }

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'description' => 'required|min:10',
        'location' => 'required',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'max_tickets' => 'nullable|integer|min:0',
        'cover_image' => 'nullable|image|max:1024',
        'is_published' => 'boolean'
    ];

    public function update()
    {
        $validatedData = $this->validate();

        // Processar upload de imagem
        if ($this->cover_image) {
            $imagePath = $this->cover_image->store('events', 'public');
            $validatedData['cover_image'] = $imagePath;
        } else {
            // Manter a imagem atual se nenhuma nova imagem for enviada
            $validatedData['cover_image'] = $this->current_cover_image;
        }

        // Converter datas
        $validatedData['start_date'] = Carbon::parse($this->start_date)->format('Y-m-d H:i:s');
        $validatedData['end_date'] = Carbon::parse($this->end_date)->format('Y-m-d H:i:s');

        try {
            // Encontrar e atualizar o evento
            $event = Event::findOrFail($this->event_id);
            $event->update($validatedData);

            // Adicionar flash message para ser exibida como toast
            $this->dispatch('toast', [
                'message' => 'Evento atualizado com sucesso!',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            // Adicionar flash message de erro como toast
            $this->dispatch('toast', [
                'message' => 'Erro ao atualizar evento: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.organizer.event-edit')
            ->layout('components.layouts.app');
    }
}
