<?php

namespace App\Livewire\Admin\Students;

use App\Models\Department;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public User $user;

    public string $name = '';

    public string $username = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public ?int $departmentId = null;

    public int $stage = 1;

    public bool $isActive = true;

    public string $blockedMessage = '';

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->departmentId = $user->department_id;
        $this->stage = $user->stage ?? 1;
        $this->isActive = $user->is_active;
        $this->blockedMessage = $user->blocked_message ?? '';
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', "unique:users,username,{$this->user->id}"],
            'password' => ['nullable', 'string', 'min:8', 'same:passwordConfirmation'],
            'departmentId' => ['required', 'exists:departments,id'],
            'stage' => ['required', 'integer', 'min:1', 'max:5'],
            'blockedMessage' => ['nullable', 'string', 'max:255'],
        ]);

        $data = [
            'name' => $this->name,
            'username' => $this->username,
            'department_id' => $this->departmentId,
            'stage' => $this->stage,
            'is_active' => $this->isActive,
            'blocked_message' => $this->isActive ? null : ($this->blockedMessage ?: null),
        ];

        if ($this->password) {
            $data['password'] = $this->password;
        }

        $this->user->update($data);

        $this->redirect(route('admin.students.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.students.edit', [
            'departments' => Department::orderBy('name')->get(),
        ])->layout('components.layouts.admin');
    }
}
