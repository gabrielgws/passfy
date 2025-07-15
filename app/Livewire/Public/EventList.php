<?php

declare(strict_types=1);

namespace App\Livewire\Public;

use App\Models\Category;
use App\Models\Event;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class EventList extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $cityFilter = '';
    public $stateFilter = '';
    public $dateFilter = '';
    public $categories = [];
    public $cities = [];
    public $states = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'cityFilter' => ['except' => ''],
        'stateFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
    ];

    public function mount()
    {
        $this->loadFilters();
    }

    public function loadFilters()
    {
        $this->categories = Category::active()->orderBy('name')->get();

        // Carregar cidades e estados únicos dos eventos publicados
        $this->cities = Event::published()
            ->select('city')
            ->distinct()
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->orderBy('city')
            ->pluck('city');

        $this->states = Event::published()
            ->select('state')
            ->distinct()
            ->whereNotNull('state')
            ->where('state', '!=', '')
            ->orderBy('state')
            ->pluck('state');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatedCityFilter()
    {
        $this->resetPage();
    }

    public function updatedStateFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'categoryFilter', 'cityFilter', 'stateFilter', 'dateFilter']);
        $this->resetPage();
    }

    public function getEventsProperty()
    {
        $query = Event::published()
            ->with(['category', 'tickets'])
            ->where('start_date', '>=', now())
            ->orderBy('start_date');

        // Filtro de busca
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('short_description', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Filtro de categoria
        if (!empty($this->categoryFilter)) {
            $query->where('category_id', $this->categoryFilter);
        }

        // Filtro de cidade
        if (!empty($this->cityFilter)) {
            $query->where('city', $this->cityFilter);
        }

        // Filtro de estado
        if (!empty($this->stateFilter)) {
            $query->where('state', $this->stateFilter);
        }

        // Filtro de data
        if (!empty($this->dateFilter)) {
            switch ($this->dateFilter) {
                case 'today':
                    $query->whereDate('start_date', today());
                    break;
                case 'tomorrow':
                    $query->whereDate('start_date', Carbon::tomorrow());
                    break;
                case 'this_week':
                    $query->whereBetween('start_date', [now(), now()->endOfWeek()]);
                    break;
                case 'this_month':
                    $query->whereBetween('start_date', [now(), now()->endOfMonth()]);
                    break;
            }
        }

        return $query->paginate(12);
    }

    public function render()
    {
        return view('livewire.public.event-list');
    }
}
