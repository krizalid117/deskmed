<template lang="html">
    <div class="chat-session" @click="selectSession" :data-uuid="session.uuid">
        <div class="session-img">
            <img class="img-circle" :src="session.receiver.image">
        </div>
        <div class="session-content">
            <div class="session-hora-nombre" :style="{ 'color': session.hora.color }">{{ session.hora.nombre }}</div>
            <div class="sesion-fecha">{{ session.hora.fecha + ' (' + session.hora.hora_inicio + ' - ' + session.hora.hora_termino + ')' }}</div>
            <div class="hora-receiver">{{ session.receiver.isDoctor ? 'Profesional' : 'Paciente' }}{{ ': ' +  session.receiver.nombres  + ' ' + session.receiver.apellidos }}</div>
        </div>
        <span class="glyphicon glyphicon-chevron-right session-selected-row"></span>
    </div>
</template>

<script>
    export default {
        props: ['session'],
        data() {
            return {

            }
        },
        methods: {
            selectSession: function () {
                $('.session-selected-row').hide();

                const el = this.$el;

                this.$emit('selectsession', {
                    el: el,
                    uuid: $(el).data('uuid')
                })
            }
        }
    }
</script>

<style lang="css">
    .chat-session {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        align-items: center;
        justify-content: flex-start;

        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;

        font-size: .9em;

        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;

        cursor: pointer;
        padding: 5px 10px 5px 5px;
        position: relative;
    }

    .chat-session:not(:last-child) {
        border-bottom: 1px solid #f1f1f1;
    }

    .chat-session:hover {
        background-color: #f1f1f1;
    }

    .session-selected-row {
        display: none;

        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
    }

    .session-img {
        flex: 0 0 25%;
        padding: 0 10px;
    }

    .session-img img {
        width: 35px;
        height: 35px;
        padding: 2px;

        background-color: var(--secondary-background-color);
    }

    .session-content {
        flex: 0 0 75%;
        width: 75%;
        padding: 5px 5px 5px 0;
    }
    
    .session-content > div {
        max-width: 100%;
        text-overflow: ellipsis;

        white-space: nowrap;
        overflow: hidden;
    }

    .session-hora-nombre {
        font-weight: bold;
    }

    .sesion-fecha {

    }

    .hora-receiver {
        font-size: .8em;
    }
 </style>