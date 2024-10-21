<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Dawahcontroller extends Controller
{
    public function Indexview()
    {
        // scope-all-lecturers
        return view('admin.dawahview', [
            'routeNamePart' => 'Dawah'
        ]);
    }

    //view-lecturere-details

    public function Adminlecturerview($dawahId)
    {
        // dawah-id
        // $dawah_id = $dawahId;

        return view('admin.viewlecturer', [
            'routeNamePart' => 'Lecturerview'
        ]);
    }

    // Dahee-lecturer-upload-lectures
    public function Uploadlecture(Request $request)
    {
        if (!isset($request->video) && !isset($request->audio)) {
            return new JsonResponse(['message' => 'error A media field is required'], 400);
        }
        $datas = $request->toArray();


        $user_posting = auth()->user()->id;

        // filter-null-values
        $filteredData = array_filter($datas, function ($array) {
            return !is_null($array);
        });

        Log::info($filteredData);
    }
}
