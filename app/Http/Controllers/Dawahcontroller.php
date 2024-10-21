<?php

namespace App\Http\Controllers;

use App\Models\Dawahlecturesmodel;
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
            return new JsonResponse(['message' => 'A media field is required'], 400);
        }
        $lesson_title = $request->lecturetitle;
        // TODO;mddlewarechecks!!
        $user_posting = auth()->user()->id;
        $formatted_data = [
            'lecturetitle' => $lesson_title,
        ];
        $audiopath = null;
        $videofile = null;
        // process-file-inputs
        if (!isset($request->audio)) {
            $audiopath = $request->file('audio')->store('media/Dawahlectures', 'public');
        }
        if (!isset($request->video)) {
            $videofile = $request->file('audio')->store('media/Dawahlectures', 'public');
        }
        $formatted_data['video'] = $videofile;
        $formatted_data['audio'] = $audiopath;
        $filteredData = array_filter($formatted_data, function ($array) {
            return !is_null($array);
        });

        // filtered_Data-ready to be saved
        Dawahlecturesmodel::updateOrCreate([
            'dahee_id' => $user_posting,
        ], [
            'uploads' => json_encode($filteredData),
        ]);

        return response()->json(['message' => 'Upload Successfu'], 201);
    }
}
