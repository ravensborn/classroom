<?php

namespace App\Livewire\Admin\Teachers;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $confirmingDeleteId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $userId): void
    {
        $this->confirmingDeleteId = $userId;
    }

    public function deleteTeacher(): void
    {
        User::teachers()->findOrFail($this->confirmingDeleteId)->delete();
        $this->confirmingDeleteId = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function render()
    {
        $teachers = User::teachers()
            ->withCount('posts')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'ilike', "%{$this->search}%")
                  ->orWhere('username', 'ilike', "%{$this->search}%");
            }))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.teachers.index', compact('teachers'))
            ->layout('components.layouts.admin');
    }
}
