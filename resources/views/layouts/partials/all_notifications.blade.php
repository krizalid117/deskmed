@if(isset($unreadNotifCount))
    <li class="hidden">
        <input type="hidden" value="{{ $unreadNotifCount }}" id="unread-notif-count">
    </li>
@endif

@if(count(Auth::user()->notifications) > 0)
    @foreach(Auth::user()->notifications as $notif)
        @include("layouts.partials.notifications." . snake_case(class_basename($notif->type)))
    @endforeach
@else
    <li class="profile-setting no-not">
        No tiene nuevas notificaciones
    </li>
@endif