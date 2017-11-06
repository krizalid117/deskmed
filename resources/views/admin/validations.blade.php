<?php
    use \App\Verificaciones;

    $validations = Verificaciones::orderBy('created_at', 'asc')->get();
?>

@extends('layouts.app')

@section('title', '| Cuenta')

@section('stylesheets')

@endsection

@section('content')

    <div class="basic-form-container">



    </div>

@endsection

@section('scripts')

@endsection