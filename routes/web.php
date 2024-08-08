<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ChunkUploadController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\StudentAssessmentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\VideoChatController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LessonAssessmentController;
use App\Http\Controllers\DawahController;
use App\Http\Controllers\DawahPostController;
use App\Http\Controllers\DawahTeacherController;
use App\Http\Controllers\LeaderBoardController;
use App\Http\Controllers\VideoCallController;
use App\Events\StartVideoChat;
use App\Events\MessageSent;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Broadcast;



// CKEditor upload route
Route::post('/upload-image', [CKEditorController::class, 'uploadImage'])->name('ckeditor.upload');

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

Route::get('/notifications', function () {
    return view('notifications.index');
})->middleware('auth');

Route::middleware(['auth'])->group(function () {
    // Student routes
    Route::get('/dashboard', [MainController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/courses', [MainController::class, 'courses'])->name('student.courses');
    Route::get('/courses/{id}', [MainController::class, 'show'])->name('student.courses.show');
    Route::get('/courses/view/{id}', [MainController::class, 'showcourse'])->name('student.coursedesc');
    Route::post('/updateprogress', [MainController::class, 'updateProgress'])->name('updateprogress');
    Route::post('/pay', [MainController::class, 'handlePayment'])->name('pay');
    Route::get('/profile', [MainController::class, 'profile'])->name('student.profile');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/signal', [ChatController::class, 'sendCallSignal'])->name('chat.signal');

    Route::get('/courses/{courseId}/lessons/{lessonId}/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/courses/{courseId}/lessons/{lessonId}/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');

    Route::post('/courses/{courseId}/lessons/{lessonId}/delete', [ChatController::class, 'deleteMessage'])->name('chat.delete');


   
// Route to show chat interface
Route::get('courses/{course_id}/lessons/{lesson_id}/chat', [ChatController::class, 'index'])->name('chat.index');

// Route to send a chat message
Route::post('courses/{course_id}/lessons/{lesson_id}/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');

// Route to send call signals (if needed)
Route::post('courses/{course_id}/lessons/{lesson_id}/chat/sendCallSignal', [ChatController::class, 'sendCallSignal'])->name('chat.sendCallSignal');




Route::get('/send-broadcast', function () {
    $user = User::find(2); // Assuming a user with ID 1 exists
    $message = new Message(['content' => 'Hello World']);
    broadcast(new MessageSent($user, $message));
    return 'Broadcast sent';
});

Route::get('/test-broadcast', function () {
    return view('test-broadcast');
});




    // Video chat routes

Route::get('/video-chat', [VideoChatController::class, 'index'])->name('video-chat.index');
Route::post('/video-chat/call', [VideoChatController::class, 'callUser'])->name('video-chat.call');
Route::post('/video-chat/accept', [VideoChatController::class, 'acceptCall'])->name('video-chat.accept');
Route::post('/video-chat/signal', [VideoChatController::class, 'sendSignal'])->name('video-chat.signal');


    // Lesson routes
    //Route::get('/chat/courses/{course_id}/lessons/{lesson_id}', [ChatController::class, 'lessonChat'])->name('chat.lesson');
   // Route::post('/chat/courses/{course_id}/lessons/{lesson_id}/send', [ChatController::class, 'sendLessonMessage'])->name('chat.lesson.send');
   Route::get('/chat/courses/{course_id}/lessons/{lesson_id}', [ChatController::class, 'index'])->name('chat.index');
   Route::post('/chat/courses/{course_id}/lessons/{lesson_id}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
   Route::post('/chat/courses/{course_id}/lessons/{lesson_id}/call', [ChatController::class, 'sendCallSignal'])->name('chat.call');
    
    // Lesson Assessment routes
    Route::get('courses/{course_id}/lessons/{lesson_id}/assessment', [LessonController::class, 'showAssessment'])->name('lessons.assessment');
    Route::post('courses/{course_id}/lessons/{lesson_id}/assessment', [LessonController::class, 'submitAssessment'])->name('lessons.submitAssessment');
    Route::post('/courses/{course_id}/lessons/{id}/complete', [LessonController::class, 'completeLesson'])->name('lessons.complete');
    
    Route::prefix('courses/{courseId}')->group(function () {
        Route::get('lesson_assessments', [LessonAssessmentController::class, 'index'])->name('lesson_assessments.index');
        Route::get('lesson_assessments/create/{lessonId}', [LessonAssessmentController::class, 'create'])->name('lesson_assessments.create');
        Route::post('lesson_assessments/store/{lessonId}', [LessonAssessmentController::class, 'store'])->name('lesson_assessments.store');
        Route::get('lesson_assessments/{lessonId}', [LessonAssessmentController::class, 'show'])->name('lesson_assessments.show');
        Route::delete('lesson_assessments/{id}', [LessonAssessmentController::class, 'destroy'])->name('lesson_assessments.destroy');
    });

    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');

    // Student course assessments routes
    Route::get('/courses/{course}/assessments', [MainController::class, 'assessments'])->name('student.courses.assessments');
    Route::get('/courses/{course}/assessments/{assessment}', [MainController::class, 'showAssessment'])->name('student.courses.assessments.show');
    Route::get('/courses/{course}/assessments/{assessment}/attempt', [MainController::class, 'attemptAssessment'])->name('student.courses.assessments.attempt');
    Route::post('/courses/{course}/assessments/{assessment}/submit', [MainController::class, 'submitAssessment'])->name('student.courses.assessments.submit');
    Route::get('assessments/{assessment}/result/{submission}', [AdminController::class, 'showResult'])->name('admin.courses.assessments.result');
    Route::get('assessments/{assessment}/leaderboard', [AdminController::class, 'leaderboard'])->name('admin.courses.assessments.leaderboard');
    
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

    Route::get('/admin/assign-course', [AdminController::class, 'showAssignCourseForm'])->name('admin.assign-course');
    Route::post('/admin/assign-course', [AdminController::class, 'assignCourse'])->name('admin.assign-course.post');
    Route::post('/admin/unassign-course', [AdminController::class, 'unassignCourse'])->name('admin.unassign-course');
    Route::get('/admin/users', [AdminController::class, 'manageUsers'])->name('admin.users');
    Route::post('/admin/users/suspend/{id}', [AdminController::class, 'suspendUser'])->name('admin.users.suspend');
    Route::post('/admin/users/unsuspend/{id}', [AdminController::class, 'unsuspendUser'])->name('admin.users.unsuspend');
    Route::delete('/admin/users/delete/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    Route::get('/admin/users', [App\Http\Controllers\AdminController::class, 'manageUsers'])->name('admin.users');
    Route::post('/admin/users/suspend/{id}', [App\Http\Controllers\AdminController::class, 'suspendUser'])->name('admin.users.suspend');
    Route::post('/admin/users/unsuspend/{id}', [App\Http\Controllers\AdminController::class, 'unsuspendUser'])->name('admin.users.unsuspend');
    Route::delete('/admin/users/delete/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.users.delete');



    Route::get('/admin/dawah', [DawahController::class, 'index'])->name('admin.dawah.index');
    Route::get('/admin/dawah/create', [DawahController::class, 'create'])->name('admin.dawah.create');
    Route::post('/admin/dawah', [DawahController::class, 'store'])->name('admin.dawah.store');
    Route::get('/admin/dawah/{dawahId}/edit', [DawahController::class, 'edit'])->name('admin.dawah.edit');
    Route::put('/admin/dawah/{dawahId}', [DawahController::class, 'update'])->name('admin.dawah.update');
    Route::delete('/admin/dawah/{dawahId}', [DawahController::class, 'destroy'])->name('admin.dawah.destroy');
    
    Route::get('/admin/dawah/{dawahId}/assign-teacher', [DawahController::class, 'assignTeacherForm'])->name('admin.dawah.assign-teacher-form');
    Route::post('/admin/dawah/{dawahId}/assign-teacher', [DawahController::class, 'assignTeacher'])->name('admin.dawah.assign-teacher');
    
    Route::get('/admin/dawah/{dawahId}/lessons', [DawahController::class, 'viewLessons'])->name('admin.dawah.view-lessons');
    Route::get('/admin/dawah/{dawahId}/create-lesson', [DawahController::class, 'createLessonForm'])->name('admin.dawah.create-lesson');
    Route::post('/admin/dawah/{dawahId}/lessons', [DawahController::class, 'storeLesson'])->name('admin.dawah.store-lesson');
    Route::get('/admin/dawah/{dawahId}/edit-lesson/{lessonId}', [DawahController::class, 'editLessonForm'])->name('admin.dawah.edit-lesson');
    Route::put('/admin/dawah/{dawahId}/lessons/{lessonId}', [DawahController::class, 'updateLesson'])->name('admin.dawah.update-lesson');
    Route::delete('/admin/dawah/{dawahId}/lessons/{lessonId}', [DawahController::class, 'deleteLesson'])->name('admin.dawah.delete-lesson');
    
    Route::get('/admin/dawah-posts', [DawahPostController::class, 'index'])->name('admin.dawah-posts.index');
Route::get('/admin/dawah-posts/create', [DawahPostController::class, 'create'])->name('admin.dawah-posts.create');
Route::post('/admin/dawah-posts/store', [DawahPostController::class, 'store'])->name('admin.dawah-posts.store');
Route::get('/admin/dawah-posts/{id}', [DawahPostController::class, 'show'])->name('admin.dawah-posts.show');
Route::get('/admin/dawah-posts/{id}/edit', [DawahPostController::class, 'edit'])->name('admin.dawah-posts.edit');
Route::put('/admin/dawah-posts/{id}', [DawahPostController::class, 'update'])->name('admin.dawah-posts.update');
Route::delete('/admin/dawah-posts/{id}', [DawahPostController::class, 'destroy'])->name('admin.dawah-posts.destroy');
Route::get('/admin/dawah-posts/teachers', [DawahPostController::class, 'teachers'])->name('admin.dawah-posts.teachers');
Route::get('/admin/dawah-posts/teacher-profile/{id}', [DawahPostController::class, 'teacherProfile'])->name('admin.dawah-posts.teacher-profile');

Route::get('/admin/dawah-posts/teachers', [DawahPostController::class, 'listTeachers'])->name('admin.dawah-posts.teachers');
Route::get('/admin/dawah-posts/teacher/{id}', [DawahPostController::class, 'teacherProfile'])->name('admin.dawah-posts.teacher-profile');

Route::get('/admin/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

// Routes for Dawah posts and teachers



    

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

// Chunk upload routes
Route::post('upload-chunk', [ChunkUploadController::class, 'uploadChunk'])->name('upload.chunk');
Route::post('finish-upload', [ChunkUploadController::class, 'finishUpload'])->name('upload.finish');
