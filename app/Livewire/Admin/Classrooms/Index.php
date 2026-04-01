<?php

namespace App\Livewire\Admin\Classrooms;

use App\Models\Classroom;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?int $confirmingDeleteId = null;

    public function confirmDelete(int $classroomId): void
    {
        $this->confirmingDeleteId = $classroomId;
    }

    public function deleteClassroom(): void
    {
        Classroom::findOrFail($this->confirmingDeleteId)->delete();
        $this->confirmingDeleteId = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function render()
    {
        $classrooms = Classroom::withCount(['teachers', 'students'])
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.classrooms.index', compact('classrooms'))
            ->layout('components.layouts.admin');
    }
}
