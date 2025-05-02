<div id='delChat' class="bar"><br>
    <div class="bar-header"><b> DELETE CHAT</b></div>
    <div class="bar-controls">
        <a href="#" onclick="toggleSidebar('delChat')" style="font-size: 24px;">&#10060;</a>
        <input type="text" id="search4" placeholder="Search users" class="search-input" onchange="handleSearch(this)">
    </div>

    <div id="delList">

        <div class="chat-toggle">
            <button onclick="switchTab('private')" class="tab-btn private-btn">Private Chats</button>
            <button onclick="switchTab('group')" class="tab-btn group-btn">Group Chats</button>
        </div>

        <div class="chat-section private-chats" style='max-height: calc(90vh - 210px);'>
            @foreach ($chats as $chat)
            <form action="{{ route('chat.delete') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id_sender" value="{{ session('user_id') }}">
                <input type="hidden" name="user_id_receiver" value="{{ $chat->user_id_receiver }}">
                <input type="hidden" name="receiver" value="{{ $chat->receiver }}">
                <input type="hidden" name="convo_id" value="{{ $chat->convo_id }}">
                <button type="submit" class="button-link">
                    <img src="{{ asset('img/user.png') }}" alt="user" class="user-avatar">
                    <div class="user-info">
                        <strong>{{ $chat->user_id_receiver }}</strong>
                        <span>{{ $chat->receiver }}</span>
                    </div>
                </button>
            </form>
            @endforeach
        </div>

        <div class="chat-section group-chats" style="display: none; max-height: calc(90vh - 210px);">
            {{-- Group Chats --}}
            @foreach ($grps as $grp)
            <form action="{{ route('grp.delete') }}" method="POST">
                @csrf
                <input type="hidden" name="grpId" value="{{ $grp->grpId }}">
                <input type="hidden" name="grp_name" value="{{ $grp->grp_name }}">
                <input type="hidden" name="receiver" value="{{ implode(', ', json_decode($grp->members, true)) }}">
                <input type="hidden" name="convo_id" value="{{ $grp->convo_id }}">
                <button type="submit" class="button-link">
                    <img src="{{ asset('img/user.png') }}" alt="user" class="user-avatar">
                    <div class="user-info">
                        <strong>{{ $grp->grp_name }}</strong>
                        <span>{{ implode(', ', json_decode($grp->members, true)) }}</span>
                    </div>
                </button>
            </form>
            @endforeach
        </div>
    </div>
</div>