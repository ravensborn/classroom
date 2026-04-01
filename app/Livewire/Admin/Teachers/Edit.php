<?php

namespace App\Livewire\Admin\Teachers;

use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public User $user;

    public string $name = '';

    public string $username = '';

    public string $password = '';

    public string $passwordConfirmation = '';

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->username = $user->username;
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', "unique:users,username,{$this->user->id}"],
            'password' => ['nullable', 'string', 'min:8', 'same:passwordConfirmation'],
        ]);

        $data = [
            'name' => $this->name,
            'username' => $this->username,
        ];

        if ($this->password) {
            $data['password'] = $this->password;
        }

        $this->user->update($data);

        $this->redirect(route('admin.teachers.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.teachers.edit')
            ->layout('components.layouts.admin');
    }
}
