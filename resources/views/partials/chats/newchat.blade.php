<div id='newChat' class="bar"><br>
    <div class="bar-header"><b> NEW CHAT</b></div>
    <div class="bar-controls">
        <a href="#" onclick="toggleSidebar('newChat')" style="font-size: 24px;">&#10060;</a>
        <input type="text" id="search2" placeholder="Search users" class="search-input" onchange="handleSearch(this, {specificContainer: 'userList'})">
    </div>

    <div id="userList" class='chat-section'>
        @foreach ($users as $user)
        <form action="{{ route('chat.store') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id_sender" value="{{ session('user_id') }}">
            <input type="hidden" name="user_id_receiver" value="{{ $user->user_id }}">
            <input type="hidden" name="receiver" value="{{ $user->full_name }}">
            <button type="submit" class="button-link">
                <img src="{{ asset('img/user.png') }}" alt="user" class="user-avatar">
                <div class="user-info">
                    <strong>{{ $user->user_id }}</strong>
                    <span>{{ $user->full_name }}</span>
                </div>
            </button>
        </form>
        @endforeach
    </div>
</div>