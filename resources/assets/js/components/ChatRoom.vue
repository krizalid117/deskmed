<template lang="html">
    <div class="chat-room">
        <div class="panel panel-deskmed2 chat-window">
            <div class="panel-heading text-right">
                <div class="col-xs-3 hidden-xs"></div>
                <div class="col-sm-9 col-xs-12 chat-title">asd</div>
            </div>

            <div class="panel-body">
                <div class="col-xs-3 hidden-xs chat-list"></div>
                <div class="col-sm-9 col-xs-12 chat-messagebox">
                    <chat-log v-bind:messages="messages"></chat-log>
                </div>
            </div>

            <div class="panel-footer text-right">
                <div class="col-xs-3 hidden-xs"></div>
                <div class="col-sm-9 col-xs-12 message-composer">
                    <message-composer></message-composer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['messages'],
        methods: {
            sendchatmessage: function () {
                console.log("2222");
            }
        }
    }

</script>

<style lang="css">
    .chat-room {
        height: 100%;
    }

    .chat-window {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        margin-bottom: 0;
    }

    .chat-window .panel-heading {
        flex: 0 0 auto;
        width: 100%;
    }

    .chat-window .panel-body {
        padding: 0 !important;
        flex: 1 1 auto;
        width: 100%;
        height: 100%;

        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }

    .chat-window .panel-footer {
        flex: 0 0 auto;
        width: 100%;
        padding: 10px;
    }

    .chat-list {
        height: calc(100% - 20px);
        border-right: 1px solid #f1f1f1;
    }

    .chat-messagebox {
        height: 100%;
    }

    .message-composer {
        padding: 0;
        padding-left: 10px;
    }
</style>
