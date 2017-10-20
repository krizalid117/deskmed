@extends('layouts.app')

@section('title', '| Servicio de consultas médicas online')

@section('stylesheets')

@endsection

@section('content')

    <?php $usuario = Auth::user()["attributes"]; ?>

    Hola, {{ $usuario["nombres"] . " " . $usuario["apellidos"] }}. <a href="{{ route('usuario.logout') }}">Salir.</a>
@endsection

@section('scripts')

@endsection