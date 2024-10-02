<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\lessonassessmentresults;
use App\Models\User;

class lessonnavigatemidlleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // grab-the -lesson-id-from-every-route-that-has-lessoid
        $lessonidfetchedid = $request->route('lesson_id') ?? $request->route('lessonId');

        // grab-course_id-for-redirect
        $coursefetchid = $request->route('course_id') ?? $request->route('courseId');

        // get-user-auth-id
        $usrasslst = auth()->user()->userAssessmentresult;
        if ($lessonidfetchedid <= 1) {
            return $next($request);
        } else {
            //  subtract-theid-and-gettheprevious-one
            $checkprevid = (int) $lessonidfetchedid - 1;
            // now-check-assessment-result-if-exist-and-dotheneedful

            $checkerfunction = $usrasslst->where('lesson_id', $checkprevid)->where("status", "Passed")->first();

            if ($checkerfunction) {
                return $next($request);
            } else {
                return redirect("/courses/{$coursefetchid}/{$checkprevid}")->with('error', 'Hmm, it seems like you haven\'t finished the previous course yet. Please complete it before proceeding.');
            }
        }
    }
}
