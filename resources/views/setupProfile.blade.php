@extends('layout.start')
@section('title', 'Setup Profile')

@section('content')

<div>
    <div class="lock_image" style="left: 50%; border-top-left-radius: 35px; border-bottom-left-radius: 35px;">
        <img src="{{ asset('img/register.jpg') }}" alt="photo">
    </div>
    <div class="lock_container" style='left: 25%;'>
        <div class="logo-container">
            <img src="{{ asset('img/logo.png') }}" alt="LOGO">
        </div>
        <h2>Setup Profile</h2>
        <p>Enter your credentials to Setup your account</p><br>

        @if(session('success'))
        <p style="color: green; margin-bottom:10px;">{{ session('success') }}</p><br>
        @endif
        @if(session('error'))
        <p style="color: red; margin-bottom:10px;">{{ session('error') }}</p><br>
        @endif

        <form id="Setup" method="post" action="{{ route('setupProfile.submit') }}" enctype="multipart/form-data">
            @csrf

            <div class="tab" style='display:none;'>
                <label for="name">Full Name</label>
                <input type="text" id="name" name="full_name" value="{{ old('full_name') }}"><br><br>
                @error('full_name') <p style="color:red">{{ $message }}</p><br> @enderror

            </div>
            <div class="tab" style='display:none;'>

                <label for="mobile">Mobile Number</label>
                <input type="text" id="mobile" name="mobile_number" value="{{ old('mobile_number') }}"><br><br>
                @error('mobile_number') <p style="color:red">{{ $message }}</p><br> @enderror

            </div>
            <div class="tab" style='display:none;'>

                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="" selected>Select Gender</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                <br><br>
                @error('gender') <p style="color:red">{{ $message }}</p><br> @enderror

            </div>
            <div class="tab" style='display:none;'>

                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" value="{{ old('dob') }}"><br><br>
                @error('dob') <p style="color:red">{{ $message }}</p><br> @enderror
            </div>

            <div style="overflow:auto;">
                <div style="float:left;">
                    <button type="submit" class='next-button' style='background-color:  #bbbbbb;'>Skip</button>
                </div>
                <div style="float:right;">
                    <button type="button" class='next-button' style='background-color: #bbbbbb;' id="prevBtn"
                        onclick="nextPrev(-1,'Setup')">Previous</button>
                    <button type="button" class='next-button' id="nextBtn" onclick="nextPrev(1,'Setup')">Next</button>
                </div>
            </div><br>

            <!-- Circles which indicates the steps of the form: -->
            <div style="text-align:center;margin-top:20px;">
                <span class="step"></span>
                <span class="step"></span>
                <span class="step"></span>
                <span class="step"></span>
                <!-- <span class="step"></span> -->
            </div>
        </form><br>
    </div>
</div>

<script>
function showTab(n, id) {
    console.log('hi')
    let tabs = document.querySelectorAll(".tab");
    if (tabs.length === 0) return;

    tabs.forEach((tab) => (tab.style.display = "none"));
    tabs[n].style.display = "block";

    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");

    if (prevBtn) prevBtn.style.display = n === 0 ? "none" : "inline";
    if (nextBtn) nextBtn.innerHTML = n === tabs.length - 1 ? id : "Next";

    document.querySelectorAll(".step").forEach((step, index) => {
        step.classList.toggle("active", index === n);
        step.classList.toggle("finish", index < n);
    });
}
showTab(0, "Setup");
</script>
@endsection