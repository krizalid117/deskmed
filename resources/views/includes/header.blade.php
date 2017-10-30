<?php
    use \App\Http\Controllers\UsuarioController;

    $currentView = Route::current()->getName();
    $profilePic = UsuarioController::getProfilePic($usuario["profile_pic_path"], $usuario["id_sexo"]);
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
                <input type="search" class="form-control txt-header-search" placeholder="Buscar..." aria-label="Buscar..." aria-describedby="btn-search">
                <span class="input-group-addon glyphicon glyphicon-search" id="btn-search"></span>
            </div>
        </div>
        <div class="header-user-ui">
            <div class="header-user-notifications">
                <a class="header-a-notif" href="#" title="Notificaciones">
                    <span class="header-notifications-count">1</span>
                    <img src="{{ URL::to('img/notification.png') }}" alt="Notificaciones" aria-label="Notificaciones">
                </a>
            </div>
            <div class="header-user-settings">
                <a href="#" class="header-a-profile" title="Mi cuenta">
                    <img src="{{ URL::to("profilePics/$profilePic") }}" alt="Cuenta" aria-label="Cuenta">
                </a>
                <div class="profile-row profile-menu"></div>
                <div class="profile-window profile-menu">
                    <ul>
                        @if ($usuario["id_tipo_usuario"] === 2)
                        <li class="profile-setting profile-setting-career">
                            <img src="{{ URL::to('img/doc.png') }}" alt="Perfil profesional">
                            <span>Perfil profesional</span>
                        </li>
                        @endif
                        @if ($usuario["id_tipo_usuario"] === 3)
                        <li class="profile-setting profile-setting-ficha">
                            <img src="{{ URL::to('img/ficha.png') }}" alt="Ficha de salud">
                            <span>Ficha de salud</span>
                        </li>
                        @endif
                        <li class="profile-setting profile-setting-config">
                            <img src="{{ URL::to('img/settings.png') }}" alt="Cuenta">
                            <span>Cuenta</span>
                        </li>
                        <li class="profile-setting profile-setting-logout">
                            <img src="{{ URL::to('img/logout.png') }}" alt="Salir">
                            <span>Cerrar sesión</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>




























