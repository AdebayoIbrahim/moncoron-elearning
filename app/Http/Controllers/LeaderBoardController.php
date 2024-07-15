<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAssessmentSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'overall');
        $courseId = $request->input('course_id');
        $country = $request->input('country');

        $query = User::join('course_assessment_submissions', 'users.id', '=', 'course_assessment_submissions.user_id')
            ->join('courses', 'course_assessment_submissions.course_id', '=', 'courses.id')
            ->select('users.name', 'users.country', 'courses.name as course_name', DB::raw('SUM(course_assessment_submissions.score) as total_score'))
            ->groupBy('users.id', 'courses.id')
            ->orderByDesc('total_score');

        if ($filter == 'course' && $courseId) {
            $query->where('courses.id', $courseId);
        }

        if ($filter == 'country' && $country) {
            $query->where('users.country', $country);
        }

        $leaderboard = $query->get();
        $courses = Course::all();
        $countries = User::select('country')->distinct()->get();

        return view('leaderboard.index', compact('leaderboard', 'courses', 'countries', 'filter', 'courseId', 'country'));
    }
}
