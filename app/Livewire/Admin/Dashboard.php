<?php

namespace App\Livewire\Admin;

use App\Models\Classroom;
use App\Models\User;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'studentsCount' => User::students()->count(),
            'teachersCount' => User::teachers()->count(),
            'classroomsCount' => Classroom::count(),
        ])->layout('components.layouts.admin');
    }
}
