@extends('layouts.app')

@section('title', '| Chat')

@section('stylesheets')

@endsection

@section('content')

    <div id="app" style="height: 100%;">
        <chat-room :messages="messages" v-on:sendchatmessage="sendMessage"></chat-room>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">

    </script>
@endsection