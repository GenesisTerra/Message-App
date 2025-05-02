@extends('layout.start')

@section('title', 'Profile')

@section('content')
<div class="container" style='width: 40%'>
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        <div id='1'>
            <h2>Edit Profile</h2><br>
            @if (session('error'))
            <p style="color: red;">{{ session('error') }} <br></p><br>
            @endif
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $user->full_name) }}">
            @error('full_name') <p style="color:red">{{ $message }}</p><br> @enderror<br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}">
            @error('email') <p style="color:red">{{ $message }}</p><br> @enderror<br>

            <label for="mobile_number">Mobile Number:</label>
            <input type="text" id="mobile_number" name="mobile_number"
                value="{{ old('mobile_number', $user->mobile_number) }}">
            @error('mobile_number') <p style="color:red">{{ $message }}</p><br> @enderror<br>

            <label for="gender">Gender</label>
            <select id="gender" name="gender">
                <option value="" {{ old('gender', $user->gender) ? '' : 'selected' }}>Select Gender
                </option>
                <option value="Male" @selected(old('gender', $user->gender) === 'Male')>Male</option>
                <option value="Female" @selected(old('gender', $user->gender) === 'Female')>Female</option>
                <option value="Other" @selected(old('gender', $user->gender) === 'Other')>Other</option>
            </select>

            <br>
            @error('gender') <p style="color:red">{{ $message }}</p><br> @enderror

            <label for="dob">Date of Birth</label>
            <input type="date" id="dob" name="dob" value="{{ old('dob', $user->dob) }}"><br>
            @error('dob') <p style="color:red">{{ $message }}</p><br> @enderror<br>

            <button class="form_button" onclick='switch_con()' type="button">Submit</button>
        </div>
        <div id='2' style='display:none;'>
            <h2>Edit Profile</h2><br>
            <label for="password">Confirm Password:</label>
            <input type="password" name="password" required><br>
            @error('password') <p style="color:red">{{ $message }}</p><br> @enderror<br>

            <button class="form_button" type="submit">Confirm</button>
        </div>
    </form>
</div>


<script>
function switch_con() {
    document.getElementById('1').style.display = 'none';
    document.getElementById('2').style.display = 'block';
}
</script>
@endsection