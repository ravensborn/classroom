<?php

namespace App\Livewire\Admin\Students;

use App\Enums\UserRole;
use App\Models\Department;
use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';

    public string $username = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public ?int $departmentId = null;

    public int $stage = 1;

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'same:passwordConfirmation'],
            'departmentId' => ['required', 'exists:departments,id'],
            'stage' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        User::create([
            'name' => $this->name,
            'username' => $this->username,
            'password' => $this->password,
            'role' => UserRole::Student,
            'department_id' => $this->departmentId,
            'stage' => $this->stage,
        ]);

        $this->redirect(route('admin.students.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.students.create', [
            'departments' => Department::orderBy('name')->get(),
        ])->layout('components.layouts.admin');
    }
}
