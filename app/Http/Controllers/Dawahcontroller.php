<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Dawahcontroller extends Controller
{
    public function Indexview()
    {
        // scope-all-lecturers
        return view('admin.dawahview', [
            'routeNamePart' => 'Dawah'
        ]);
    }
}
