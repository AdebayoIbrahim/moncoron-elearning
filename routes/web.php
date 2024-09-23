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
use App\Http\Controllers\ChatmessagesController;
use App\Http\Controllers\VideoChatController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\LessonAssessmentController;
use App\Http\Controllers\CourseAssessmentController;
use App\Http\Controllers\DawahController;
use App\Http\Controllers\DawahPostController;
use App\Http\Controllers\DawahTeacherController;
use App\Http\Controllers\LeaderBoardController;
use App\Http\Controllers\VideoCallController;
use App\Http\Controllers\LessonsAssessmentsNew;
use App\Events\StartVideoChat;
use App\Events\MessageSent;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\EditorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgoraController;
use App\Http\Controllers\NotificationController;
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

// Maincontroller-has-student-controllerstoo
Route::middleware(['auth'])->group(function () {
    // Student routes starts here
    // also addenrollment-type-check
    Route::middleware(['checkifenrolled', 'checkspecial'])->group(function () {
        Route::get('/courses/{courseid}', [MainController::class, 'showcourse'])->name('student.coursedesc');
        // navigate-to-acourse-lesson-for-students
        Route::get('/courses/{courseid}/lesson/{lessonid}', [MainController::class, 'showlessons'])->name('student.lessonsvie.show');
    });
    Route::get('/dashboard', [MainController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/courses', [MainController::class, 'courses'])->name('student.courses');

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



    // Display the editor
    Route::get('/editor', [EditorController::class, 'showEditor'])->name('editor.show');

    // Handle form submission
    Route::post('/editor/save', [EditorController::class, 'saveContent'])->name('editor.save');

    Route::post('/editor/upload', [EditorController::class, 'upload'])->name('editor.upload');



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

    // group-admin-ensure-admin-and-auth-middleware-forany-courseidroutestoo
    Route::middleware(['admin', 'checkspecial'])->group(function () {
        // Creating a new assessment
        Route::get('/admin/courses/{courseId}/lesson/{lessonId}/create-assessments', [LessonAssessmentController::class, 'create'])->name('assessments.create');

        // Storing the assessment
        Route::post('/admin/courses/{courseid}/lesson/{lessonid}/create-assessment', [AdminController::class, 'Addlessonassessment'])->name('assessment.create.add');

        // Viewing a specific assessment updated
        Route::get('/admin/courses/{courseid}/lesson/{lessonid}/assessment', [AdminController::class, 'PreviewAssessment'])->name('lesson_assessment.view');

        // updatibg-lesson-assessments-this-is-now
        //TODO:THIS IS ACTUALLY A PUT REQUEST IN POST METHOD...
        //TODO:FIXED PUT REQUEST ISSUES INLARAVEL, THE CONTROLLER ACTUALL
        //TODO:UPDATE USING UPDATE FUNCTION 
        Route::post('admin/courses/{courseid}/lesson/{lessonid}/assessmentupdate', [AdminController::class, 'UpdateLessonAssessment'])->name('lesson_asessment.update');

        // Deleting an assessment
        Route::delete('/admin/courses/{courseId}/lessons/{lessonId}/assessments/{id}', [LessonAssessmentController::class, 'destroy'])->name('lesson_assessments.destroy');

        Route::get('courses/{courseId}/lessons/{lessonId}/assessment', [LessonController::class, 'showAssessment'])->name('lessons.assessment');

        // Submitting the assessment
        Route::post('courses/{courseId}/lessons/{lessonId}/assessment/submit', [LessonAssessmentController::class, 'submitAssessment'])->name('student.assessments.submit');
        // getlessons-in-acourse
        Route::get('courses/{course_id}/lessons/{id}', [LessonController::class, 'show'])->name('lessons.show');

        Route::post('courses/{course_id}/lessons/{id}/upload', [LessonController::class, 'uploadStudentMedia'])->name('lessons.uploadStudentMedia');

        Route::post('courses/{course_id}/lessons/{id}/complete', [LessonController::class, 'completeLesson'])->name('lessons.complete');

        Route::get('/admin/courses/{course_id}/lessons/{lesson_id}/assessments', [LessonAssessmentController::class, 'viewAssessmentsByLesson'])->name('lessons.viewAssessments');

        // Route to view assessments by course

        Route::get('admin/courses/{courseId}/lessons/{lessonId}/assessments/{id}', [LessonAssessmentController::class, 'show'])->name('lesson_assessments.show');

        // Route to delete assessment
        Route::delete('admin/courses/{courseId}/lessons/{lessonId}/assessments/{id}', [LessonAssessmentController::class, 'destroy'])->name('lesson_assessments.destroy');
    });



    // Admin-route-for-non-coursid-middleware-rtracks
    Route::middleware(['admin'])->group(function () {

        Route::get('admin/manage-assessments', [LessonAssessmentController::class, 'manageAssessments'])->name('assessments.manage');
        Route::post('admin/publish-assessment/{id}', [LessonAssessmentController::class, 'publishAssessment'])->name('assessments.publish');
        Route::post('admin/unpublish-assessment/{id}', [LessonAssessmentController::class, 'unpublishAssessment'])->name('assessments.unpublish');
        Route::post('admin/delete-assessment/{id}', [LessonAssessmentController::class, 'deleteAssessment'])->name('assessments.delete');

        Route::post('admin/assessments/delete/{id}', [LessonAssessmentController::class, 'deleteAssessmentWithConfirmation'])->name('assessments.delete');
        // Admin Routes for Managing Assessments
        Route::get('/admin/courses/{course}/assessments', [CourseAssessmentController::class, 'index'])->name('course_assessments.index');

        Route::get('/admin/courses/{course}/assessments/create', [CourseAssessmentController::class, 'create'])->name('course_assessments.create');

        Route::post('/admin/courses/{course}/assessments', [CourseAssessmentController::class, 'store'])->name('course_assessments.store');

        Route::get('/admin/courses/{course}/assessments/{assessment}', [CourseAssessmentController::class, 'show'])->name('course_assessments.show');

        Route::get('/admin/courses/{course}/assessments/{assessment}/edit', [CourseAssessmentController::class, 'edit'])->name('course_assessments.edit');

        Route::put('/admin/courses/{course}/assessments/{assessment}', [CourseAssessmentController::class, 'update'])->name('course_assessments.update');

        Route::delete('/admin/courses/{course}/assessments/{assessment}', [CourseAssessmentController::class, 'destroy'])->name('course_assessments.delete');

        // Agora-video-chat-only-premium-users
        Route::post('/admin/video_token/generate', [AgoraController::class, 'generateToken'])->name('videochat.start')->middleware('premiumuser');



        // Admin routes
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/courses', [AdminController::class, 'courses'])->name('admin.courses');
        // special-course
        Route::get('/admin/specialcourses', [AdminController::class, 'getPremiumcourses'])->name('admin.premiumcourses')->middleware('premiumuser');

        Route::post('/admin/courses/register', [AdminController::class, 'registerCourse'])->name('admin.courses.register');
        // add-lessons-to-specific-course
        Route::post('/admin/course/{courseid}/lessons', [AdminController::class, 'addcourseLessons'])->name('admin.courses.lessons.create');
        Route::get('/admin/courses/{courseid}', [AdminController::class, 'fetchCourse'])->name('admin.courses.fetch');
        // fetchcourse-details-lessons`
        Route::get('/admin/courses/{courseid}/lesson/{lessonid}', [AdminController::class, 'fetchlesson'])->name('admin.course.lessonview');

        // Dynamic-new-chat-routes
        // Fetch messages for a specific lesson in a specific course
        Route::get('/admin/courses/{courseId}/lesson/{lessonId}/messages', [ChatmessagesController::class, 'fetchMessages'])->name('chat.fetch');


        // Send a message to a specific lesson in a specific course
        Route::post('/admin/courses/{courseId}/lesson/{lessonId}/message', [ChatmessagesController::class, 'sendMessage'])->name('chat.send');

        Route::put('/admin/courses/update', [AdminController::class, 'updateCourse'])->name('admin.courses.update');
        Route::get('/admin/courses/delete/{id}', [AdminController::class, 'deleteCourse'])->name('admin.courses.delete');
        Route::post('/admin/courses/lesson', [AdminController::class, 'addLesson'])->name('admin.courses.lesson');
        Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::get('/admin/students', [AdminController::class, 'students'])->name('admin.students');
        Route::post('/admin/students/register', [AdminController::class, 'registerStudent'])->name('admin.students.register');
        Route::get('/admin/teachers', [AdminController::class, 'teachers'])->name('admin.teachers');
        Route::post('/admin/teachers/register', [AdminController::class, 'registerTeacher'])->name('admin.teachers.register');
        Route::get('/admin/lecturers', [AdminController::class, 'lecturers'])->name('admin.lecturers');
        Route::post('/admin/lecturers/register', [AdminController::class, 'registerLecturer'])->name('admin.lecturers.register');
        Route::get('admin/courses/{courseId}/assessments/{assessmentId}/delete', [AdminController::class, 'deleteAssessment'])
            ->name('admin.courses.assessments.delete');

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
        //ends
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
    });



    Route::post('/courses/{courseId}/lessons/{lessonId}/assessments/submit', [LessonAssessmentController::class, 'submitAssessment'])->name('student.assessments.submit');

    Route::get('courses/{courseId}/lessons/{lessonId}/assessments/take', [LessonAssessmentController::class, 'showStudentAssessment'])->name('student.assessments.take');
    Route::post('courses/{courseId}/lessons/{lessonId}/assessments/submit', [LessonAssessmentController::class, 'submitStudentAssessment'])->name('student.assessments.submit');


    Route::get('courses/{courseId}/lessons/{lessonId}/assessments/result', [LessonAssessmentController::class, 'showAssessmentResult'])->name('student.assessments.result');

    Route::get('/courses/{courseId}/lessons/{lessonId}/assessments/{id}/edit', [LessonAssessmentController::class, 'edit'])->name('lesson_assessments.edit');


    Route::get('/courses/{courseId}/lessons/{lessonId}/chat', [ChatController::class, 'index'])->name('student.chat.index');

    Route::get('/admin/student-grades', [AdminController::class, 'studentGrades'])->name('admin.student_grades');
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    // auth-user-mark-all-as-read
    Route::post('/notification/markallasread', [NotificationController::class, 'markAllasRead'])->name('notifications.markall');
    // Student Routes for Course Assessment
    Route::get('/courses/{course}/assessments', [CourseAssessmentController::class, 'index'])->name('student.courses.assessments');
    Route::get('/courses/{course}/assessments/{assessment}', [CourseAssessmentController::class, 'show'])->name('student.courses.assessments.show');
    Route::get('/courses/{course}/assessments/{assessment}/attempt', [CourseAssessmentController::class, 'attempt'])->name('student.courses.assessments.attempt');
    Route::post('/courses/{course}/assessments/{assessment}/submit', [CourseAssessmentController::class, 'submit'])->name('student.courses.assessments.submit');

    // Admin Routes for Course Assessment Result
    Route::get('assessments/{assessment}/result/{submission}', [CourseAssessmentController::class, 'showResult'])->name('admin.courses.assessments.result');

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
Route::get('/token', function () {
    return csrf_token();
});
// getphp-ifo-testing-ini
Route::get('/info', function () {
    return phpinfo();
});
