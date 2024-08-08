<?php

namespace App\Http\Controllers;

use App\Models\Dawah;
use App\Models\DawahPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function createDawahPostForm($dawahId)
    {
        $dawah = Dawah::findOrFail($dawahId);
        return view('teacher.create-dawah-post', compact('dawah'));
    }

    public function createDawahPost(Request $request)
    {
        $request->validate([
            'dawah_id' => 'required|exists:dawahs,id',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'media' => 'nullable|file|mimes:mp4,mp3,jpg,jpeg,png,gif',
        ]);

        $mediaPath = null;
        $mediaType = 'text';

        if ($request->hasFile('media')) {
            $media = $request->file('media');
            $mediaPath = $media->store('dawah_posts', 'public');

            $mimeType = $media->getMimeType();
            if (strstr($mimeType, 'video/')) {
                $mediaType = 'video';
            } elseif (strstr($mimeType, 'audio/')) {
                $mediaType = 'audio';
            } elseif (strstr($mimeType, 'image/')) {
                $mediaType = 'image';
            }
        }

        $dawahPost = new DawahPost([
            'dawah_id' => $request->dawah_id,
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
        ]);

        $dawahPost->save();

        return redirect()->route('teacher.dawah-posts', $request->dawah_id)->with('success', 'Post created successfully.');
    }
}
