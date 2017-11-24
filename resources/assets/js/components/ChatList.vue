<template lang="html">
    <div class="chat-list-container">
        <chat-session v-for="chatlist in chatlists" :session="chatlist" v-on:selectsession="sessionSelected"></chat-session>
    </div>
</template>

<script>
    export default {
        props: ['chatlists'],
        data() {
            return {

            }
        },
        methods: {
            sessionSelected: function (obj) {

                this.$emit('changeselectedsession', obj);

//                $(obj.el).find('.session-selected-row').show();
            }
        }
    }
</script>

<style lang="css">

</style>