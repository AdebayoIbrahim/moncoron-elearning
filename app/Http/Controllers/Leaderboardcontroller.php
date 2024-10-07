<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Leaderboardcontroller extends Controller
{

    // load-leader-bord-view
    public function leaderboardview()
    {
        return view('student.leaderboard');
    }
    // fetch-allcontrollers
    public function fetchleaderboards(Request $request)
    {
        // filter-byquesr-params




    }
}
