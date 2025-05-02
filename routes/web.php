<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.submit');

Route::get('/setupProfile', [LoginController::class, 'setupProfileForm'])->name('setupProfileForm');
Route::post('/setupProfile', [LoginController::class, 'setupProfile'])->name('setupProfile.submit');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::get('/forgotPassword', [LoginController::class, 'showForgotPassForm'])->name('forgotPassword');
Route::post('/send-otp', [LoginController::class, 'sendOtp'])->name('otp.send');
Route::post('/verify-otp', [LoginController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/update-password', [LoginController::class, 'updatePassword'])->name('password.update');
// note: mailer needs work .i.e. forgot password wont work

Route::get('/dashboard', [ChatController::class, 'index'])->name('dashboard');
Route::post('/chat/store', [ChatController::class, 'store'])->name('chat.store');
Route::post('/chat/delete', [ChatController::class, 'delete'])->name('chat.delete');
Route::post('/grp/store', [ChatController::class, 'Grpstore'])->name('grp.store');
Route::post('/grp/delete', [ChatController::class, 'Grpdelete'])->name('grp.delete');
Route::post('/chat/start', [ChatController::class, 'start'])->name('chat.start');
Route::post('/chat/new', [ChatController::class, 'newMsg'])->name('newMsg');
Route::post('/chat/del', [ChatController::class, 'deleteMsg'])->name('deleteMsg');

Route::post('/ProfileDisplay', [ChatController::class, 'ProfileDisplay'])->name('ProfileDisplay');
Route::post('/ProfileDisplayOne', [ChatController::class, 'ProfileDisplayOne'])->name('ProfileDisplayOne');
Route::get('/ProfileClose', [ChatController::class, 'ProfileClose'])->name('ProfileClose');

Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
Route::get('/profile/edit', [ProfileController::class, 'profileEdit'])->name('profile.edit');
Route::post('/profile/edit', [ProfileController::class, 'profileUpdate'])->name('profile.update');
Route::get('/changePassword', [ProfileController::class, 'changePassword'])->name('changePassword');
Route::post('/changePassword', [ProfileController::class, 'changePasswordUpdate'])->name('changePassword.submit');

