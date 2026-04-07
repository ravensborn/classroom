<?php

namespace App\Livewire\Student\Classrooms;

use App\Models\Classroom;
use App\Models\Post;
use App\Models\PostAttendance;
use App\Models\PostComment;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Show extends Component
{
    public Classroom $classroom;

    public ?int $playingPostId = null;

    public ?string $playingVideoUrl = null;

    public ?int $showingCommentsForPostId = null;

    public string $commentText = '';

    public function mount(Classroom $classroom): void
    {
        abort_unless(
            auth()->user()->classrooms()->where('classrooms.id', $classroom->id)->exists(),
            403
        );

        $this->classroom = $classroom;
    }

    public function playVideo(int $postId): void
    {
        $post = Post::where('classroom_id', $this->classroom->id)->findOrFail($postId);

        abort_unless($post->hasVideo(), 404);

        $this->playingPostId = $postId;
        $this->playingVideoUrl = Storage::disk('r2')->temporaryUrl($post->video_path, now()->addHours(2));
    }

    public function closePlayer(): void
    {
        $this->playingPostId = null;
        $this->playingVideoUrl = null;
    }

    public function toggleComments(int $postId): void
    {
        if ($this->showingCommentsForPostId === $postId) {
            $this->showingCommentsForPostId = null;
        } else {
            $this->showingCommentsForPostId = $postId;
            $this->commentText = '';
        }
    }

    public function addComment(int $postId): void
    {
        Post::where('classroom_id', $this->classroom->id)->findOrFail($postId);

        $this->validate(['commentText' => ['required', 'string', 'max:1000']]);

        PostComment::create([
            'post_id' => $postId,
            'user_id' => auth()->id(),
            'body' => $this->commentText,
        ]);

        $this->commentText = '';
    }

    public function deleteComment(int $commentId): void
    {
        PostComment::where('user_id', auth()->id())->findOrFail($commentId)->delete();
    }

    public function markAttendance(int $postId): void
    {
        $post = Post::where('classroom_id', $this->classroom->id)->findOrFail($postId);

        abort_unless($post->isAttendanceOpen(), 403);

        PostAttendance::firstOrCreate([
            'post_id' => $postId,
            'user_id' => auth()->id(),
        ]);
    }

    public function render()
    {
        $posts = Post::where('classroom_id', $this->classroom->id)
            ->with(['teacher', 'comments' => fn ($q) => $q->with('author')])
            ->withCount('comments')
            ->latest()
            ->get();

        $attendedPostIds = PostAttendance::where('user_id', auth()->id())
            ->whereIn('post_id', $posts->pluck('id'))
            ->pluck('post_id')
            ->toArray();

        return view('livewire.student.classrooms.show', compact('posts', 'attendedPostIds'))
            ->layout('components.layouts.portal');
    }
}
