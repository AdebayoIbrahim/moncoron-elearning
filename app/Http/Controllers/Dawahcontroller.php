<?php

namespace App\Http\Controllers;

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
}
