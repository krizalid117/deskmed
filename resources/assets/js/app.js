
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

Vue.component('chat-room', require('./components/ChatRoom.vue'));
Vue.component('message-composer', require('./components/MessageComposer.vue'));
Vue.component('chat-log', require('./components/ChatLog.vue'));
Vue.component('chat-message', require('./components/ChatMessage.vue'));

const app = new Vue({
    el: '#app',
    data: {
        messages: [
            {
                message: 'Hola...',
                user: 'Doctor',
                isSender: true,
                image: '/profilePics/default_male.png',
                time: '12:30PM'
            },
            {
                message: 'Buenas!!!1',
                user: 'Paciente',
                isSender: false,
                image: '/profilePics/default_female.png',
                time: '12:31PM'
            }
        ]
    },
    methods: {
        // sendChatMessage() {
        //
        // }
    }
});
