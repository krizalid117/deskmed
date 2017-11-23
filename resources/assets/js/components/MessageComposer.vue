<template lang="html">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Escriba aquí su mensaje..." v-model="messageText" @keydown.enter="sendMessage">
        <span class="input-group-btn">
            <button class="btn btn-primary" @click="sendMessage">Enviar</button>
        </span>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                messageText: ''
            }
        },
        methods: {
            sendMessage() {
                if ($.trim(this.messageText).length > 0) {
                    var msg = $.trim(this.messageText);

//                    this.$emit('sendChatMessage', {
//                        message: msg
//                    });

                    this.messageText = '';
                }
            }
        }
    }
</script>

<style lang="css">

</style>