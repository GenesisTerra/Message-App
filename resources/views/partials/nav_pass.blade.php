<nav>
    <div>
        <span class='navcontainer' style="font-size:30px;" onclick="sidebartoggle()">&#9776;</span>
        <a href="{{ route('dashboard') }}" class="navcontainer">
            <img src="{{asset('img/logo.png')}}" alt="LOGO" class='logo'>
            <span class='logo-text'>AVINEX</span>
        </a>
    </div>
    <div style="margin-right:20px">
    <a id="themeToggle" href="#" class="emoji_button">&#127769;</a>
            &nbsp;&nbsp;
        <div class="dropdown">
            <button class="dropdown-button">{{ session( 'user_id' )}}</button>
            <div class="dropdown-content">
                <a href="{{ route('profile') }}">Profile</a>
                <a href="{{ route('logout') }}">logout</a>
            </div>
        </div>
    </div>
</nav>