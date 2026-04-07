<?php

namespace App\Livewire\Teacher\Classrooms;

use App\Models\Classroom;
use App\Models\Post;
use App\Models\PostAttendance;
use App\Models\PostComment;
use App\Support\PostHtmlSanitizer;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class Show extends Component
{
    public Classroom $classroom;

    public bool $showUploadModal = false;

    public bool $showEditModal = false;

    public ?int $editingPostId = null;

    public string $title = '';

    public string $description = '';

    public ?string $pendingVideoPath = null;

    public bool $removeExistingVideo = false;

    public ?int $confirmingDeleteId = null;

    public ?int $showingAttendanceForPostId = null;

    public ?int $showingCommentsForPostId = null;

    public ?int $playingPostId = null;

    public ?string $playingVideoUrl = null;

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
        $this->reset(['title', 'description', 'pendingVideoPath', 'removeExistingVideo']);
        $this->showUploadModal = true;
    }

    public function closeUploadModal(): void
    {
        $this->showUploadModal = false;
        $this->reset(['title', 'description', 'pendingVideoPath', 'removeExistingVideo']);
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
            'use_path_style_endpoint' => true,
            'version' => 'latest',
        ]);

        $command = $client->getCommand('PutObject', [
            'Bucket' => $diskConfig['bucket'],
            'Key' => $path,
        ]);

        $presignedUrl = (string) $client->createPresignedRequest($command, '+60 minutes')->getUri();

        $this->pendingVideoPath = $path;

        return ['url' => $presignedUrl, 'path' => $path];
    }

    public function savePost(): void
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'pendingVideoPath' => ['nullable', 'string'],
        ]);

        Post::create([
            'classroom_id' => $this->classroom->id,
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => PostHtmlSanitizer::clean($this->description),
            'video_path' => $this->pendingVideoPath ?: null,
        ]);

        $this->showUploadModal = false;
        $this->reset(['title', 'description', 'pendingVideoPath', 'removeExistingVideo']);
    }

    public function openEditModal(int $postId): void
    {
        $post = Post::where('classroom_id', $this->classroom->id)
            ->where('user_id', auth()->id())
            ->findOrFail($postId);

        $this->editingPostId = $postId;
        $this->title = $post->title;
        $this->description = $post->description;
        $this->pendingVideoPath = null;
        $this->removeExistingVideo = false;
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingPostId = null;
        $this->reset(['title', 'description', 'pendingVideoPath', 'removeExistingVideo']);
    }

    public function saveEdit(): void
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'pendingVideoPath' => ['nullable', 'string'],
        ]);

        $post = Post::where('classroom_id', $this->classroom->id)
            ->where('user_id', auth()->id())
            ->findOrFail($this->editingPostId);

        $newVideoPath = $post->video_path;

        if ($this->pendingVideoPath) {
            if ($post->video_path) {
                Storage::disk('r2')->delete($post->video_path);
            }
            $newVideoPath = $this->pendingVideoPath;
        } elseif ($this->removeExistingVideo && $post->video_path) {
            Storage::disk('r2')->delete($post->video_path);
            $newVideoPath = null;
        }

        $post->update([
            'title' => $this->title,
            'description' => PostHtmlSanitizer::clean($this->description),
            'video_path' => $newVideoPath,
        ]);

        $this->showEditModal = false;
        $this->editingPostId = null;
        $this->reset(['title', 'description', 'pendingVideoPath', 'removeExistingVideo']);
    }

    public function confirmDelete(int $postId): void
    {
        $this->confirmingDeleteId = $postId;
    }

    public function deletePost(): void
    {
        $post = Post::where('classroom_id', $this->classroom->id)
            ->where('user_id', auth()->id())
            ->findOrFail($this->confirmingDeleteId);

        if ($post->video_path) {
            Storage::disk('r2')->delete($post->video_path);
        }
        $post->delete();

        $this->confirmingDeleteId = null;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function playVideo(int $postId): void
    {
        $post = Post::where('classroom_id', $this->classroom->id)
            ->where('user_id', auth()->id())
            ->findOrFail($postId);

        abort_unless($post->hasVideo(), 404);

        $this->playingPostId = $postId;
        $this->playingVideoUrl = Storage::disk('r2')->temporaryUrl($post->video_path, now()->addHours(2));
    }

    public function closePlayer(): void
    {
        $this->playingPostId = null;
        $this->playingVideoUrl = null;
    }

    public function toggleAttendance(int $postId): void
    {
        $this->showingAttendanceForPostId = $this->showingAttendanceForPostId === $postId ? null : $postId;
    }

    public function toggleComments(int $postId): void
    {
        $this->showingCommentsForPostId = $this->showingCommentsForPostId === $postId ? null : $postId;
    }

    public function deleteComment(int $commentId): void
    {
        PostComment::whereHas('post', fn ($q) => $q->where('classroom_id', $this->classroom->id))
            ->findOrFail($commentId)
            ->delete();
    }

    public function render()
    {
        $posts = Post::where('classroom_id', $this->classroom->id)
            ->where('user_id', auth()->id())
            ->with('teacher')
            ->withCount('comments')
            ->latest()
            ->get();

        $attendances = collect();
        if ($this->showingAttendanceForPostId) {
            $attendances = PostAttendance::where('post_id', $this->showingAttendanceForPostId)
                ->with('student.department')
                ->latest()
                ->get();
        }

        $comments = collect();
        if ($this->showingCommentsForPostId) {
            $comments = PostComment::where('post_id', $this->showingCommentsForPostId)
                ->with('author.department')
                ->latest()
                ->get();
        }

        return view('livewire.teacher.classrooms.show', compact('posts', 'attendances', 'comments'))
            ->layout('components.layouts.portal');
    }
}
