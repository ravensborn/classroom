<?php

namespace App\Livewire\Student;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $classrooms = auth()->user()->classrooms()->withCount('videos')->orderBy('name')->get();

        return view('livewire.student.dashboard', compact('classrooms'))
            ->layout('components.layouts.portal');
    }
}
