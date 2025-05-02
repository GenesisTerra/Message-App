<div class='content-box'>
    @if(session()->has('id_receiver'))
    <form action="{{ route('ProfileDisplay') }}" method="Post" class="id-tab">
        @csrf
        <button type="submit"
            style="border: none; background: none; display: flex; align-items: center; width: 100%; text-align: left; cursor: pointer;">
            <img src="{{ asset('img/user.png') }}" alt="user" class="user-avatar" style="margin-right: 10px;">
            <div class="user-info">
                <strong style='color:var(--on-background); font-size:18px'>{{ session('id_receiver') }}</strong>
                <span style='color:var(--on-background); font-size:14px'>{{ session('receivers') }}</span>
            </div>
        </button>
        <input type="hidden" name="id_receiver" value="{{ session('id_receiver') }}">
        <input type="hidden" name="receivers" value="{{ session('receivers') }}">
        <input type="hidden" name="convo_id" value="{{ session('convo_id') }}">
    </form>

    <div>

        @php
        $previousSender = null;
        @endphp
        @foreach($msg as $message)

        @php
        $cleanedFilePath = preg_replace('/_(\d+)(\..+)$/', '$2', $message->filePath);
        @endphp

        @if($previousSender !== $message->sender)
        <div class='{{ session("user_id") == $message->sender ? "sender" : "receiver" }}'>
            <p style="font-size: 20px;">{{ $message->sender }}</p>
        </div>
        @endif

        @php
        $previousSender = $message->sender;
        @endphp

        <div class="message {{ session('user_id') == $message->sender ? 'sender' : 'receiver' }}">

            <!-- Delete Form (Initially Hidden) -->
            <form action="{{ route('deleteMsg') }}" method="POST" class="delete-form">
                @csrf
                <input type="hidden" name="id" value="{{ $message->id }}">
                <button type="submit">🗑️</button>
            </form>

            <!-- Message Content -->
            <div
                style="background-color:{{session('user_id')==$message->sender?'var(--secondary)':'var(--background)'}}"
                class="{{ session('user_id') == $message->sender ? 'sender' : 'receiver' }}">
                @if ($message->filePath)

                @if (in_array(pathinfo($message->filePath, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'svg',
                'webp']))
                <img src="{{ asset('storage/uploads/' . $message->filePath) }}" onclick="openModal(this)"><br>
                @else
                <strong class="download-label">{{ $cleanedFilePath }}</strong>
                <a class="download-btn" href="{{ asset('storage/uploads/' . $message->filePath) }}"
                    download="{{ $cleanedFilePath }}">Download File</a><br>
                @endif

                @endif

                @if ($message->conversation)
                <strong>{{ $message->conversation }}</strong><br>
                @endif
                <span style="font-size:10px">{{ \Carbon\Carbon::parse($message->created_at)->format('h:i A') }}</span>
            </div>
        </div>
        @endforeach
    </div>
    <style>

    </style>
    <div class='send-tab'>
        <div id='file-info'>
            <span id="fileName"></span>
            <button type="button" id="removeFile" onclick="removeFile()">&#10060;</button>
        </div>
        <form class='send' autocomplete="off" action="{{ route('newMsg') }}" enctype="multipart/form-data"
            method='POST'>
            @csrf
            <input type="hidden" name='user_id' value="{{session( 'user_id' )}}">
            <input type="hidden" name='convo_id' value="{{session( 'convo_id' )}}">
            <label for="fileInput" class="file-label">&#x1F4CC;</label>
            <input type="file" id="fileInput" style='display:none;' name="file" onchange="updateFileName()">
            <input type="text" name="message" placeholder="Type your message">
            <button type='submit'><b>Send</b></button>
        </form>
    </div>
    @else
    <div style='background:var(--surface); display: flex; justify-content: center; align-items: center; height:90vh;'>
        <p style='text-align:center;'><img src="{{ asset('img/on_chat.png') }}" style='height: 100px; width: auto; filter: grayscale(50%);' ><br><br>No Chat Selected</p>
    </div>
    @endif
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal" onclick="closeModal()">
    <span class="close">&times;</span>
    <img class="modal-content" id="modalImage">
</div>