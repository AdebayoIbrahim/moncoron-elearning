<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DawahTeacherController extends Controller
{
    public function index()
    {
        // Get all teachers
        $teachers = User::where('role', 'teacher')->get();

        return view('dawah-posts.teachers', compact('teachers'));
    }

    public function show($teacherId)
    {
        // Get a specific teacher
        $teacher = User::findOrFail($teacherId);

        // Get all posts by this teacher
        $posts = $teacher->dawahPosts;

        return view('dawah-posts.teacher-profile', compact('teacher', 'posts'));
    }
}
