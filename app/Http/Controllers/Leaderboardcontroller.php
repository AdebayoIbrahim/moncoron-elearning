<?php

namespace App\Http\Controllers;

use App\Models\leaderboard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Leaderboardcontroller extends Controller
{

    // load-leader-bord-view
    public function leaderboardview()
    {
        // Log::info("leader" . leaderboard::Local()->get());
        return view('student.leaderboard', [
            'routeNamePart' => 'Leaderboard'
        ]);
    }
    // fetch-allcontrollers
    public function fetchleaderboards(Request $request)
    {
        // filter-byquesr-params
        $typehandling = $request->query('type');
        $json_data = [];
        switch ($typehandling) {
            case "global":
                $json_data = $this->jsonDataconstruct(leaderboard::Allleaderboards()->orderBy('points', 'desc')->get());
                break;
            case "local":
                $json_data = $this->jsonDataconstruct(leaderboard::Local()->orderBy('points', 'desc')->get());
                break;

            default:
                return response()->json(['error no matched leaderboard'], 404);
        }
        return response()->json($json_data, 200);
    }

    protected function jsonDataconstruct($jsondata)
    {
        $refilldata = [];
        foreach ($jsondata as $json) {
            $idfetch = $json->student_id;
            $username = User::find($idfetch)->name;
            $userimage = User::find($idfetch)->avatar ?? null;

            $refilldata[] = array(
                'user_name' => $username,
                'avatar' => $userimage,
                'points' => $json->points,
            );
        }
        return $refilldata;
    }
}
