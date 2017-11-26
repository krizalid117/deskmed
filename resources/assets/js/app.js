
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// import ChatRoom from './components/ChatRoom.vue';

Vue.component('chat-room', require('./components/ChatRoom.vue'));
Vue.component('chat-list', require('./components/ChatList.vue'));
Vue.component('chat-session', require('./components/ChatSession.vue'));
Vue.component('message-composer', require('./components/MessageComposer.vue'));
Vue.component('chat-log', require('./components/ChatLog.vue'));
Vue.component('chat-message', require('./components/ChatMessage.vue'));

const chat = new Vue({
    el: '#app',
    data: {
        activesession: '',
        messages: [],
        chatlists: []
    },
    methods: {
        selectSession: function (obj) {

            const THIS = this;

            this.$http.get('/getchatroommessages/' + obj.uuid).then(function (res) {
                this.activesession = obj.uuid;
                this.messages = res.data.messages;

                Echo.private('chatroom.' + res.data.id_hora)
                    .listen('ChatRoomNewMessage', function(e) {
                        THIS.selectSession({ uuid: THIS.activesession });
                    });
            });
        },
        sendChatMessage: function (obj) {
            const THIS = this;

            this.$http.post('/sendmessage', {
                message: obj.message,
                uuid_chatroom: this.activesession
            }).then(function (res) {
                if (!res.error) {
                    this.selectSession({ uuid: THIS.activesession });
                }
                else {
                    mensajes.alerta("Hubo un erro al enviar el mensaje.");
                }
            });
        },
        scrollToEnd: function() {
            var container = this.$el.querySelector(".chat-messagebox");
            container.scrollTop = container.clientHeight;
        }
    },
    created: function () {

    },
    mounted: function() {
        this.$http.get('/getchatrooms').then(function (res) {
            this.chatlists = res.data.chatrooms;

            this.selectSession({ uuid: res.data.chatrooms[0].uuid });
        });
    },
    updated: function () {
        this.scrollToEnd();
    }
});