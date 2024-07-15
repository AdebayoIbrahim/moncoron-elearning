<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/forgot', function () {
    return view('forgot');
});

Route::get('/verify', function () {
    return view('verify');
});

Route::get('/changepassword', function () {
    return view('changepassword');
});

Route::get('/passwordchangesuccess', function () {
    return view('passwordchangesuccess');
});

Route::middleware(['auth'])->group(function () {
    // Student routes
    Route::get('/dashboard', [MainController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/courses', [MainController::class, 'courses'])->name('student.courses');
    Route::get('/courses/{id}', [MainController::class, 'show'])->name('student.courses.show');
    Route::get('/courses/view/{id}', [MainController::class, 'showcourse'])->name('student.coursedesc');
    Route::post('/updateprogress', [MainController::class, 'updateProgress'])->name('updateprogress');
    Route::post('/pay', [MainController::class, 'handlePayment'])->name('pay');
    Route::get('/profile', [MainController::class, 'profile'])->name('student.profile');

    // Student course assessments routes
    Route::get('/courses/{course}/assessments', [MainController::class, 'assessments'])->name('student.courses.assessments');
    Route::get('/courses/{course}/assessments/{assessment}', [MainController::class, 'showAssessment'])->name('student.courses.assessments.show');
    Route::get('/courses/{course}/assessments/{assessment}/attempt', [MainController::class, 'attemptAssessment'])->name('student.courses.assessments.attempt');
    Route::post('/courses/{course}/assessments/{assessment}/submit', [MainController::class, 'submitAssessment'])->name('student.courses.assessments.submit');
    Route::get('assessments/{assessment}/result/{submission}', [AdminController::class, 'showResult'])->name('admin.courses.assessments.result');
    Route::get('assessments/{assessment}/leaderboard', [AdminController::class, 'leaderboard'])->name('admin.courses.assessments.leaderboard');
    
    //student leaderboard route
    Route::get('/leaderboard', [MainController::class, 'leaderboard'])->name('student.leaderboard');

    // Admin routes
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/courses', [AdminController::class, 'courses'])->name('admin.courses');
    Route::post('/admin/courses/register', [AdminController::class, 'registerCourse'])->name('admin.courses.register');
    Route::get('/admin/courses/{id}', [AdminController::class, 'fetchCourse'])->name('admin.courses.fetch');
    Route::put('/admin/courses/update', [AdminController::class, 'updateCourse'])->name('admin.courses.update');
    Route::get('/admin/courses/view/{id}', [AdminController::class, 'viewCourse'])->name('admin.courseview');
    Route::get('/admin/courses/delete/{id}', [AdminController::class, 'deleteCourse'])->name('admin.courses.delete');
    Route::post('/admin/courses/lesson', [AdminController::class, 'addLesson'])->name('admin.courses.lesson');
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/admin/students', [AdminController::class, 'students'])->name('admin.students');
    Route::post('/admin/students/register', [AdminController::class, 'registerStudent'])->name('admin.students.register');
    Route::get('/admin/teachers', [AdminController::class, 'teachers'])->name('admin.teachers');
    Route::post('/admin/teachers/register', [AdminController::class, 'registerTeacher'])->name('admin.teachers.register');
    Route::get('/admin/lecturers', [AdminController::class, 'lecturers'])->name('admin.lecturers');
    Route::post('/admin/lecturers/register', [AdminController::class, 'registerLecturer'])->name('admin.lecturers.register');

    // Admin and Teacher course assessments routes
    Route::prefix('admin/courses/{course}')->group(function () {
        Route::get('assessments', [AdminController::class, 'assessments'])->name('admin.courses.assessments');
        Route::get('assessments/create', [AdminController::class, 'createAssessment'])->name('admin.courses.assessments.create');
        Route::post('assessments', [AdminController::class, 'storeAssessment'])->name('admin.courses.assessments.store');
        Route::get('assessments/{assessment}', [AdminController::class, 'showAssessment'])->name('admin.courses.assessments.show');
        Route::get('assessments/{assessment}/edit', [AdminController::class, 'editAssessment'])->name('admin.courses.assessments.edit');
        Route::put('assessments/{assessment}', [AdminController::class, 'updateAssessment'])->name('admin.courses.assessments.update');
        Route::delete('assessments/{assessment}', [AdminController::class, 'deleteAssessment'])->name('admin.courses.assessments.destroy');
    });

    // Teacher routes
    Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    Route::prefix('teacher/courses/{course}')->group(function () {
        Route::get('assessments', [AdminController::class, 'assessments'])->name('teacher.courses.assessments');
        Route::get('assessments/create', [AdminController::class, 'createAssessment'])->name('teacher.courses.assessments.create');
        Route::post('assessments', [AdminController::class, 'storeAssessment'])->name('teacher.courses.assessments.store');
        Route::get('assessments/{assessment}', [AdminController::class, 'showAssessment'])->name('teacher.courses.assessments.show');
        Route::get('assessments/{assessment}/edit', [AdminController::class, 'editAssessment'])->name('teacher.courses.assessments.edit');
        Route::put('assessments/{assessment}', [AdminController::class, 'updateAssessment'])->name('teacher.courses.assessments.update');
        Route::delete('assessments/{assessment}', [AdminController::class, 'deleteAssessment'])->name('teacher.courses.assessments.destroy');
    });

    // Lecturer routes
    Route::get('/lecturer/dashboard', [LecturerController::class, 'dashboard'])->name('lecturer.dashboard');
});

// User Login, Password Reset, Verification
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('forgot', [AuthController::class, 'forgot'])->name('forgot');
Route::post('verify', [AuthController::class, 'verify'])->name('verify');
Route::post('changepassword', [AuthController::class, 'changepassword'])->name('changepassword');
Route::post('register', [AuthController::class, 'register'])->name('register');
