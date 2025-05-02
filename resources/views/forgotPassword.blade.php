@extends('layout.start')
@section('title', 'Forgot Password')

@section('content')

<!-- In your Blade template -->
<input type="hidden" id="sessionOtp" value="{{ session('otp') }}">

<script>
    // Get the value and log it
    const otp = document.getElementById('sessionOtp').value;
    console.log("OTP:", otp);
</script>
<div class="lock_image" style="border-top-right-radius: 35px; border-bottom-right-radius: 35px;">
    <img src="{{ asset('img/login.jpg') }}" alt="photo">
</div>
<div class="lock_container" style='left:75%'>
    <div class="logo-container">
        <img src="{{ asset('img/logo.png') }}" alt="LOGO">
    </div>
    <h2>Forgot Password</h2>
    <p>Enter your credentials to reset your password</p><br>

    @if(session('success'))
    <p style="color: green;">{{ session('success') }}</p><br>
    @endif
    @if(session('error'))
    <p style="color: red;">{{ session('error') }}</p><br>
    @endif
    
    <!-- Form to send OTP -->
    @if(!session('otp') && !session('otp_verified'))
    <form method="POST" action="{{ route('otp.send') }}">
        @csrf
        <label for="Userid">Enter your User ID:</label><br>
        <input type="text" id="Userid" name="Userid" required><br><br>
        <input class="form_button" type="submit" value="Send OTP">
    </form>
    @endif

    <!-- Form to verify OTP -->
    @if(session('otp') && !session('otp_verified'))
    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <label for="otp">Enter OTP:</label><br>
        <input type="text" id="otp" name="otp" required><br><br>
        <input class="form_button" type="submit" value="Verify OTP">
    </form>
    @endif

    <!-- Form to update password -->
    @if(session('otp') && session('otp_verified'))
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <label for="password_confirmation">Confirm Password:</label><br>
        <input type="password" id="password_confirmation" name="password_confirmation" required><br><br>
        <button class="form_button" type="submit">Update Password</button>
    </form>
    @endif
    <br>
    <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
</div>

@endsection