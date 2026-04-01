<?php

use App\Livewire\Admin\Classrooms\Create as ClassroomsCreate;
use App\Livewire\Admin\Classrooms\Edit as ClassroomsEdit;
use App\Livewire\Admin\Classrooms\Index as ClassroomsIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Departments\Index as DepartmentsIndex;
use App\Livewire\Admin\Students\Create as StudentsCreate;
use App\Livewire\Admin\Students\Edit as StudentsEdit;
use App\Livewire\Admin\Students\Index as StudentsIndex;
use App\Livewire\Admin\Teachers\Create as TeachersCreate;
use App\Livewire\Admin\Teachers\Edit as TeachersEdit;
use App\Livewire\Admin\Teachers\Index as TeachersIndex;
use App\Livewire\Guest\Login;
use App\Livewire\Student\Classrooms\Show as StudentClassroomShow;
use App\Livewire\Student\Dashboard as StudentDashboard;
use App\Livewire\Teacher\Classrooms\Show as TeacherClassroomShow;
use App\Livewire\Teacher\Dashboard as TeacherDashboard;
use App\Livewire\Welcome;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', Welcome::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

    Route::get('/students', StudentsIndex::class)->name('students.index');
    Route::get('/students/create', StudentsCreate::class)->name('students.create');
    Route::get('/students/{user}/edit', StudentsEdit::class)->name('students.edit');

    Route::get('/teachers', TeachersIndex::class)->name('teachers.index');
    Route::get('/teachers/create', TeachersCreate::class)->name('teachers.create');
    Route::get('/teachers/{user}/edit', TeachersEdit::class)->name('teachers.edit');

    Route::get('/classrooms', ClassroomsIndex::class)->name('classrooms.index');
    Route::get('/classrooms/create', ClassroomsCreate::class)->name('classrooms.create');
    Route::get('/classrooms/{classroom}/edit', ClassroomsEdit::class)->name('classrooms.edit');

    Route::get('/departments', DepartmentsIndex::class)->name('departments.index');
});

// Teacher routes
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', TeacherDashboard::class)->name('dashboard');
    Route::get('/classrooms/{classroom}', TeacherClassroomShow::class)->name('classrooms.show');
});

// Student routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', StudentDashboard::class)->name('dashboard');
    Route::get('/classrooms/{classroom}', StudentClassroomShow::class)->name('classrooms.show');
});
