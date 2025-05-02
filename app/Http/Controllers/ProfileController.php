<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MsgChat;
use App\Models\MsgUser;
use App\Models\MsgProfile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Exception;

class ProfileController extends Controller
{
    public function profile()
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'You must be logged in!');
        }else{
        $user_id = session('user_id');
        $user = MsgProfile::where('user_id', $user_id)->first();
        return view('profile', compact('user'));
        }
    }

    public function profileEdit()
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'You must be logged in!');
        }
        $user_id = session('user_id');
        $user = MsgProfile::where('user_id', $user_id)->first();
        return view('profileEdit', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user_id = session('user_id');
        $validator = Validator::make($request->all(), [
            'full_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'mobile_number' => 'nullable|string|max:15',
            'gender' => 'nullable|in:Male,Female,Other',
            'dob' => 'nullable|date|before:today', 
            'password' => 'required', // |min:8
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $userL = MsgUser::where('user_id', $user_id)->first();
        $userP = MsgProfile::where('user_id', $user_id)->first();
        if (!$userL || !$userP) {
            return back()->with('error', 'User not found.')->withInput();
        }
        if (!Hash::check($request->password, $userL->password)) {
            return back()->with('error', 'Incorrect password.')->withInput();
        }
        $userP->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number ?: null, 
            'gender' => $request->gender ?: null, 
            'dob' => $request->dob ?: null, 
        ]);
        $userL->update(['email' => $request->email]);
        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }



    public function changePassword(){
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'You must be logged in!');
        }
        return view('changePassword');
    }

    public function changePasswordUpdate(Request $request){
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'You must be logged in!');
        }
        $validator = Validator::make($request->all(), [
            'cur_password' => 'required', 
            'password' => 'required|confirmed', //|min:8
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $user = MsgUser::where('user_id', session('user_id'))->first();
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
        if (!Hash::check($request->cur_password, $user->password)) {
            return redirect()->back()->with('error', 'Incorrect current password.');
        }
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        return redirect()->route('profile')->with('success', 'Password changed successfully.');
    }   
}