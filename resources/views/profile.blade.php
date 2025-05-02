@extends('layout.start')

@section('title', 'Profile')

@section('content')
<div class="container">
    @if(session('success'))
    <p style="color: green; margin-bottom:10px;">{{ session('success') }}</p><br>
    @endif
    <div class="profile-header">
        <img src="{{ asset('img/user.png') }}" alt="User Profile Picture">
        <h2>{{ $user->user_id }}</h2>
        <p style="color: #818181">{{ $user->mobile_number ?? 'N/A' }} | {{ $user->email ?? 'N/A' }}</p>
    </div>

    <div class="profile-body">
        <div class="profile-info">
            <h3>ABOUT ME</h3>
            <p><span>Name:</span> {{ $user->full_name ?? 'N/A' }}</p>
            <p><span>User ID:</span> {{ $user->user_id ?? 'N/A' }}</p>
            <p><span>Email:</span> {{ $user->email ?? 'N/A' }}</p>
            <p><span>Phone:</span> {{ $user->mobile_number ?? 'N/A' }}</p>
            <p><span>Gender:</span> {{ $user->gender ?? 'N/A' }}</p>
            <p><span>Date of Birth:</span> {{ $user->dob ? date('F j, Y', strtotime($user->dob)) : 'N/A' }}
            </p>
            <p><span>Joined:</span> {{ date('F j, Y', strtotime($user->created_at)) }}</p>
        </div>


        <div class="profile-actions">
            <a href="{{ route('profile.edit') }}">Edit Profile</a>
            <a href="{{ route('changePassword') }}">Change Password</a>
            <a href="{{ route('logout') }}">Log Out</a>
        </div>
    </div>
</div>
@endsection