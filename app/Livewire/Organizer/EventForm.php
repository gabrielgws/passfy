<?php

namespace App\Livewire\Organizer;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Event;
use Carbon\Carbon;

class EventForm extends Component
{
    use WithFileUploads;

    public $title = '';
    public $description = '';
    public $location = '';
    public $category_id = '';
    public $start_date = '';
    public $end_date = '';
    public $max_tickets = null;
    public $cover_image = null;
    public $is_published = false;

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'description' => 'required|min:10',
        'location' => 'required',
        'category_id' => 'required|exists:categories,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'max_tickets' => 'nullable|integer|min:0',
        'cover_image' => 'nullable|image|max:1024',
        'is_published' => 'boolean'
    ];

    protected $messages = [
        'title.required' => 'O título do evento é obrigatório.',
        'description.required' => 'A descrição do evento é obrigatória.',
        'location.required' => 'O local do evento é obrigatório.',
        'category_id.required' => 'A categoria do evento é obrigatória.',
        'category_id.exists' => 'A categoria selecionada é inválida.',
        'start_date.required' => 'A data de início é obrigatória.',
        'end_date.required' => 'A data de término é obrigatória.',
        'end_date.after' => 'A data de término deve ser posterior à data de início.',
        'max_tickets.integer' => 'O número máximo de ingressos deve ser um número inteiro.',
    ];

    public function save()
    {
        $validatedData = $this->validate();

        // Processar upload de imagem
        if ($this->cover_image) {
            $imagePath = $this->cover_image->store('events', 'public');
            $validatedData['cover_image'] = $imagePath;
        }

        // Converter datas
        $validatedData['start_date'] = Carbon::parse($this->start_date)->format('Y-m-d H:i:s');
        $validatedData['end_date'] = Carbon::parse($this->end_date)->format('Y-m-d H:i:s');

        // Adicionar ID do usuário
        $validatedData['user_id'] = auth()->id();

        // Criar evento
        try {
            $event = Event::create($validatedData);

            session()->flash('success', 'Evento criado com sucesso!');
            return redirect()->route('organizer.events');
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao criar evento: ' . $e->getMessage());
            return back();
        }
    }

    public function render()
    {
        return view('livewire.organizer.event-form', [
            'categories' => \App\Models\Category::orderBy('name')->get()
        ])->layout('components.layouts.app');
    }
}
