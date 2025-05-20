<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class CategoryManager extends Component
{
    use WithPagination;

    public $name = '';
    public $description = '';
    public $editingCategoryId = null;

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'description' => 'nullable|max:1000',
    ];

    public function save()
    {
        $this->validate();

        if ($this->editingCategoryId) {
            $category = Category::find($this->editingCategoryId);
            $category->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
            ]);
            $this->dispatch('toast', message: 'Categoria atualizada com sucesso!', type: 'success');
        } else {
            Category::create([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
            ]);
            $this->dispatch('toast', message: 'Categoria criada com sucesso!', type: 'success');
        }

        $this->reset(['name', 'description', 'editingCategoryId']);
    }

    public function edit($categoryId)
    {
        $category = Category::find($categoryId);
        $this->editingCategoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function delete($categoryId)
    {
        $category = Category::find($categoryId);
        if ($category->events()->count() > 0) {
            $this->dispatch('toast', message: 'Não é possível excluir uma categoria que possui eventos!', type: 'error');
            return;
        }
        $category->delete();
        $this->dispatch('toast', message: 'Categoria excluída com sucesso!', type: 'success');
    }

    public function cancel()
    {
        $this->reset(['name', 'description', 'editingCategoryId']);
    }

    public function render()
    {
        return view('livewire.admin.category-manager', [
            'categories' => Category::orderBy('name')->paginate(10)
        ])->layout('components.layouts.app', ['header' => 'Gerenciar Categorias']);
    }
}
