<?php

namespace App\Livewire\Admin\Teachers;

use App\Enums\UserRole;
use App\Models\User;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';

    public string $username = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'same:passwordConfirmation'],
        ]);

        User::create([
            'name' => $this->name,
            'username' => $this->username,
            'password' => $this->password,
            'role' => UserRole::Teacher,
        ]);

        $this->redirect(route('admin.teachers.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.teachers.create')
            ->layout('components.layouts.admin');
    }
}
