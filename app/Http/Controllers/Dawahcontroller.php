<?php

namespace App\Http\Controllers;

use App\Models\Dawahlecturesmodel;
use App\Models\User;
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
        if ($request->audio && $request->audio != null) {
            $audiopath = $request->file('audio')->store('media/Dawahlectures', 'public');
        }
        if ($request->video && $request->video != null) {
            $videofile = $request->file('video')->store('media/Dawahlectures', 'public');
        }


        $uploads['video'] = $videofile;
        $uploads['audio'] = $audiopath;
        $updfilter = array_filter($uploads, function ($array) {
            return !is_null($array);
        });

        $finaldata = null;
        $formatted_data['uploads'][] = $updfilter;

        // check-if-ther-exist-previous-uploads
        $prevdata = Dawahlecturesmodel::where('dahee_id', $user_posting)->first();
        if ($prevdata) {
            // get-prev
            $uploadprev = json_decode($prevdata->uploads, true);
            // generate-new
            $newdata = $formatted_data;
            // push-to-theprev
            $uploadprev[] = $newdata;
            // assign-finaldata-to-concetentedone
            $finaldata = $uploadprev;
        } else {
            $finaldata =  [$formatted_data];
        }
        Log::alert('Final', $finaldata);


        // filtered_Data-ready to be saved
        Dawahlecturesmodel::updateOrCreate([
            'dahee_id' => $user_posting,
        ], [
            'uploads' => json_encode($finaldata),
        ]);

        return response()->json(['message' => 'Upload Successfu'], 201);
    }

    // fetchleturer-bu-id
    public function fetchlecturerbyId($daheeId)
    {

        // fecth-lecturer
        // Todo:heckif-lecture
        $lecturer = User::where('id', $daheeId)->first();

        if (!$lecturer) {
            return new JsonResponse(['message' => 'No leturer Found'], 400);
        };

        // if-found-fetch-there-details
        $dawahs = Dawahlecturesmodel::where('dahee_id', $lecturer->id)->first();

        // return_details_asjsonreponse

        return response()->json([
            'avatar_url' => null,
            'biography' => null,
            'dahee_name' => $lecturer->name,
            'uploads' => json_decode($dawahs->uploads),
        ], 200);
    }
}
