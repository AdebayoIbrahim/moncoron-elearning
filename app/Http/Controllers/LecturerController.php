<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerController extends Controller
{
    //
    public function dashboard(){
        //$courses = Course::all();
        //return view('dashboard',compact('courses'));
        $user = Auth::user();
        return view('lecturer.dashboard', compact('user'));

    }
}
