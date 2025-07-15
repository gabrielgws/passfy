<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Shows e Música',
                'slug' => 'shows-musica',
                'description' => 'Eventos musicais, shows, festivais e apresentações artísticas',
                'color' => '#3B82F6',
                'icon' => 'music-note',
                'is_active' => true,
            ],
            [
                'name' => 'Tecnologia',
                'slug' => 'tecnologia',
                'description' => 'Conferências, workshops e eventos de tecnologia e inovação',
                'color' => '#10B981',
                'icon' => 'computer-desktop',
                'is_active' => true,
            ],
            [
                'name' => 'Negócios',
                'slug' => 'negocios',
                'description' => 'Palestras, workshops e eventos corporativos',
                'color' => '#F59E0B',
                'icon' => 'briefcase',
                'is_active' => true,
            ],
            [
                'name' => 'Esportes',
                'slug' => 'esportes',
                'description' => 'Eventos esportivos, campeonatos e atividades físicas',
                'color' => '#EF4444',
                'icon' => 'trophy',
                'is_active' => true,
            ],
            [
                'name' => 'Educação',
                'slug' => 'educacao',
                'description' => 'Cursos, workshops educacionais e eventos acadêmicos',
                'color' => '#8B5CF6',
                'icon' => 'academic-cap',
                'is_active' => true,
            ],
            [
                'name' => 'Gastronomia',
                'slug' => 'gastronomia',
                'description' => 'Festivais gastronômicos, degustações e eventos culinários',
                'color' => '#F97316',
                'icon' => 'cake',
                'is_active' => true,
            ],
            [
                'name' => 'Arte e Cultura',
                'slug' => 'arte-cultura',
                'description' => 'Exposições, galerias e eventos culturais',
                'color' => '#EC4899',
                'icon' => 'paint-brush',
                'is_active' => true,
            ],
            [
                'name' => 'Saúde e Bem-estar',
                'slug' => 'saude-bem-estar',
                'description' => 'Eventos de saúde, yoga, meditação e bem-estar',
                'color' => '#06B6D4',
                'icon' => 'heart',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
