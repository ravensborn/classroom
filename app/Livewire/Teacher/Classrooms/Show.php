<?php

namespace App\Livewire\Teacher\Classrooms;

use App\Models\Classroom;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public Classroom $classroom;

    public bool $showUploadModal = false;

    public bool $showEditModal = false;

    public ?int $editingVideoId = null;

    public string $title = '';

    public string $description = '';

    public $videoFile = null;

    public ?int $confirmingDeleteId = null;

    public ?int $showingAttendanceForVideoId = null;

    public ?int $showingCommentsForVideoId = null;

    public function mount(Classroom $classroom): void
    {
        // Ensure teacher is assigned to this classroom
        abort_unless(
            auth()->user()->classrooms()->where('classrooms.id', $classroom->id)->exists(),
            403
        );

        $this->classroom = $classroom;
    }

    public function openUploadModal(): void
    {
        $this->reset(['title', 'description', 'videoFile']);
        $this->showUploadModal = true;
    }

    public function closeUploadModal(): void
    {
        $this->showUploadModal = false;
        $this->reset(['title', 'description', 'videoFile']);
    }

    public function uploadVideo(): void
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'videoFile' => ['required', 'file', 'mimes:mp4,mov,avi', 'max:512000'],
        ]);

        $path = $this->videoFile->storeAs(
            'videos',
            \Illuminate\Support\Str::uuid().'.'.$this->videoFile->getClientOriginalExtension(),
            'r2'
        );

        Video::create([
            'classroom_id' => $this->classroom->id,
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'file_path' => $path,
        ]);

        $this->showUploadModal = false;
        $this->reset(['title', 'description', 'videoFile']);
    }

    public function openEditModal(int $videoId): void
    {
        $video = Video::where('classroom_id', $this->classroom->id)
            ->where('user_id', auth()->id())
            ->findOrFail($videoId);

        $this->editingVideoId = $videoId;
        $this->title = $video->title;
        $this->description = $video->description;
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingVideoId = null;
        $this->reset(['title', 'description']);
    }

    public function saveEdit(): void
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        Video::where('classroom_id', $this->classroom->id)
            ->where('user_id', auth()->id())
            ->findOrFail($this->editingVideoId)
            ->update(['title' => $this->title, 'description' => $this->description]);

        $this->showEditModal = false;
        $this->editingVideoId = null;
        $this->reset(['title', 'description']);
    }

    public function confirmDelete(int $videoId): void
    {
        $this->confirmingDeleteId = $videoId;
    }

    public function deleteVideo(): void
    {
        $video = Video::where('classroom_id', $this->classroom->id)
            ->where('user_id', auth()->id())
            ->findOrFail($this->confirmingDeleteId);

        Storage::disk('r2')->delete($video->file_path);
        $video->delete();

        $this->confirmingDeleteId = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function toggleAttendance(int $videoId): void
    {
        $this->showingAttendanceForVideoId = $this->showingAttendanceForVideoId === $videoId ? null : $videoId;
    }

    public function toggleComments(int $videoId): void
    {
        $this->showingCommentsForVideoId = $this->showingCommentsForVideoId === $videoId ? null : $videoId;
    }

    public function deleteComment(int $commentId): void
    {
        \App\Models\VideoComment::whereHas('video', fn ($q) => $q->where('classroom_id', $this->classroom->id))
            ->findOrFail($commentId)
            ->delete();
    }

    public function render()
    {
        $videos = Video::where('classroom_id', $this->classroom->id)
            ->where('user_id', auth()->id())
            ->with('teacher')
            ->withCount('comments')
            ->latest()
            ->get();

        $attendances = collect();
        if ($this->showingAttendanceForVideoId) {
            $attendances = \App\Models\VideoAttendance::where('video_id', $this->showingAttendanceForVideoId)
                ->with('student.department')
                ->latest()
                ->get();
        }

        $comments = collect();
        if ($this->showingCommentsForVideoId) {
            $comments = \App\Models\VideoComment::where('video_id', $this->showingCommentsForVideoId)
                ->with('author.department')
                ->latest()
                ->get();
        }

        return view('livewire.teacher.classrooms.show', compact('videos', 'attendances', 'comments'))
            ->layout('components.layouts.portal');
    }
}
