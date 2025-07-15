<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FixTicketDates extends Command
{
    protected $signature = 'fix:ticket-dates';
    protected $description = 'Corrigir datas de venda dos ingressos';

    public function handle()
    {
        $this->info('Corrigindo datas de venda dos ingressos...');

        // Buscar o Evento 2
        $event = Event::where('slug', 'evento-2')->first();

        if (!$event) {
            $this->error('Evento 2 não encontrado!');
            return 1;
        }

        // Atualizar todos os ingressos do Evento 2
        $tickets = Ticket::where('event_id', $event->id)->get();

        foreach ($tickets as $ticket) {
            $ticket->update([
                'sale_start_date' => Carbon::now()->subDays(1), // Venda começou ontem
                'sale_end_date' => Carbon::now()->addMonths(2), // Venda termina em 2 meses
            ]);

            $this->line("Ingresso '{$ticket->name}' atualizado:");
            $this->line("  Venda início: " . $ticket->sale_start_date->format('d/m/Y H:i'));
            $this->line("  Venda fim: " . $ticket->sale_end_date->format('d/m/Y H:i'));
            $this->line("  Em venda: " . ($ticket->is_on_sale ? 'Sim' : 'Não'));
            $this->line('');
        }

        $this->info('Datas corrigidas com sucesso!');
        return 0;
    }
}
