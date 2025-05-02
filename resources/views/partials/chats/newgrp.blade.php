<div id='newGrp' class="bar"><br>
    <div class="bar-header"><b>NEW GROUP</b></div>
    <div class="bar-controls">
        <a href="#" onclick="toggleSidebar('newGrp')" style="font-size: 24px;">&#10060;</a>
        <input type="text" id="search3" placeholder="Search users" class="search-input" onchange="handleSearch(this, {specificContainer: 'grpList', checkboxMode: true})">
    </div>
    <form action="{{route('grp.store')}}" method="POST">
        @csrf
        <div class="group-input-container">
            <input type='text' id='grp_name' name='grp_name' placeholder="Group Name" required>
            <button type="submit" class="group-button">CREATE</button>
        </div>

        <div id="grpList" class="chat-section form-container" style='max-height: calc(90vh - 200px);'>
            @foreach ($users as $user)
            @if($user->user_id == session('user_id'))
            <input type="hidden" name="selected_users[]" value="{{ $user->user_id }}" checked>
            @else
            <label class="user-item">
                <input type="checkbox" name="selected_users[]" value="{{ $user->user_id }}">
                <img src="{{ asset('img/user.png') }}" alt="user" class="user-avatar">
                <div class="user-info">
                    <strong>{{ $user->user_id }}</strong>
                    <span>{{ $user->full_name }}</span>
                </div>
            </label>
            @endif
            @endforeach
            <br>
        </div>
    </form>
</div>