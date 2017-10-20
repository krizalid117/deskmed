<div class="header-container">
    <div class="header-content-wrapper">
        <div class="header-logo hidden-xs">
            <a href="{{ route('home') }}" title="Deskmed">
                <span class="dm-logo dm-logo-small"></span>
            </a>
        </div>
        <div class="header-search">
            <div class="input-group">
                <input type="text" class="form-control txt-header-search" placeholder="Buscar..." aria-label="Buscar..." aria-describedby="btn-search">
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
                <a href="#" class="header-a-profile" title="Configuraciones de perfil">
                    <?php

                        $profilePic = "default_nonbinary.png";

                        if (File::exists(public_path("profilePics/" . $usuario["id"] . ".jpg"))) {
                            $profilePic = $usuario["id"] . ".jpg";
                        }
                        else {
                            if (!is_null($usuario["id_sexo"])) {
                                if ($usuario["id_sexo"] === 1) { //M
                                    $profilePic = "default_male.png";
                                }
                                else if ($usuario["id_sexo"] === 2) { //F
                                    $profilePic = "default_female.png";
                                }
                            }
                        }

                    ?>
                    <img src="{{ URL::to("profilePics/$profilePic") }}" alt="Perfil" aria-label="Perfil">
                </a>
            </div>
        </div>
    </div>
</div>




























