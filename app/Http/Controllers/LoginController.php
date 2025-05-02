<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MsgUser;
use App\Models\MsgChat;
use App\Models\Msgprofile;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller{
    private $maxAttempts = 3;
    private $lockDuration = 10; 
    private $resetPeriod = 60; 

    public function showRegisterForm(){
        Session()->forget(['otp', 'otp_verified', 'Userid']);
        return view('register');
    }

    public function setupProfileForm(){
        Session()->forget(['otp', 'otp_verified', 'Userid']);
        return view('setupProfile');
    }

    public function setupProfile(Request $request){
        $validator = Validator::make($request->all(), [
            'full_name' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:15',
            'gender' => 'nullable|in:Male,Female,Other',
            'dob' => 'nullable|date|before:today', 
        ]);

        try {  
            $profile = MsgProfile::where('user_id', session('user_id'))->first();
            $profile->full_name = $request->full_name;
            $profile->mobile_number = $request->mobile_number;
            $profile->gender = $request->gender;
            $profile->dob = $request->dob;
            $profile->save();

            session(['full_name' => $request->full_name]);
            return redirect()->route('dashboard')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'full_name' => 'nullable|string|max:255',
            'user_id' => 'required|string|max:255|unique:MsgUsers,user_id',
            'email' => 'required|email|max:255',
            'mobile_number' => 'nullable|string|max:15',
            'gender' => 'nullable|in:Male,Female,Other',
            'dob' => 'nullable|date|before:today', 
            'password' => 'required|confirmed', // |min:8
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        try {
            // Create MsgUser record
            $user = new MsgUser();
            $user->user_id = $request->user_id;
            $user->email = $request->email;
            $user->password = Hash::make($request->password); // Hash the password
            $user->failed_attempts = 0;
            $user->lock_until = 0;
            $user->last_attempt = 0;
            $user->save();
    
            // Create MsgProfile record
            $profile = new MsgProfile();
            $profile->user_id = $request->user_id;
            $profile->full_name = $request->full_name;
            $profile->email = $request->email;
            $profile->mobile_number = $request->mobile_number;
            $profile->gender = $request->gender;
            $profile->dob = $request->dob;
            $profile->save();

            session(['user_id' => $request->user_id]);
            session(['full_name' => $request->full_name]);
            return redirect()->route('setupProfileForm')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    

    public function showLoginForm(){
        Session()->forget(['otp', 'otp_verified', 'Userid']);
        return view('login');
    }

    public function login(Request $request){
        $request->validate([
            'loginId' => 'required',
            'password' => 'required',
        ]);
        $user = MsgUser::where('user_id', $request->loginId)->first();
        $profile = MsgProfile::where('user_id', $request->loginId)->first();
        if (!$user) {
            return back()->with('error', 'User ID not found!');
        }
        $currentTime = time();
        if ($currentTime - $user->last_attempt > $this->resetPeriod) {
            $user->failed_attempts = 0;
            $user->save();
        }
        if ($user->failed_attempts >= $this->maxAttempts && $currentTime < $user->lock_until) {
            return back()->with('error', 'Too many attempts. Please try again later.');
        }
        if (Hash::check($request->password, $user->password)) {
            $user->update([
                'failed_attempts' => 0,
                'lock_until' => 0,
            ]);
            session(['user_id' => $user->user_id]);
            session(['full_name' => $profile->full_name]);
            return redirect()->route('dashboard');
        } else {
            $user->failed_attempts += 1;
            $lockUntil = ($user->failed_attempts >= $this->maxAttempts) ? $currentTime + $this->lockDuration : $user->lock_until;
            $user->update([
                'failed_attempts' => $user->failed_attempts,
                'lock_until' => $lockUntil,
                'last_attempt' => $currentTime
            ]);
            $errorMessage = ($user->failed_attempts >= $this->maxAttempts) ? 'Too many attempts. Please try again later.' : 'Invalid password!';
            return back()->with('error', $errorMessage);
        }
    }

    public function logout(){
        session()->flush();
        return redirect()->route('login');
    }

    public function showForgotPassForm(){
        return view("forgotPassword");
    }

    public function sendOtp(Request $request){
        $request->validate([
            'Userid' => 'required|exists:msgusers,user_id', 
        ]);
        try {
            $user = MsgUser::where('user_id', $request->Userid)->firstOrFail();
            $otp = rand(100000, 999999);
            session(['otp' => $otp]);
            session(['Userid' => $request->Userid]);
            // Mail::raw("Your OTP code is: $otp", function ($message) use ($user) {
            //     $message->to($user->email)->subject('Your OTP Code');
            // });
            return back()->with('success', 'An OTP has been sent to your email.');
        } catch (Exception $e) {
            Log::error("OTP Send Error: " . $e->getMessage());
            return back()->with('error', 'Failed to send OTP. Please try again.');
        }
    }

    public function verifyOtp(Request $request){
        $request->validate([
            'otp' => 'required|numeric',
        ]);
        try {
            if ($request->otp == session('otp')) {
                Session(['otp_verified' => 'true']);
                return back()->with('success', 'OTP verified successfully.');
            } else {
                return back()->with('error', 'Invalid OTP. Please try again.');
            }
        } catch (Exception $e) {
            Log::error("OTP Verification Error: " . $e->getMessage());
            return back()->with('error', 'An error occurred while verifying the OTP.');
        }
    }

    public function updatePassword(Request $request){
        $request->validate([
            'password' => 'required|confirmed',
        ]);
        try {
            $user = MsgUser::where('user_id', Session('Userid'))->first();
            $user->password = Hash::make($request->password);
            $user->save();
            Session()->forget(['otp', 'otp_verified', 'Userid']);
            return redirect()->route('login')->with('success', 'Password updated successfully!');
        } catch (Exception $e) {
            Log::error("Password Update Error: " . $e->getMessage());
            return back()->with('error', 'Failed to update password. Please try again.');
        }
    }

}