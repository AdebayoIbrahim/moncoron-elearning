<?php

namespace App\Http\Controllers;

use App\Models\DawahPost;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

class DawahPostController extends Controller
{
    public function index()
    {
        $posts = DawahPost::all();
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('dawah-posts.index', compact('posts', 'routeNamePart'));
    }

    public function create()
    {
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('dawah-posts.create', compact('routeNamePart'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'video' => 'nullable|file|mimes:mp4|max:20480',
            'audio' => 'nullable|file|mimes:mp3|max:20480',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('dawah-posts/images', 'public');
            $data['image'] = $path;
        }

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('dawah-posts/videos', 'public');
            $data['video'] = $path;
        }

        if ($request->hasFile('audio')) {
            $path = $request->file('audio')->store('dawah-posts/audios', 'public');
            $data['audio'] = $path;
        }

        $data['user_id'] = auth()->id();
        $data['type'] = $this->getAttachmentType($data);

        DawahPost::create($data);

        return redirect()->route('admin.dawah-posts.index')->with('success', 'Post created successfully.');
    }

    public function show($id)
    {
        $post = DawahPost::findOrFail($id);
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('dawah-posts.show', compact('post', 'routeNamePart'));
    }

    public function edit($id)
    {
        $post = DawahPost::findOrFail($id);
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('dawah-posts.edit', compact('post', 'routeNamePart'));
    }

    public function update(Request $request, $id)
    {
        $post = DawahPost::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'video' => 'nullable|file|mimes:mp4|max:20480',
            'audio' => 'nullable|file|mimes:mp3|max:20480',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $path = $request->file('image')->store('dawah-posts/images', 'public');
            $data['image'] = $path;
        }

        if ($request->hasFile('video')) {
            if ($post->video) {
                Storage::disk('public')->delete($post->video);
            }
            $path = $request->file('video')->store('dawah-posts/videos', 'public');
            $data['video'] = $path;
        }

        if ($request->hasFile('audio')) {
            if ($post->audio) {
                Storage::disk('public')->delete($post->audio);
            }
            $path = $request->file('audio')->store('dawah-posts/audios', 'public');
            $data['audio'] = $path;
        }

        $post->update($data);

        return redirect()->route('admin.dawah-posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy($id)
    {
        $post = DawahPost::findOrFail($id);
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        if ($post->video) {
            Storage::disk('public')->delete($post->video);
        }
        if ($post->audio) {
            Storage::disk('public')->delete($post->audio);
        }
        $post->delete();

        return redirect()->route('admin.dawah-posts.index')->with('success', 'Post deleted successfully.');
    }

    private function getAttachmentType($data)
    {
        if (!empty($data['video'])) {
            return 'video';
        } elseif (!empty($data['audio'])) {
            return 'audio';
        } elseif (!empty($data['image'])) {
            return 'image';
        } else {
            return 'text';
        }
    }

    public function listTeachers()
    {
        $teachers = User::where('role', 'teacher')->get();
        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('dawah-posts.teachers', compact('teachers', 'routeNamePart'));
    }

    public function teacherProfile($id, Request $request)
    {
        $teacher = User::findOrFail($id);
        $mediaType = $request->query('media_type');

        if ($mediaType) {
            $posts = DawahPost::where('user_id', $id)->where('type', $mediaType)->get();
        } else {
            $posts = DawahPost::where('user_id', $id)->get();
        }

        $routeName = Route::currentRouteName();
        $routeNamePart = ucfirst(last(explode('.', $routeName)));

        return view('dawah-posts.teacher-profile', compact('teacher', 'posts', 'routeNamePart', 'mediaType'));
    }
}
