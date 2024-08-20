<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve filter inputs
        $filter = $request->input('filter', 'overall');
        $courseId = $request->input('course_id');
        $country = $request->input('country');

        // Start building the query for leaderboard
        $query = User::join('user_lesson_assessments', 'users.id', '=', 'user_lesson_assessments.user_id')
            ->select(
                'users.id', 
                'users.name', 
                'users.country', 
                DB::raw('SUM(user_lesson_assessments.score) as total_score')
            )
            ->groupBy('users.id', 'users.name', 'users.country')
            ->orderByDesc('total_score');

        // Filter by course if 'course' filter is selected
        if ($filter == 'course' && $courseId) {
            $query->where('user_lesson_assessments.course_id', $courseId);
        }

        // Filter by country if 'country' filter is selected
        if ($filter == 'country' && $country) {
            $query->where('users.country', $country);
        }

        // Execute the query to get the leaderboard data
        $leaderboard = $query->get();

        // Retrieve all available courses and distinct countries for filter options
        $courses = Course::all();
        $countries = User::select('country')->distinct()->get();

        // Return the leaderboard view with necessary data
        return view('leaderboard.index', compact('leaderboard', 'courses', 'countries', 'filter', 'courseId', 'country'));
    }
}
