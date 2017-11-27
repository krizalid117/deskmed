<a class="notification-item {{ (is_null($notif->read_at) ? "notification-item-unread" : "notification-item-read") }}" href="#" data-uuid="{{ $notif->id }}" onclick="window.location = '{{ route('user.mainchat', $notif->data["chat_room"]["uuid"]) }}';">
    <li class="profile-setting not">
        <?php $medico = \App\Usuario::find(\App\HoraMedica::find($notif->data["chat_room"]["hora_id"])->id_medico); ?>
        <div class="not-img">
            <img class="img-circle" src="{{ $medico->getProfileImage() }}">
        </div>
        <div class="not-text">
            ¡{{ $medico->nombres }} ha iniciado una sala de chat para la hora que tenías reservada!
        </div>
    </li>
</a>