<?php
    use \App\Http\Controllers\UsuarioController;
    use \Illuminate\Support\Facades\Auth;

    $currentView = Route::current()->getName();
    $profilePic = UsuarioController::getProfilePic($usuario->profile_pic_path, $usuario->id_sexo);

?>

<div class="header-container">
    <div class="header-content-wrapper">
        <div class="header-logo hidden-xs">
            <a href="{{ route('home') }}" title="Deskmed">
                <span class="dm-logo dm-logo-small"></span>
            </a>
        </div>
        <div class="header-search">
            <div class="input-group">
                <input type="search" class="form-control txt-header-search" placeholder="Buscar..." aria-label="Buscar..." aria-describedby="btn-search" value="{{ ($currentView === "search" ? $keyword : "") }}">
                <span class="input-group-addon glyphicon glyphicon-search" id="btn-search"></span>
            </div>
        </div>
        <div class="header-user-ui">
            <div class="header-user-notifications">
                <a class="header-a-notif" href="#" title="Notificaciones">
                    <span class="header-notifications-count">{{ (count(Auth::user()->unreadNotifications) > 0 ? count(Auth::user()->unreadNotifications) : "") }}</span>
                    <img src="{{ URL::to('img/notification.png') }}" alt="Notificaciones" aria-label="Notificaciones">
                </a>
                <div class="profile-row profile-row-n profile-menu profile-menu-n"></div>
                <div class="profile-window profile-window-n profile-menu profile-menu-n">
                    <ul>
                        @include('layouts.partials.all_notifications')
                    </ul>
                </div>
            </div>
            <div class="header-user-settings">
                <a href="#" class="header-a-profile" title="{{ Auth::user()->nombres . " " . Auth::user()->apellidos }}">
                    <img src="{{ URL::to("profilePics/$profilePic") }}" alt="Cuenta" aria-label="Cuenta">
                </a>
                <div class="profile-row profile-row-p profile-menu profile-menu-p"></div>
                <div class="profile-window profile-window-p profile-menu profile-menu-p">
                    <ul>
                        @if ($usuario->id_tipo_usuario === 1)
                        <li class="profile-setting per profile-setting-validations">
                            <img src="{{ URL::to('img/validations.png') }}" style="filter: invert(100%);">
                            <span>Solicitudes de validación</span>
                        </li>
                        <li class="profile-setting per profile-setting-subs">
                            <img src="{{ URL::to('img/dollar.png') }}">
                            <span>Subscripciones</span>
                        </li>
                        @endif
                        @if ($usuario->id_tipo_usuario === 2)
                        <li class="profile-setting per profile-setting-sub" title="{{ (is_null($sub) ? "¡Subscríbase para configurar horas y tener consultas médicas en línea!" : "Plan: {$sub->nombre_plan}") }}">
                            <img src="{{ (is_null($sub) ? URL::to('img/x.png') : URL::to('img/check.png')) }}">
                            <span>
                                {{ (is_null($sub) ? "No subscrito" : "Subscrito") }}
                            </span>
                        </li>
                        <li class="profile-setting per profile-setting-career">
                            <img src="{{ URL::to('img/doc.png') }}">
                            <span>Perfil profesional</span>
                        </li>
                        @endif
                        @if ($usuario->id_tipo_usuario === 3)
                        <li class="profile-setting per profile-setting-ficha">
                            <img src="{{ URL::to('img/ficha.png') }}">
                            <span>Ficha de salud</span>
                        </li>
                        @endif
                        <li class="profile-setting per profile-setting-config">
                            <img src="{{ URL::to('img/settings.png') }}">
                            <span>Cuenta</span>
                        </li>
                        <li class="profile-setting per profile-setting-logout">
                            <img src="{{ URL::to('img/logout.png') }}">
                            <span>Cerrar sesión</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>




























