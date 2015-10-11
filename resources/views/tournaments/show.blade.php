@extends('app')

@section('title')
    Torneo: {{$tournament->name}} @parent
@stop

@section('content')
    <h3>Detalles de Torneo</h3>
    <hr>
    Opta Games: {{$tournament->optagames->count()}}<br>
    Opta Teams: {{$tournament->optateams->count()}}
@stop