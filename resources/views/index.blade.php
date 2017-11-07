@extends('layouts.app')

@section('title', '| Servicio de consultas médicas online')

@section('stylesheets')

@endsection

@section('content')
    <div class="dm-title1">
        ¡Bienvenid{{ ($usuario->id_sexo === 2) ? "a" : "o" }} a Deskmed!
        <img src="{{ URL::to('img/deskmed_full.png') }}" alt="Logo" style="margin-top: 20px;">
    </div>
@endsection

@section('scripts')

@endsection