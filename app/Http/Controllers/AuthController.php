<?php
namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function login(Request $request){
        // Validate the request data
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Authentication was successful
            $user = Auth::user();

            // Determine the redirect route based on user role
            switch ($user->role) {
                case 'admin':
                    $redirectRoute = 'admin.dashboard';
                    break;
                case 'lecturer':
                    $redirectRoute = 'lecturer.dashboard';
                    break;
                case 'teacher':
                    $redirectRoute = 'teacher.dashboard';
                    break;
                default:
                    $redirectRoute = 'student.dashboard'; // Default route
                    break;
            }
        
            // Log the successful login
            error_log('Login successful');
        
            // Redirect to the determined route
            return redirect()->route($redirectRoute);
            
        } else {
            // Authentication failed
            error_log('Login failed');
            return redirect()->back()->with('error', 'Invalid Username or Password!!!');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Redirect the user to the login page with a success message
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    public function register(Request $request)
    {
        $data = array_merge($request->all(), [
            'ref' => uniqid('USR_', true),
            'role' => 'student',
        ]);

        $emailExists = User::where('email', $data['email'])->exists();
        if ($emailExists) {
            return view('signup', 
                ['error' => 'A user with this email already exist!!!, kindly reset your password if the email belongs to you, or try again!!!',
            ]);
        }
        else{
            // Create the new user with the merged data
            User::create($data);

            // Redirect the user to the login page with a success message
            return redirect()->route('login')->with('success', 'Your Account has been created successfully.');
        }
    }

    public function forgot(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required',
        ]);

        // Attempt to cheeck if the email exist in the user table
        $emailExists = User::where('email', $validatedData['email'])->exists();
        if ($emailExists) {
            // Generate a random token of 11 characters (alphabets and numbers)
            $token = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 11);

            // Insert the email and token into the password_resets table
            DB::table('password_resets')->insert([
                'email' => $validatedData['email'],
                'token' => $token,
                'created_at' => now(),
            ]);

            // Send the token to the user's email
            Mail::to($validatedData['email'])->send(new PasswordResetMail($token));
            $email = $validatedData['email'];
            return view('verify',compact('email'));
            
        } else {
            // Authentication failed
            error_log('Invalid Email Address');
            return redirect()->back()->with('error', 'Invalid Email Address!!!');
        }

    }

    public function verify(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'vcode' => 'required',
        ]);

        // Check if the verification code (token) exists for the provided email
        $checkVCode = DB::table('password_resets')
            ->where('email', $validatedData['email'])
            ->where('token', $validatedData['vcode'])
            ->exists();
        
        $email = $validatedData['email'];

        if ($checkVCode) {
            // Verification successful, proceed with your logic
            return view('changepassword',compact('email'));
            //return response()->json(['message' => 'Verification successful.']);
        } else {
            // Verification failed, return an error
            //return redirect()->back()->with('error', 'Invalid Verification Code, Please try again!!!');
            return view('verify', [
                'email' => $validatedData['email'],
                'error' => 'Invalid Verification Code, Please try again!!!'
            ]);
        }
    }

    public function changepassword(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'password' => 'required',
            'conpassword' => 'required',
        ]);

        $password = $validatedData['password'];
        $conpassword = $validatedData['conpassword'];

        if($password != $conpassword){
            return view('changepassword', [
                'email' => $validatedData['email'],
                'error' => 'New Password and Confirm New Password does not match!!!'
            ]);
        }
        else{
            // Hash the new password
        $hashedPassword = Hash::make($password);

        // Update the user's password with the hashed password
        if (User::where('email', $validatedData['email'])->update(['password' => $hashedPassword])) {
                DB::table('password_resets')->where('email', $validatedData['email'])->delete();
                return view('passwordchangesuccess');
            }
            else{
                return view('changepassword', [
                    'email' => $validatedData['email'],
                    'error' => 'Failed to change your password!!!'
                ]);
            }            

        }
    }
}
