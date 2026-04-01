<?php

namespace App\Livewire\Student\Classrooms;

use App\Models\Classroom;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Show extends Component
{
    public Classroom $classroom;

    public ?int $playingVideoId = null;

    public ?string $playingVideoUrl = null;

    public function mount(Classroom $classroom): void
    {
        abort_unless(
            auth()->user()->classrooms()->where('classrooms.id', $classroom->id)->exists(),
            403
        );

        $this->classroom = $classroom;
    }

    public function playVideo(int $videoId): void
    {
        $video = Video::where('classroom_id', $this->classroom->id)->findOrFail($videoId);

        $this->playingVideoId = $videoId;
        $this->playingVideoUrl = Storage::disk('r2')->temporaryUrl($video->file_path, now()->addHours(2));
    }

    public function closePlayer(): void
    {
        $this->playingVideoId = null;
        $this->playingVideoUrl = null;
    }

    public function render()
    {
        $videos = Video::where('classroom_id', $this->classroom->id)
            ->with('teacher')
            ->latest()
            ->get();

        return view('livewire.student.classrooms.show', compact('videos'))
            ->layout('components.layouts.portal');
    }
}
