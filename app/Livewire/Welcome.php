<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;
use App\Models\Category;

class Welcome extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = null;

    protected $queryString = ['search', 'selectedCategory'];

    public function filterByCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Event::query()
            ->with(['category', 'tickets'])
            ->where('is_published', true)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%')
                        ->orWhere('location', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->orderBy('start_date');

        // Buscar apenas categorias que têm eventos publicados
        $categories = Category::whereHas('events', function ($query) {
            $query->where('is_published', true);
        })->orderBy('name')->get();

        return view('welcome', [
            'events' => $query->paginate(12),
            'categories' => $categories,
        ])->layout('layouts.guest');
    }
}
