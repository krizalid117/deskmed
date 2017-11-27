@extends('layouts.app')

@section('title', '| Chat')

@section('stylesheets')

@endsection

@section('content')

    <div id="app" style="height: 100%;">
        <chat-room :receiver="receiver" :activesession="activesession" :messages="messages" :chatlists="chatlists" v-on:selectsession="selectSession" v-on:sendchatmessage="sendChatMessage"></chat-room>
    </div>

@endsection

@section('scripts')

    <script type="text/javascript">

    </script>

@endsection