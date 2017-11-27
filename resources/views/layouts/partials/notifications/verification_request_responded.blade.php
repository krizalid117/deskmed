<a class="notification-item {{ (is_null($notif->read_at) ? "notification-item-unread" : "notification-item-read") }}" href="#" onclick="checkVerificationRequest('{{ $notif->data["solicitud"]["id"] }}', '{{ $notif->id }}');">
    <li class="profile-setting not">
        <div class="not-img"></div>
        <div class="not-text">
            Tu&nbsp;solicitud de verificación&nbsp;ha sido cursada.
        </div>
    </li>
</a>