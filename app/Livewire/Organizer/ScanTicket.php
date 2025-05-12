<?php

namespace App\Livewire\Organizer;

use Livewire\Component;
use App\Models\Event;
use App\Models\OrderItem;

class ScanTicket extends Component
{
    public Event $event;
    public $qrCode = '';
    public $message = '';
    public $messageType = '';
    public $attendee = null;

    public function mount($eventId)
    {
        $this->event = Event::where('user_id', auth()->id())
            ->with('tickets')
            ->findOrFail($eventId);
    }

    public function verifyTicket()
    {
        $this->resetValidation();
        $this->attendee = null;

        if (empty($this->qrCode)) {
            $this->addError('qrCode', 'Por favor, insira um código QR.');
            return;
        }

        $orderItem = OrderItem::where('qr_code', $this->qrCode)
            ->whereHas('ticket', function ($query) {
                $query->whereHas('event', function ($subQuery) {
                    $subQuery->where('id', $this->event->id);
                });
            })
            ->with(['ticket', 'order.user'])
            ->first();

        if (!$orderItem) {
            $this->message = 'Ingresso inválido ou não pertence a este evento.';
            $this->messageType = 'error';
            return;
        }

        if ($orderItem->checked_in) {
            $this->message = 'Este ingresso já foi utilizado em ' . $orderItem->checked_in_at->format('d/m/Y H:i') . '.';
            $this->messageType = 'warning';
            $this->attendee = $orderItem;
            return;
        }

        // Validar o ingresso
        $orderItem->checked_in = true;
        $orderItem->checked_in_at = now();
        $orderItem->save();

        $this->message = 'Ingresso validado com sucesso!';
        $this->messageType = 'success';
        $this->attendee = $orderItem;
        $this->qrCode = '';
    }

    public function render()
    {
        $checkedInCount = OrderItem::whereHas('ticket', function ($query) {
            $query->whereHas('event', function ($subQuery) {
                $subQuery->where('id', $this->event->id);
            });
        })
            ->where('checked_in', true)
            ->count();

        $totalTickets = OrderItem::whereHas('ticket', function ($query) {
            $query->whereHas('event', function ($subQuery) {
                $subQuery->where('id', $this->event->id);
            });
        })
            ->count();

        return view('livewire.organizer.scan-ticket', [
            'checkedInCount' => $checkedInCount,
            'totalTickets' => $totalTickets,
        ])->layout('components.layouts.app', ['header' => 'Validar Ingressos']);
    }
}
