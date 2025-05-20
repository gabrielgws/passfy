<?php

namespace App\Livewire\Events;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;
use App\Models\Category;

class EventList extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCategory = null;

    public function filterByCategory($categoryId)
    {
        $this->selectedCategory = $categoryId;
        $this->resetPage();
    }

    public function updatingSearch()
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

        return view('events.index', [
            'events' => $query->paginate(12),
            'categories' => $categories,
        ])->layout('layouts.guest');
    }
}
