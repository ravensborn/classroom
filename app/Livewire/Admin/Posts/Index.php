<?php

namespace App\Livewire\Admin\Posts;

use App\Models\Video;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $videos = Video::query()
            ->with(['teacher:id,name', 'classroom:id,name'])
            ->withCount(['comments', 'attendances'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('title', 'ilike', "%{$this->search}%")
                    ->orWhereHas('teacher', fn ($q) => $q->where('name', 'ilike', "%{$this->search}%"))
                    ->orWhereHas('classroom', fn ($q) => $q->where('name', 'ilike', "%{$this->search}%"));
            }))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.posts.index', compact('videos'))
            ->layout('components.layouts.admin');
    }
}
