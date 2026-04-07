<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Post;
use App\Models\PostAttendance;

class AttendancePrintController extends Controller
{
    public function __invoke(Classroom $classroom, Post $post)
    {
        abort_unless(
            auth()->user()->classrooms()->where('classrooms.id', $classroom->id)->exists(),
            403
        );

        abort_unless($post->classroom_id === $classroom->id, 404);

        $students = $classroom->students()
            ->with('department')
            ->orderBy('name')
            ->get();

        $attendedIds = PostAttendance::where('post_id', $post->id)
            ->pluck('user_id')
            ->toArray();

        return view('teacher.attendance-print', compact('classroom', 'post', 'students', 'attendedIds'));
    }
}
