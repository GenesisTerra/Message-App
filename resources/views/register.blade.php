@extends('layout.start')
@section('title', 'Register')

@section('content')

<div>
    <div class="lock_image" style="left: 50%; border-top-left-radius: 35px; border-bottom-left-radius: 35px;">
        <img src="{{ asset('img/register.jpg') }}" alt="photo">
    </div>
    <div class="lock_container" style='left: 25%;'>
        <div class="logo-container">
            <img src="{{ asset('img/logo.png') }}" alt="LOGO">
        </div>
        <h2>Register</h2>
        <p>Enter your credentials to create your account</p><br>

        @if(session('success'))
        <p style="color: green; margin-bottom:10px;">{{ session('success') }}</p><br>
        @endif
        @if(session('error'))
        <p style="color: red; margin-bottom:10px;">{{ session('error') }}</p><br>
        @endif

        <form id="register" method="post" action="{{ route('register.submit') }}" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="userId">User ID</label>
                <input type="text" id="userId" name="user_id" value="{{ old('user_id') }}"><br>
                @error('user_id') <p style="color:red">{{ $message }}</p><br> @enderror

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"><br>
                @error('email') <p style="color:red">{{ $message }}</p><br> @enderror

                <label for="password">Password</label>
                <input type="password" id="password" name="password"><br>
                @error('password') <p style="color:red">{{ $message }}</p><br> @enderror

                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"><br><br>
            </div>
            <button class="form_button" type="submit">Register</button><br><br>
            <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>

        </form><br>
    </div>
</div>
@endsection