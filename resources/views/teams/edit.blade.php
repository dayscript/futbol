@extends('app')

@section('title')
    Editar equipo @parent
@stop

@section('content')
    <h3>Editar equipo</h3>
    <hr>
    {!! Form::model($team,['method' => 'PATCH', 'action' => ['TeamsController@update',$team->id]]) !!}
    @include('teams.form',['submitButtonText' => "Actualizar"])
    {!! Form::close() !!}

@stop