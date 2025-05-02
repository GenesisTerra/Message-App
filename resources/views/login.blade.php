@extends('layout.start')
@section('title', 'Login')

@section('content')
<div class="lock_image" style="border-top-right-radius: 35px; border-bottom-right-radius: 35px;">
    <img src="{{ asset('img/login.jpg') }}" alt="photo">
</div>
<div class="lock_container" style='left:75%'>
    <div class="logo-container">
        <img src="{{ asset('img/logo.png') }}" alt="LOGO">
    </div>
    <h2>Login</h2>
    <p>Enter your credentials to access your account</p><br>

    @if(session('success'))
    <p style="color: green; margin-bottom:10px;">{{ session('success') }}</p><br>
    @endif
    @if(session('error'))
    <p style="color: red; margin-bottom:10px;">{{ session('error') }}</p><br>
    @endif

    <form class='login' method="POST" action="{{ route('login.post') }}">
        @csrf
        <label for="loginId">Login ID:</label>
        <input type="text" name="loginId" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>

        <a href="{{ route('forgotPassword') }}">Forgot Password?</a><br><br>

        <button class="form_button" type="submit">Login</button>
    </form><br>
    <p>Don't have an account? <a href="{{ route('register') }}">Create an account</a></p>
</div>
@endsection