<?php

namespace App\Http\Middleware;
// THIS MIDDLEWARE IS FOR STUDENTS COURSES ROUTE ONLY 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscriptions;

class studentcourseaccessmiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // SCOPE-OUT-COURSEID
        $courseid = $request->route('courseid') ?? $request->route('courseId');
        // scope-student-id
        $studentid = auth()->user()->id;


        if (Auth::check() && Auth::user()->role === 'student') {
            // check-if-user-enrolled-for-thecourse
            // onetomanyrelationshipisdefinedonusertosubscriptions
            $subscriptions = Subscriptions::where('course_id', $courseid)->whereHas('user', function ($query) use ($studentid) {
                $query->where('id', $studentid);
            })->first();

            if (!$subscriptions) {
                return redirect('/dashboard')->with('error', "You are not enrolled for this course");
            }
            // resolve-the-request
            return $next($request);
        } else {
            return redirect('/dashboard')->with('error', 'Unknown Error Occoured! relogin please');
        }
    }
}