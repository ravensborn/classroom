<?php

namespace App\Livewire\Admin\Classrooms;

use App\Models\Classroom;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public Classroom $classroom;

    public string $name = '';

    /** @var array<int> */
    public array $selectedTeacherIds = [];

    /** @var array<int> */
    public array $selectedStudentIds = [];

    public function mount(Classroom $classroom): void
    {
        $this->classroom = $classroom;
        $this->name = $classroom->name;
        $this->selectedTeacherIds = $classroom->teachers()->pluck('users.id')->toArray();
        $this->selectedStudentIds = $classroom->students()->pluck('users.id')->toArray();
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255', "unique:classrooms,name,{$this->classroom->id}"],
            'selectedTeacherIds' => ['array'],
            'selectedStudentIds' => ['array'],
        ]);

        $this->classroom->update(['name' => $this->name]);

        // Sync teachers and students through the pivot (user_id)
        $this->classroom->users()->sync(
            array_merge($this->selectedTeacherIds, $this->selectedStudentIds)
        );

        $this->redirect(route('admin.classrooms.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.classrooms.edit', [
            'teachers' => User::teachers()->orderBy('name')->get(),
            'students' => User::students()->with('department')->orderBy('name')->get(),
        ])->layout('components.layouts.admin');
    }
}
