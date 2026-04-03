<?php

namespace App\Livewire\Student\Classrooms;

use App\Models\Classroom;
use App\Models\Video;
use App\Models\VideoAttendance;
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

    public function markAttendance(int $videoId): void
    {
        $video = Video::where('classroom_id', $this->classroom->id)->findOrFail($videoId);

        abort_unless($video->isAttendanceOpen(), 403);

        VideoAttendance::firstOrCreate([
            'video_id' => $videoId,
            'user_id' => auth()->id(),
        ]);
    }

    public function render()
    {
        $videos = Video::where('classroom_id', $this->classroom->id)
            ->with('teacher')
            ->latest()
            ->get();

        $attendedVideoIds = VideoAttendance::where('user_id', auth()->id())
            ->whereIn('video_id', $videos->pluck('id'))
            ->pluck('video_id')
            ->toArray();

        return view('livewire.student.classrooms.show', compact('videos', 'attendedVideoIds'))
            ->layout('components.layouts.portal');
    }
}
