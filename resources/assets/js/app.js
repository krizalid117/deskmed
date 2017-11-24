
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
        chatlists: [
            {
                uuid: '1234',
                receiver: {
                    image: '/profilePics/default_nonbinary.png',
                    nombres: 'lala jr',
                    apellidos: 'qwerty gonzalez sad sad sadsa',
                    isDoctor: false
                },
                hora: {
                    nombre: 'Hora kinesiólogo',
                    fecha: '24-11-2017',
                    hora_inicio: '11:30',
                    hora_termino: '12:00',
                    color: '#000454'
                }
            },
            {
                uuid: 'abcd',
                receiver: {
                    image: '/profilePics/default_male.png',
                    nombres: 'Steffan',
                    apellidos: 'Kramer',
                    isDoctor: false
                },
                hora: {
                    nombre: 'Hora psiquiatra',
                    fecha: '23-11-2017',
                    hora_inicio: '17:00',
                    hora_termino: '17:45',
                    color: 'green'
                }
            }
        ]
    },
    methods: {
        selectSession: function (obj) {

            const THIS = this;

            vueSelectSession(obj, function () {
                THIS.activesession = obj.uuid;

                $(obj.el).find('.session-selected-row').show();
            });
        },
        sendChatMessage: function (obj) {

            vueSendChatMessage(obj, function () {

            });
        }
    }
});
