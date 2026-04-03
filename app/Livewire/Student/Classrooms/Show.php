<?php

namespace App\Livewire\Student\Classrooms;

use App\Models\Classroom;
use App\Models\Video;
use App\Models\VideoAttendance;
use App\Models\VideoComment;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Show extends Component
{
    public Classroom $classroom;

    public ?int $playingVideoId = null;

    public ?string $playingVideoUrl = null;

    public ?int $showingCommentsForVideoId = null;

    public string $commentText = '';

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

    public function toggleComments(int $videoId): void
    {
        if ($this->showingCommentsForVideoId === $videoId) {
            $this->showingCommentsForVideoId = null;
        } else {
            $this->showingCommentsForVideoId = $videoId;
            $this->commentText = '';
        }
    }

    public function addComment(int $videoId): void
    {
        Video::where('classroom_id', $this->classroom->id)->findOrFail($videoId);

        $this->validate(['commentText' => ['required', 'string', 'max:1000']]);

        VideoComment::create([
            'video_id' => $videoId,
            'user_id' => auth()->id(),
            'body' => $this->commentText,
        ]);

        $this->commentText = '';
    }

    public function deleteComment(int $commentId): void
    {
        VideoComment::where('user_id', auth()->id())->findOrFail($commentId)->delete();
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
            ->with(['teacher', 'comments' => fn ($q) => $q->with('author')])
            ->withCount('comments')
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
