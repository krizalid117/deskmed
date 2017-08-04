@extends('layouts.master')

@section('title')
    Deskmed - Servicio de consultas médicas online
@endsection

@section('local_head')

@endsection

@section('content')
    <?php
        use Illuminate\Support\Facades\Auth;

        $usuario = Auth::user()["attributes"];
    ?>

    Hola, {{ $usuario["nombres"] . " " . $usuario["apellidos"] }}. <a href="{{ route('usuario.logout') }}">Salir.</a>
@endsection