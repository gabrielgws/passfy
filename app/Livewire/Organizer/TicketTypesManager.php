<?php

namespace App\Livewire\Event;

use Livewire\Component;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Support\Collection;

class TicketTypesManager extends Component
{
    public Event $event;
    public Collection $ticketTypes;
    public bool $showAddTicketModal = false;

    // Formulário para novo ingresso
    public array $ticketForm = [
        'name' => '',
        'description' => '',
        'price' => '0.00',
        'quantity_available' => 0,
        'min_purchase' => 1,
        'max_purchase' => 10,
        'is_visible' => true,
        'sales_start_at' => '',
        'sales_end_at' => '',
    ];

    public ?TicketType $editingTicket = null;

    protected $rules = [
        'ticketForm.name' => 'required|string|max:255',
        'ticketForm.description' => 'nullable|string',
        'ticketForm.price' => 'required|numeric|min:0',
        'ticketForm.quantity_available' => 'required|integer|min:0',
        'ticketForm.min_purchase' => 'required|integer|min:1',
        'ticketForm.max_purchase' => 'required|integer|min:1',
        'ticketForm.is_visible' => 'boolean',
        'ticketForm.sales_start_at' => 'nullable|date',
        'ticketForm.sales_end_at' => 'nullable|date|after:ticketForm.sales_start_at',
    ];

    protected $messages = [
        'ticketForm.name.required' => 'O nome do ingresso é obrigatório.',
        'ticketForm.price.required' => 'O preço é obrigatório.',
        'ticketForm.price.min' => 'O preço não pode ser negativo.',
        'ticketForm.quantity_available.required' => 'A quantidade é obrigatória.',
        'ticketForm.quantity_available.min' => 'A quantidade não pode ser negativa.',
        'ticketForm.sales_end_at.after' => 'A data de término deve ser posterior à data de início.',
    ];

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->loadTicketTypes();
    }

    public function loadTicketTypes()
    {
        $this->ticketTypes = $this->event->ticketTypes()->get();
    }

    public function openAddTicketModal()
    {
        $this->resetForm();
        $this->showAddTicketModal = true;
    }

    public function closeModal()
    {
        $this->showAddTicketModal = false;
        $this->editingTicket = null;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->ticketForm = [
            'name' => '',
            'description' => '',
            'price' => '0.00',
            'quantity_available' => 0,
            'min_purchase' => 1,
            'max_purchase' => 10,
            'is_visible' => true,
            'sales_start_at' => '',
            'sales_end_at' => '',
        ];

        $this->resetValidation();
    }

    public function saveTicket()
    {
        $this->validate();

        if ($this->editingTicket) {
            $this->editingTicket->update($this->ticketForm);
            $message = 'Ingresso atualizado com sucesso!';
        } else {
            $this->event->ticketTypes()->create([
                ...$this->ticketForm,
                'sort_order' => $this->ticketTypes->count(),
            ]);
            $message = 'Ingresso criado com sucesso!';
        }

        $this->loadTicketTypes();
        $this->closeModal();

        $this->dispatch('flux:toast', title: $message, variant: 'success');
    }

    public function editTicket(TicketType $ticket)
    {
        $this->editingTicket = $ticket;
        $this->ticketForm = $ticket->only([
            'name',
            'description',
            'price',
            'quantity_available',
            'min_purchase',
            'max_purchase',
            'is_visible',
            'sales_start_at',
            'sales_end_at'
        ]);

        // Formatar datas para o input
        if ($this->ticketForm['sales_start_at']) {
            $this->ticketForm['sales_start_at'] = $ticket->sales_start_at->format('Y-m-d\TH:i');
        }
        if ($this->ticketForm['sales_end_at']) {
            $this->ticketForm['sales_end_at'] = $ticket->sales_end_at->format('Y-m-d\TH:i');
        }

        $this->showAddTicketModal = true;
    }

    public function deleteTicket(TicketType $ticket)
    {
        if ($ticket->quantity_sold > 0) {
            $this->dispatch(
                'flux:toast',
                title: 'Este ingresso não pode ser excluído pois já possui vendas.',
                variant: 'warning'
            );
            return;
        }

        $ticket->delete();
        $this->loadTicketTypes();

        $this->dispatch('flux:toast', title: 'Ingresso excluído com sucesso!', variant: 'success');
    }

    public function toggleVisibility(TicketType $ticket)
    {
        $ticket->update(['is_visible' => !$ticket->is_visible]);
        $this->loadTicketTypes();
    }

    public function updateSortOrder($sortedIds)
    {
        foreach ($sortedIds as $index => $id) {
            TicketType::where('id', $id)->update(['sort_order' => $index]);
        }

        $this->loadTicketTypes();
    }

    public function render()
    {
        return view('livewire.event.ticket-types-manager');
    }
}
