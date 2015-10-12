@extends('app')

@section('title')
    Torneo: {{$tournament->name}} @parent
@stop
@section('breadcrumb')
    <ol class="breadcrumb" style="margin-bottom: 15px;">
        <li><a href="/">Inicio</a></li>
        <li><a href="/tournaments">Torneos</a></li>
        <li class="active">{{$tournament->name}}</li>
    </ol>
@stop
@section('content')
    <h3>Torneo: {{$tournament->name}} </h3>
    <hr>
    @if($option=="games")
        @include('tournaments.games')
    @else
        @include('tournaments.teams')
    @endif
@stop