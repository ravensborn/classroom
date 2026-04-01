<?php

namespace App\Livewire\Admin\Students;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $confirmingDeleteId = null;

    public ?int $blockingUserId = null;

    public string $blockedMessage = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleActive(int $userId): void
    {
        $user = User::students()->findOrFail($userId);

        if ($user->is_active) {
            $this->blockingUserId = $userId;
            $this->blockedMessage = $user->blocked_message ?? '';
        } else {
            $user->update(['is_active' => true, 'blocked_message' => null]);
        }
    }

    public function confirmBlock(): void
    {
        $this->validate(['blockedMessage' => ['required', 'string', 'max:255']]);

        User::students()->findOrFail($this->blockingUserId)->update([
            'is_active' => false,
            'blocked_message' => $this->blockedMessage,
        ]);

        $this->blockingUserId = null;
        $this->blockedMessage = '';
    }

    public function cancelBlock(): void
    {
        $this->blockingUserId = null;
        $this->blockedMessage = '';
    }

    public function confirmDelete(int $userId): void
    {
        $this->confirmingDeleteId = $userId;
    }

    public function deleteStudent(): void
    {
        User::students()->findOrFail($this->confirmingDeleteId)->delete();
        $this->confirmingDeleteId = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function render()
    {
        $students = User::students()
            ->with('department')
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'ilike', "%{$this->search}%")
                  ->orWhere('username', 'ilike', "%{$this->search}%");
            }))
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.students.index', compact('students'))
            ->layout('components.layouts.admin');
    }
}
