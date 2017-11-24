<template lang="html">
    <div v-bind:class="{ 'chat-message-sender': message.isSender, 'chat-message': true }">
        <div class="chat-message-time hidden-xs">{{ message.time }}</div>
        <div class="chat-message-container">{{ message.message }}</div>
        <div class="chat-message-image">
            <img class="img-circle" :src="message.image" alt="" :title="message.user">
        </div>
    </div>
</template>

<script>
    export default {
        props: ['message']
    }
</script>

<style lang="css">
    .chat-message {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: flex-start;

        margin: 10px 0;
    }

    .chat-message.chat-message-sender {
        flex-direction: row-reverse;
    }

    .chat-message-container {
        flex: 1 1 auto;

        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;

        margin: 0 10px;
        padding: 5px 15px;
        position: relative;
        background-color: #f1f1f1;
        text-align: right;
    }

    .chat-message.chat-message-sender .chat-message-container {
        background-color: rgba(240, 144, 6, .5);
        text-align: left;
    }

    .chat-message.chat-message-sender .chat-message-container:before {
        content: " ";
        position: absolute;
        left: -6px;
        top: 7px;
        width: 0;
        height: 0;

        border-bottom: 2px solid rgba(240, 144, 6, .5);
        border-right: 5px solid rgba(240, 144, 6, .5);
        border-top: 5px solid transparent;
        border-left: 1px solid transparent;
    }


    .chat-message:not(.chat-message-sender) .chat-message-container:after {
        content: " ";
        position: absolute;
        right: -6px;
        top: 7px;
        width: 0;
        height: 0;

        border-bottom: 2px solid #f1f1f1;
        border-left: 5px solid #f1f1f1;
        border-top: 5px solid transparent;
        border-right: 1px solid transparent;
    }

    .chat-message-time {
        flex: 0 0 auto;

        font-size: .65em;
    }

    .chat-message-image {
        flex: 0 0 auto;
    }

    .chat-message-image img {
        width: 30px;
        height: 30px;

        -webkit-user-drag: none;
    }
</style>