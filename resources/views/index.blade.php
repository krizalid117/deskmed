@extends('layouts.app')

@section('title', '| Servicio de consultas médicas online')

@section('stylesheets')

@endsection

@section('content')
    <?php
//        use Illuminate\Support\Facades\Auth;

        $usuario = Auth::user();
    ?>

    Hola, {{ $usuario["attributes"]["nombres"] . " " . $usuario["attributes"]["apellidos"] }}. <a href="{{ route('usuario.logout') }}">Salir.</a>

    contentttt
@endsection

@section('scripts')

@endsection