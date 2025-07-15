<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Console\Command;

class CheckDatabase extends Command
{
    protected $signature = 'check:database';
    protected $description = 'Verificar dados do banco de dados';

    public function handle()
    {
        $this->info('=== VERIFICANDO DADOS DO BANCO ===');

        // Verificar eventos
        $this->info('EVENTOS:');
        $events = Event::all();
        foreach ($events as $event) {
            $this->line("ID: {$event->id} - {$event->title} (Status: {$event->status}, Slug: {$event->slug})");
        }

        // Verificar ingressos
        $this->info('INGRESSOS:');
        $tickets = Ticket::with('event')->get();
        foreach ($tickets as $ticket) {
            $this->line("ID: {$ticket->id} - {$ticket->name} (Evento: {$ticket->event->title})");
            $this->line("  Preço: R$ {$ticket->price}");
            $this->line("  Quantidade: {$ticket->quantity}");
            $this->line("  Vendidos: {$ticket->sold_quantity}");
            $this->line("  Ativo: " . ($ticket->is_active ? 'Sim' : 'Não'));
            $this->line("  Disponível: {$ticket->available_quantity}");
            $this->line("  Esgotado: " . ($ticket->is_sold_out ? 'Sim' : 'Não'));
            $this->line("  Em Venda: " . ($ticket->is_on_sale ? 'Sim' : 'Não'));
            $this->line("  Venda Início: " . ($ticket->sale_start_date ? $ticket->sale_start_date->format('d/m/Y H:i') : 'Não definido'));
            $this->line("  Venda Fim: " . ($ticket->sale_end_date ? $ticket->sale_end_date->format('d/m/Y H:i') : 'Não definido'));
            $this->line('');
        }

        return 0;
    }
}
