<?php

namespace App\Livewire\Admin\Classrooms;

use App\Models\Classroom;
use App\Models\Department;
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

    public string $teacherSearch = '';

    public string $studentSearch = '';

    public ?int $studentStage = null;

    public ?int $studentDepartment = null;

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
        $teachers = User::teachers()
            ->when($this->teacherSearch, fn ($q) => $q->where('name', 'ilike', "%{$this->teacherSearch}%"))
            ->orderBy('name')
            ->get();

        $students = User::students()
            ->with('department')
            ->when($this->studentSearch, fn ($q) => $q->where('name', 'ilike', "%{$this->studentSearch}%"))
            ->when($this->studentStage, fn ($q) => $q->where('stage', $this->studentStage))
            ->when($this->studentDepartment, fn ($q) => $q->where('department_id', $this->studentDepartment))
            ->orderBy('name')
            ->get();

        return view('livewire.admin.classrooms.edit', [
            'teachers' => $teachers,
            'students' => $students,
            'departments' => Department::orderBy('name')->get(),
            'stages' => User::students()->distinct()->orderBy('stage')->pluck('stage'),
        ])->layout('components.layouts.admin');
    }
}
