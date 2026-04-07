<?php

namespace App\Livewire\Admin\Teachers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;

    public User $user;

    public ?int $playingPostId = null;

    public ?string $playingVideoUrl = null;

    public function mount(User $user): void
    {
        abort_unless($user->isTeacher(), 404);
        $this->user = $user;
    }

    public function playVideo(int $postId): void
    {
        $post = Post::where('user_id', $this->user->id)->findOrFail($postId);

        abort_unless($post->hasVideo(), 404);

        $this->playingPostId = $postId;
        $this->playingVideoUrl = Storage::disk('r2')->temporaryUrl($post->video_path, now()->addHours(2));
    }

    public function closePlayer(): void
    {
        $this->playingPostId = null;
        $this->playingVideoUrl = null;
    }

    public function render()
    {
        $posts = $this->user->posts()
            ->with('classroom:id,name')
            ->withCount(['comments', 'attendances'])
            ->latest()
            ->paginate(15);

        return view('livewire.admin.teachers.posts', [
            'teacher' => $this->user,
            'posts' => $posts,
        ])->layout('components.layouts.admin');
    }
}
