@extends('layouts.app')

@section('title', '| Chat')

@section('stylesheets')

@endsection

@section('content')

    <div id="app" style="height: 100%;">
        <chat-room :activesession="activesession" :messages="messages" :chatlists="chatlists" v-on:selectsession="selectSession" v-on:sendchatmessage="sendChatMessage"></chat-room>
    </div>

@endsection

@section('scripts')

    <script type="text/javascript">
        $(function () {
            @if (!is_null($uuid))

            @endif
        });

        function vueSelectSession(obj, callback) {
            mensajes.alerta('selectSession recieved!', "Alerta", function () {
                if (callback) {
                    callback();
                }
            });
        }

        function vueSendChatMessage(obj, callback) {
            mensajes.alerta('SendChatMessage recieved: ' + obj.message);
        }
    </script>

@endsection