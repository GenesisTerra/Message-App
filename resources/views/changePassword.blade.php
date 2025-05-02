@extends('layout.start')

@section('title', 'Dashboard')

@section('content')


<div class="container" style='width: 40%'>
    <h2>Edit Profile</h2><br>

    @if (session('error'))
    <p style="color: red;">{{ session('error') }} <br></p><br>
    @endif
    @if (session('success'))
    <p style="color: green;">{{ session('success') }} <br></p><br>
    @endif

    <form method="POST" action="{{ route('changePassword.submit') }}">
        @csrf
        <label for="cur_password">Current Password:</label><br>
        <input type="password" id="cur_password" name="cur_password" required>
        @error('cur_password') <p style="color:red">{{ $message }}</p><br> @enderror<br><br>

        <label for="password">New Password:</label><br>
        <input type="password" id="password" name="password" required><br>
        @error('password') <p style="color:red">{{ $message }}</p><br> @enderror<br>

        <label for="password_confirmation">Confirm Password:</label><br>
        <input type="password" id="password_confirmation" name="password_confirmation" required><br><br>

        <button class="form_button" type="submit">Change Password</button>
    </form>
</div>

@endsection