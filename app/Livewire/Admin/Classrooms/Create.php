<?php

namespace App\Livewire\Admin\Classrooms;

use App\Models\Classroom;
use Livewire\Component;

class Create extends Component
{
    public string $name = '';

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:classrooms,name'],
        ]);

        Classroom::create(['name' => $this->name]);

        $this->redirect(route('admin.classrooms.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.classrooms.create')
            ->layout('components.layouts.admin');
    }
}
