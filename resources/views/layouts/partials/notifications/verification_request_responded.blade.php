<a class="notification-item {{ (is_null($notif->read_at) ? "notification-item-unread" : "notification-item-read") }}" href="#" onclick="checkVerificationRequest('{{ $notif->data["solicitud"]["id"] }}', '{{ $notif->id }}');">
    <li class="profile-setting not">
        Tu&nbsp;solicitud de verificación&nbsp;ha sido cursada.
    </li>
</a>