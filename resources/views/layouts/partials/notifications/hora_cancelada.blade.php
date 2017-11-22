<a class="notification-item {{ (is_null($notif->read_at) ? "notification-item-unread" : "notification-item-read") }}" href="#" data-uuid="{{ $notif->id }}" onclick="verHora(false, '{{ $notif->data["hora"]["id"] }}', '{{ $notif->id }}');">
    <li class="profile-setting not">
        <div style="padding-left: 30px;">Una de tus horas ha sido cancelada.</div>
    </li>
</a>