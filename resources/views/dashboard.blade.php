@extends('layout.start')

@section('title', 'Dashboard')

@section('content')

<style>
.chatgrid {
    display: grid;
    grid-template-columns: 300px 1fr;
    grid-template-rows: repeat(1, 1fr);
    height: 90vh;
    overflow: hidden;
    transition: all 0.3s ease-in-out;
}

#liveChat,
#createChat,
#deleteChat,
#createGrp {
    display: none;
}

/* Mobile styles (vertical layout) */
@media only screen and (max-width: 768px) {
    .chatgrid {
        grid-template-columns: 1fr;
        grid-template-rows: auto 1fr;
        height: auto;
        min-height: 90vh;
    }
    
}

</style>

<div class='chatgrid'>
    <div id='liveChat' >
        @include('partials.chats.chat')
    </div>
    <div id='createChat'>
        @include('partials.chats.newchat')
    </div>
    <div id='deleteChat'>
        @include('partials.chats.delchat')
    </div>
    <div id='createGrp'>
        @include('partials.chats.newgrp')
    </div>
    <div id='chat-menu'>
        @include('partials.chats.chatWindow')
    </div>
    @include('partials.ProfileDisplay')
</div>

@endsection