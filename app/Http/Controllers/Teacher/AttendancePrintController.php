<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Video;
use App\Models\VideoAttendance;

class AttendancePrintController extends Controller
{
    public function __invoke(Classroom $classroom, Video $video)
    {
        abort_unless(
            auth()->user()->classrooms()->where('classrooms.id', $classroom->id)->exists(),
            403
        );

        abort_unless($video->classroom_id === $classroom->id, 404);

        $students = $classroom->students()
            ->with('department')
            ->orderBy('name')
            ->get();

        $attendedIds = VideoAttendance::where('video_id', $video->id)
            ->pluck('user_id')
            ->toArray();

        return view('teacher.attendance-print', compact('classroom', 'video', 'students', 'attendedIds'));
    }
}
