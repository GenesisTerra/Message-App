<div id='Chat' class='bar'><br>
    <div class="bar-header"><b>CHAT</b></div>
    <div class="bar-controls">
    <a href="javascript:void(0);" onclick="toggleSidebar('Chat')" style="font-size: 24px; display: inline-block;">&#10060;</a>
    <input type="text" id="search1" placeholder="Search chats..." class="search-input" onchange="handleSearch(this)">
    </div>
    <div id="chatList" class='list1'>
        <div class="chat-toggle" style=''>
            <button onclick="switchTab('private')" class="tab-btn private-btn">Private Chats</button>
            <button onclick="switchTab('group')" class="tab-btn group-btn">Group Chats</button>
        </div>

        {{-- Private Chats --}}
        <div class="chat-section private-chats" style='max-height: calc(90vh - 210px);'>
            @foreach ($chats as $chat)
            <form action="{{ route('chat.start') }}" method="POST">
                @csrf
                <input type="hidden" name="id_receiver" value="{{ $chat->user_id_receiver }}">
                <input type="hidden" name="receivers" value="{{ $chat->receiver }}">
                <input type="hidden" name="convo_id" value="{{ $chat->convo_id }}">
                <button type="submit" class="button-link"
                    style="background: {{ session('id_receiver') == $chat->user_id_receiver ? 'var(--surface)' : 'transparent' }};">
                    <img src="{{ asset('img/user.png') }}" alt="user" class="user-avatar">
                    <div class="user-info ">
                        <strong>{{ $chat->user_id_receiver }}</strong>
                        <span>{{ $chat->receiver }}</span>
                    </div>
                </button>
            </form>
            @endforeach
        </div>

        {{-- Group Chats --}}
        <div class="chat-section group-chats" style="display: none; max-height: calc(90vh - 210px);">
            @foreach ($grps as $grp)
            <form action="{{ route('chat.start') }}" method="POST">
                @csrf
                <input type="hidden" name="grpId" value="{{ $grp->grpId }}">
                <input type="hidden" name="id_receiver" value="{{ $grp->grp_name }}">
                <input type="hidden" name="receivers" value="{{ implode(', ', json_decode($grp->members, true)) }}">
                <input type="hidden" name="convo_id" value="{{ $grp->convo_id }}">
                <button type="submit" class="button-link"
                    style="background: {{ session('convo_id') == $grp->convo_id ? 'var(--surface)' : 'transparent' }};">
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

<script>
    function setupChatSearch() {
    const searchInput = document.getElementById('search1');
    
    if (!searchInput) return; // Exit if search input doesn't exist
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const activeSection = document.querySelector('.chat-section:not([style*="display: none"])');
        
        if (!activeSection) return;
        
        const chatButtons = activeSection.querySelectorAll('.button-link');
        
        chatButtons.forEach(button => {
            const userName = button.querySelector('strong')?.textContent.toLowerCase() || '';
            const userInfo = button.querySelector('span')?.textContent.toLowerCase() || '';
            
            if (userName.includes(searchTerm) || userInfo.includes(searchTerm)) {
                button.style.display = 'flex';
            } else {
                button.style.display = 'none';
            }
        });
    });
}

// Call this function when your chat interface is loaded
setupChatSearch();
</script>