@extends('layouts.master')

@section('title')
    Deskmed - Servicio de consultas médicas online
@endsection

@section('local_head')

@endsection

@section('content')
    <a href="{{ route('usuario.logout') }}">Salir.</a>
@endsection