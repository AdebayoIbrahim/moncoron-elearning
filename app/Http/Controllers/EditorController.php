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
        // Validate the input content
        $request->validate([
            'content' => 'required|string',
        ]);

        // Sanitize the content before saving to avoid XSS attacks
        $cleanContent = Purifier::clean($request->input('content'));

        // Save the sanitized content to the database
        EditorContent::create([
            'content' => $cleanContent,
        ]);

        return redirect()->back()->with('success', 'Content saved successfully!');
    }

    /**
     * Handle the file upload from the editor.
     */
    public function upload(Request $request)
    {
        // Validate the request to ensure the file is an image or video and within size limits
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:10240', // 10MB Max
        ]);

        // Store the file in the 'media' directory within the 'public' disk
        $path = $request->file('file')->store('media', 'public');

        // Return the file URL to be used in the editor
        return response()->json(['url' => Storage::url($path)]);
    }
}
