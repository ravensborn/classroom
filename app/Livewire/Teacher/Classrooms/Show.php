<?php

namespace App\Livewire\Teacher\Classrooms;

use App\Models\Classroom;
use App\Models\Video;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class Show extends Component
{
    public Classroom $classroom;

    public bool $showUploadModal = false;

    public bool $showEditModal = false;

    public ?int $editingVideoId = null;

    public string $title = '';

    public string $description = '';

    public string $pendingFilePath = '';

    public ?int $confirmingDeleteId = null;

    public ?int $showingAttendanceForVideoId = null;

    public ?int $showingCommentsForVideoId = null;

    public function mount(Classroom $classroom): void
    {
        abort_unless(
            auth()->user()->classrooms()->where('classrooms.id', $classroom->id)->exists(),
            403
        );

        $this->classroom = $classroom;
    }

    public function openUploadModal(): void
    {
        $this->reset(['title', 'description', 'pendingFilePath']);
        $this->showUploadModal = true;
    }

    public function closeUploadModal(): void
    {
        $this->showUploadModal = false;
        $this->reset(['title', 'description', 'pendingFilePath']);
    }

    /**
     * Generate a presigned PUT URL so the browser can upload directly to R2,
     * bypassing Cloudflare's 100 MB request limit.
     *
     * @return array{url: string, path: string}
     */
    public function getPresignedUploadUrl(string $extension): array
    {
        $allowed = ['mp4', 'mov', 'avi'];
        abort_unless(in_array(strtolower($extension), $allowed), 422);

        $path = 'videos/'.Str::uuid().'.'.$extension;

        $diskConfig = config('filesystems.disks.r2');

        $client = new S3Client([
            'credentials' => [
                'key' => $diskConfig['key'],
                'secret' => $diskConfig['secret'],
            ],
            'region' => $diskConfig['region'],
            'endpoint' => $diskConfig['endpoint'],
            'use_path_style_endpoint' => $diskConfig['use_path_style_endpoint'] ?? false,
            'version' => 'latest',
        ]);

        $command = $client->getCommand('PutObject', [
            'Bucket' => $diskConfig['bucket'],
            'Key' => $path,
        ]);

        $presignedUrl = (string) $client->createPresignedRequest($command, '+60 minutes')->getUri();

        $this->pendingFilePath = $path;

        return ['url' => $presignedUrl, 'path' => $path];
    }

    public function saveVideoRecord(): void
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'pendingFilePath' => ['required', 'string'],
        ]);

        Video::create([
            'classroom_id' => $this->classroom->id,
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'file_path' => $this->pendingFilePath,
        ]);

        $this->showUploadModal = false;
        $this->reset(['title', 'description', 'pendingFilePath']);
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
