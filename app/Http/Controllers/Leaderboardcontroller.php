<?php

namespace App\Http\Controllers;

use App\Models\leaderboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Leaderboardcontroller extends Controller
{

    // load-leader-bord-view
    public function leaderboardview()
    {


        Log::info("leader" . leaderboard::Local()->get());
        return view('student.leaderboard', [
            'routeNamePart' => 'Leaderboard'
        ]);
    }
    // fetch-allcontrollers
    public function fetchleaderboards(Request $request)
    {
        // filter-byquesr-params




    }
}
