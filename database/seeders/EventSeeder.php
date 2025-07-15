<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar usuário e categoria existentes
        $user = User::first();
        $categories = Category::all();

        if (!$user || $categories->isEmpty()) {
            return;
        }

        $events = [
            [
                'title' => 'Festival de Música Eletrônica 2024',
                'short_description' => 'O maior festival de música eletrônica do ano com os melhores DJs internacionais.',
                'description' => 'Prepare-se para uma noite inesquecível com os melhores DJs internacionais. O Festival de Música Eletrônica 2024 promete ser o evento mais eletrizante do ano, com 12 horas de música, luzes e muita energia!',
                'category_id' => $categories->where('name', 'Música')->first()?->id ?? $categories->first()->id,
                'location' => 'Parque da Cidade',
                'address' => 'Av. das Palmeiras, 1000',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01234-567',
                'start_date' => Carbon::now()->addDays(30)->setTime(20, 0),
                'end_date' => Carbon::now()->addDays(30)->setTime(8, 0),
                'status' => 'published',
            ],
            [
                'title' => 'Workshop de Culinária Italiana',
                'short_description' => 'Aprenda a fazer massas e molhos autênticos da culinária italiana.',
                'description' => 'Neste workshop prático, você aprenderá técnicas tradicionais da culinária italiana, incluindo preparo de massas frescas, molhos autênticos e sobremesas típicas. Todos os ingredientes estão incluídos.',
                'category_id' => $categories->where('name', 'Gastronomia')->first()?->id ?? $categories->first()->id,
                'location' => 'Escola de Culinária Bella Italia',
                'address' => 'Rua das Flores, 250',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
                'zip_code' => '20000-000',
                'start_date' => Carbon::now()->addDays(15)->setTime(14, 0),
                'end_date' => Carbon::now()->addDays(15)->setTime(18, 0),
                'status' => 'published',
            ],
            [
                'title' => 'Palestra: Inovação em Tecnologia',
                'short_description' => 'Descubra as últimas tendências em tecnologia e inovação.',
                'description' => 'Uma palestra imperdível sobre as últimas tendências em tecnologia, incluindo IA, blockchain, IoT e muito mais. Ideal para profissionais da área e entusiastas de tecnologia.',
                'category_id' => $categories->where('name', 'Tecnologia')->first()?->id ?? $categories->first()->id,
                'location' => 'Centro de Convenções',
                'address' => 'Av. Paulista, 1000',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01310-100',
                'start_date' => Carbon::now()->addDays(7)->setTime(19, 0),
                'end_date' => Carbon::now()->addDays(7)->setTime(22, 0),
                'status' => 'published',
            ],
            [
                'title' => 'Exposição de Arte Contemporânea',
                'short_description' => 'Exposição com obras de artistas contemporâneos brasileiros.',
                'description' => 'Uma exposição única que reúne obras de artistas contemporâneos brasileiros, explorando diferentes técnicas e expressões artísticas. Visita guiada incluída.',
                'category_id' => $categories->where('name', 'Arte')->first()?->id ?? $categories->first()->id,
                'location' => 'Museu de Arte Moderna',
                'address' => 'Rua das Artes, 500',
                'city' => 'Belo Horizonte',
                'state' => 'MG',
                'zip_code' => '30000-000',
                'start_date' => Carbon::now()->addDays(45)->setTime(10, 0),
                'end_date' => Carbon::now()->addDays(45)->setTime(18, 0),
                'status' => 'published',
            ],
        ];

        foreach ($events as $eventData) {
            $event = Event::create(array_merge($eventData, [
                'user_id' => $user->id,
            ]));

            // Criar ingressos para cada evento
            $tickets = [
                [
                    'name' => 'Ingresso Padrão',
                    'description' => 'Acesso completo ao evento',
                    'price' => 50.00,
                    'quantity' => 100,
                    'sold_quantity' => 0,
                    'min_per_order' => 1,
                    'max_per_order' => 4,
                    'is_active' => true,
                ],
                [
                    'name' => 'Ingresso VIP',
                    'description' => 'Acesso VIP com área exclusiva e open bar',
                    'price' => 150.00,
                    'quantity' => 50,
                    'sold_quantity' => 0,
                    'min_per_order' => 1,
                    'max_per_order' => 2,
                    'is_active' => true,
                ],
            ];

            foreach ($tickets as $ticketData) {
                Ticket::create(array_merge($ticketData, [
                    'event_id' => $event->id,
                ]));
            }
        }
    }
}
