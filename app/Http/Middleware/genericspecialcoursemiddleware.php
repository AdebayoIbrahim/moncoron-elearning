<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Course;

class genericspecialcoursemiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


        $courseid = $request->route("courseId") ?? $request->route('courseid');

        $course = Course::find($courseid);

        if ($course->course_type === "special") {
            //     // continue-tocheck-priviledge
            if (auth()->user()->user_type != 'premium') {
                return redirect('dashboard')->with('error', 'You have no acess to this course');
            }
        }

        return $next($request);
    }
}
