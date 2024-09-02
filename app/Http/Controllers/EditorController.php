<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EditorContent;
use Illuminate\Support\Facades\Storage;
use Purifier;

class EditorController extends Controller
{
    /**
     * Display the editor view.
     */
    public function showEditor()
    {
        // Retrieve the latest content if any exists
        $content = EditorContent::latest()->first();

        return view('editor', ['existingContent' => $content ? $content->content : '']);
    }

    /**
     * Handle the form submission to save editor content.
     */
    

     public function saveContent(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'content' => 'required|string',
        'image' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:10240', // 10MB Max
        'video' => 'nullable|file|mimes:mp4,mov,avi|max:20480', // 20MB Max
        'audio' => 'nullable|file|mimes:mp3,wav|max:10240', // 10MB Max
    ]);

    // Sanitize the content
    $cleanContent = e($request->input('content'));

    // Handle file uploads
    $imagePath = $request->hasFile('image') ? $request->file('image')->store('media/images', 'public') : null;
    $videoPath = $request->hasFile('video') ? $request->file('video')->store('media/videos', 'public') : null;
    $audioPath = $request->hasFile('audio') ? $request->file('audio')->store('media/audio', 'public') : null;


    $newimagepath  = Storage::url($imagePath);
    $newvideopath  = Storage::url($videoPath);
    $newaudiopath  = Storage::url($audioPath);

    
    // Save the content in the database
    EditorContent::create([
        'content' => $cleanContent,
        'image_path' => $newimagepath,
        'video_path' => $newvideopath,
        'audio_path' => $newaudiopath,
    ]);


    // Return a success message
    return response()->json(['success' => true, 'message' => 'Content saved successfully!']);
}


}


