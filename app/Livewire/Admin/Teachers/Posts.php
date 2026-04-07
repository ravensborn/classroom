<?php

namespace App\Livewire\Admin\Teachers;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;

    public User $user;

    public function mount(User $user): void
    {
        abort_unless($user->isTeacher(), 404);
        $this->user = $user;
    }

    public function render()
    {
        $videos = $this->user->videos()
            ->with('classroom:id,name')
            ->withCount(['comments', 'attendances'])
            ->latest()
            ->paginate(15);

        return view('livewire.admin.teachers.posts', [
            'teacher' => $this->user,
            'videos' => $videos,
        ])->layout('components.layouts.admin');
    }
}
