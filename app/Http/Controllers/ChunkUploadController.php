<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChunkUploadController extends Controller
{
    public function uploadChunk(Request $request)
    {
        $fileName = $request->fileName;
        $chunkIndex = $request->chunkIndex;
        $fileChunk = $request->file('fileChunk');

        // Store the chunk
        $fileChunk->storeAs('chunks/' . $fileName, $fileName . '.part' . $chunkIndex);

        return response()->json(['status' => 'Chunk uploaded successfully']);
    }

    public function finishUpload(Request $request)
    {
        $fileName = $request->fileName;
        $chunkTotal = $request->chunkTotal;
        $filePath = 'uploads/' . $fileName;

        // Combine all chunks
        Storage::disk('local')->put($filePath, '');
        for ($i = 0; $i < $chunkTotal; $i++) {
            $chunkPath = storage_path('app/chunks/' . $fileName . '/' . $fileName . '.part' . $i);
            $chunkContent = file_get_contents($chunkPath);
            Storage::disk('local')->append($filePath, $chunkContent);
            unlink($chunkPath); // Delete chunk after appending
        }

        // Optionally, delete the chunk directory
        rmdir(storage_path('app/chunks/' . $fileName));

        return response()->json(['status' => 'File upload complete', 'filePath' => $filePath]);
    }
}