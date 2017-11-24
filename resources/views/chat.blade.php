@extends('layouts.app')

@section('title', '| Chat')

@section('stylesheets')

@endsection

@section('content')

    <div id="app" style="height: 100%;">
        <chat-room :messages="messages"></chat-room>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript">
        $(function () {

        });

        function sendChatMessage(msg, callback) {
            console.log(msg.message);

            setTimeout(function () {
                if (callback) {
                    callback();
                }
            }, 5000);
        }
    </script>
@endsection