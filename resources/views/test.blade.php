@extends('layouts.app')

@section('title', '| Test')

@section('stylesheets')
    <style type="text/css">

    </style>
@endsection

@section('content')
    <?php

    echo "<pre>";

    print_r($results);

    echo "</pre>";

    ?>
@endsection

@section('scripts')
    <script type="text/javascript">

    </script>
@endsection