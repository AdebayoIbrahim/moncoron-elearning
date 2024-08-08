<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourseLesson;
use App\Models\UserCourseLesson;
use App\Models\StudentMedia;
use App\Notifications\StudentUploadNotification;
use Illuminate\Support\Facades\Storage;
use Auth;

class StudentMediaController extends Controller
{
    public function show($id)
    {
        $lesson = CourseLesson::findOrFail($id);
        $studentMedia = StudentMedia::where('lesson_id', $id)->get();

        return view('lessons.show', compact('lesson', 'studentMedia'));
    }

    public function upload(Request $request, $id)
    {
        $lesson = CourseLesson::findOrFail($id);
        $studentMedia = new StudentMedia();
        $studentMedia->user_id = Auth::id();
        $studentMedia->lesson_id = $id;

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('student_media', 'public');
            $studentMedia->addMedia(storage_path('app/public/' . $path))->toMediaCollection('student_media');
        }

        if ($request->has('audio_data')) {
            $audioData = $request->input('audio_data');
            list($type, $audioData) = explode(';', $audioData);
            list(, $audioData) = explode(',', $audioData);
            $audioData = base64_decode($audioData);
            $fileName = uniqid() . '.webm';
            $filePath = storage_path('app/public/student_media/' . $fileName);
            file_put_contents($filePath, $audioData);
            $studentMedia->addMedia($filePath)->toMediaCollection('student_media');
        }

        $studentMedia->save();

        // Notify the teacher
        $teacher = $lesson->course->teacher; // Assuming the course has a teacher relationship
        $teacher->notify(new StudentUploadNotification($studentMedia));

        return redirect()->back()->with('success', 'Media uploaded successfully!');
    }
}
