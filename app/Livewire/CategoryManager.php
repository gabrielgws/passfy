<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Str;

class CategoryManager extends Component
{
    public $categories;
    public $name = '';
    public $slug = '';
    public $description = '';
    public $color = '#3B82F6';
    public $icon = '';
    public $is_active = true;
    public $editingId = null;
    public $showModal = false;
    public $confirmingDelete = false;
    public $deleteId = null;

    public function mount()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso restrito a administradores.');
        }
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = Category::orderBy('name')->get();
    }

    public function showCreateModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function showEditModal($id)
    {
        $category = Category::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->description = $category->description;
        $this->color = $category->color;
        $this->icon = $category->icon;
        $this->is_active = $category->is_active;
        $this->showModal = true;
    }

    public function saveCategory()
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|unique:categories,slug,' . $this->editingId,
            'color' => 'required|string|max:7',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $slug = $this->slug ?: Str::slug($this->name);

        if ($this->editingId) {
            $category = Category::findOrFail($this->editingId);
            $category->update([
                'name' => $this->name,
                'slug' => $slug,
                'description' => $this->description,
                'color' => $this->color,
                'icon' => $this->icon,
                'is_active' => $this->is_active,
            ]);
        } else {
            Category::create([
                'name' => $this->name,
                'slug' => $slug,
                'description' => $this->description,
                'color' => $this->color,
                'icon' => $this->icon,
                'is_active' => $this->is_active,
            ]);
        }

        $this->showModal = false;
        $this->resetForm();
        $this->loadCategories();
        session()->flash('success', 'Categoria salva com sucesso!');
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteCategory()
    {
        $category = Category::findOrFail($this->deleteId);
        $category->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->loadCategories();
        session()->flash('success', 'Categoria removida com sucesso!');
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->color = '#3B82F6';
        $this->icon = '';
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.category-manager');
    }
}
