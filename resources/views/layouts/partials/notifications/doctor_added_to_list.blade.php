<a class="notification-item {{ (is_null($notif->read_at) ? "notification-item-unread" : "notification-item-read") }}" href="{{ route('patients.profile', [$notif->data["patient"]["id"], $notif->id]) }}" data-uuid="{{ $notif->id }}">
    <li class="profile-setting not">
        <img class="img-circle" src="/profilePics/{{ \App\Http\Controllers\UsuarioController::getProfilePic($notif->data["patient"]["profile_pic_path"], $notif->data["patient"]["id_sexo"]) }}">
        ¡Un paciente te ha añadido a su lista!
    </li>
</a>