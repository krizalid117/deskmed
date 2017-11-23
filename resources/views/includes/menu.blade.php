<?php
    $currentView = Route::current()->getName();

    $labelTratamientos = ($usuario->id_tipo_usuario === 2 ? "Tratamientos" : ($usuario->id_tipo_usuario === 3 ? "Mis tratamientos" : "Tratamientos de pacientes"));
?>

<div class="side-menu-container">
    <div class="side-menu-minified">
        <div class="side-menu-collapser">
            <button class="c-hamburger c-hamburger--htx">
                <span>toggle menu</span>
            </button>
        </div>
        <ul class="side-menu-item-container">
            <li class="side-menu-item menu-home {{ ($currentView === "home" ? "side-menu-selected" : "") }}" title="Inicio">
                <img class="img-menu" src="{{ URL::to('img/home.png') }}" alt="home" data-title="Inicio">
                <div class="content-menu">Inicio</div>
            </li>
            @if ($usuario->id_tipo_usuario === 2) <!-- Doctor -->
                <li class="side-menu-item menu-pacientes {{ ($currentView === "pacientes" ? "side-menu-selected" : "") }}" title="Mis pacientes">
                    <img class="img-menu" src="{{ URL::to('img/patient.png') }}" alt="pacientes" data-title="Mis pacientes">
                    <div class="content-menu">Mis pacientes</div>
                </li>
            @elseif ($usuario->id_tipo_usuario === 3) <!-- Paciente -->
                <li class="side-menu-item menu-doctores {{ ($currentView === "paciente.doctores" ? "side-menu-selected" : "") }}" title="Mis doctores">
                    <img class="img-menu" src="{{ URL::to('img/doc.png') }}" alt="doctores" data-title="Mis doctores">
                    <div class="content-menu">Mis profesionales de la salud</div>
                </li>
            @endif
            <li class="side-menu-item menu-tratamientos {{ ($currentView === "tratamientos" ? "side-menu-selected" : "") }}" title="{{ $labelTratamientos }}">
                <img class="img-menu" src="{{ URL::to('img/tablets.png') }}" alt="tratamientos" data-title="{{ $labelTratamientos }}">
                <div class="content-menu">{{ $labelTratamientos }}</div>
            </li>
            <li class="side-menu-item menu-agenda {{ (isset(explode(".", $currentView)[1]) && explode(".", $currentView)[1] === "agenda" ? "side-menu-selected" : "") }}" title="Agenda">
                <img class="img-menu" src="{{ URL::to('img/notepad.png') }}" alt="agenda" data-title="Agenda">
                <div class="content-menu">Agenda</div>
            </li>

            <!-- Chat -->

            <li class="side-menu-item menu-chat {{ ($currentView === "user.mainchat" ? "side-menu-selected" : "") }}" title="Mensajes">
                <div class="menu-chat-messages-container">
                    <span class="menu-chat-messages">1</span>
                    <img class="img-menu" src="{{ URL::to('img/chat.png') }}" alt="mensajes" data-title="Mensajes">
                </div>
                <div class="content-menu">Mensajes</div>
            </li>
        </ul>
    </div>
</div>