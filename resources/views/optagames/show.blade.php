@extends('app')

@section('title')
    Partido: {{$game->id}} @parent
@stop
@section('breadcrumb')
    <ol class="breadcrumb" style="margin-bottom: 15px;">
        <li><a href="/">Inicio</a></li>
        <li><a href="/tournaments">Torneos</a></li>
        <li><a href="/tournaments/{{$game->tournament->id}}">{{$game->tournament->name}}</a></li>
        <li class="active">{{$game->id}}</li>
    </ol>
@stop
@section('content')
    <h3>Partido: {{$game->id}} </h3>
    <hr>
    @if($option=="details")
        @include('optagames.details')
    @else
        @include('optagames.events')
    @endif
@stop