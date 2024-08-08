<?php

namespace App\Http\Controllers;

use App\Models\Dawah;
use App\Models\DawahLesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DawahController extends Controller
{
    public function index()
    {
        $dawahs = Dawah::all();
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.dawah.index', compact('dawahs', 'routeNamePart'));
    }

    public function create()
    {
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.dawah.create', compact('routeNamePart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:dawahs',
            'description' => 'nullable|string',
            'type' => 'required|string',
            'age_group' => 'required|integer',
            'status' => 'required|integer',
        ]);

        Dawah::create($request->all());

        return redirect()->route('admin.dawah.index')->with('success', 'Dawah course created successfully.');
    }

    public function edit($dawahId)
    {
        $dawah = Dawah::findOrFail($dawahId);
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.dawah.edit', compact('dawah', 'routeNamePart'));
    }

    public function update(Request $request, $dawahId)
    {
        $dawah = Dawah::findOrFail($dawahId);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:dawahs,slug,' . $dawah->id,
            'description' => 'nullable|string',
            'type' => 'required|string',
            'age_group' => 'required|integer',
            'status' => 'required|integer',
        ]);

        $dawah->update($request->all());

        return redirect()->route('admin.dawah.index')->with('success', 'Dawah course updated successfully.');
    }

    public function destroy($dawahId)
    {
        $dawah = Dawah::findOrFail($dawahId);
        $dawah->delete();

        return redirect()->route('admin.dawah.index')->with('success', 'Dawah course deleted successfully.');
    }

    public function assignTeacherForm($dawahId)
    {
        $dawah = Dawah::findOrFail($dawahId);
        $teachers = User::where('role', 'teacher')->get();
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.dawah.assign-teacher', compact('dawah', 'teachers', 'routeNamePart'));
    }

    public function assignTeacher(Request $request, $dawahId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $dawah = Dawah::findOrFail($dawahId);
        $dawah->teachers()->attach($request->user_id, ['is_teacher' => 1]);

        return redirect()->route('admin.dawah.index')->with('success', 'Teacher assigned successfully.');
    }

    public function createLessonForm($dawahId)
    {
        $dawah = Dawah::findOrFail($dawahId);
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.dawah.create-lesson', compact('dawah', 'routeNamePart'));
    }

    public function storeLesson(Request $request, $dawahId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'resource_type' => 'required|string',
            'source' => 'required|string',
            'resource_url' => 'nullable|string',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $dawah = Dawah::findOrFail($dawahId);
        $lesson = new DawahLesson($request->all());
        $dawah->lessons()->save($lesson);

        return redirect()->route('admin.dawah.view-lessons', $dawahId)->with('success', 'Lesson added successfully.');
    }

    public function viewLessons($dawahId)
    {
        $dawah = Dawah::findOrFail($dawahId);
        $lessons = $dawah->lessons;
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.dawah.view-lessons', compact('dawah', 'lessons', 'routeNamePart'));
    }

    public function editLessonForm($dawahId, $lessonId)
    {
        $dawah = Dawah::findOrFail($dawahId);
        $lesson = DawahLesson::findOrFail($lessonId);
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('admin.dawah.edit-lesson', compact('dawah', 'lesson', 'routeNamePart'));
    }

    public function updateLesson(Request $request, $dawahId, $lessonId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'resource_type' => 'required|string',
            'source' => 'required|string',
            'resource_url' => 'nullable|string',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $lesson = DawahLesson::findOrFail($lessonId);
        $lesson->update($request->all());

        return redirect()->route('admin.dawah.view-lessons', $dawahId)->with('success', 'Lesson updated successfully.');
    }

    public function deleteLesson($dawahId, $lessonId)
    {
        $lesson = DawahLesson::findOrFail($lessonId);
        $lesson->delete();

        return redirect()->route('admin.dawah.view-lessons', $dawahId)->with('success', 'Lesson deleted successfully.');
    }
}
