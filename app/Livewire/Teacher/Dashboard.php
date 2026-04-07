<?php

namespace App\Livewire\Teacher;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $classrooms = auth()->user()->classrooms()->withCount('posts')->orderBy('name')->get();

        return view('livewire.teacher.dashboard', compact('classrooms'))
            ->layout('components.layouts.portal');
    }
}
