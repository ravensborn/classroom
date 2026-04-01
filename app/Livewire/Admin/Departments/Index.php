<?php

namespace App\Livewire\Admin\Departments;

use App\Models\Department;
use Livewire\Component;

class Index extends Component
{
    public string $newName = '';

    public ?int $editingId = null;

    public string $editingName = '';

    public ?int $confirmingDeleteId = null;

    public function create(): void
    {
        $this->validate(['newName' => ['required', 'string', 'max:255', 'unique:departments,name']]);

        Department::create(['name' => $this->newName]);

        $this->newName = '';
    }

    public function startEdit(int $id): void
    {
        $this->editingId = $id;
        $this->editingName = Department::findOrFail($id)->name;
    }

    public function saveEdit(): void
    {
        $this->validate(['editingName' => ['required', 'string', 'max:255', "unique:departments,name,{$this->editingId}"]]);

        Department::findOrFail($this->editingId)->update(['name' => $this->editingName]);

        $this->editingId = null;
        $this->editingName = '';
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->editingName = '';
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmingDeleteId = $id;
    }

    public function deleteDepartment(): void
    {
        Department::findOrFail($this->confirmingDeleteId)->delete();
        $this->confirmingDeleteId = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function render()
    {
        return view('livewire.admin.departments.index', [
            'departments' => Department::withCount('students')->orderBy('name')->get(),
        ])->layout('components.layouts.admin');
    }
}
