@extends('app')

@section('title')
    Crear equipo @parent
@stop

@section('content')
    <h3>Crear un equipo</h3>
    <hr>
    {!! Form::model( $team = new \Dayscore\Team, ['url' => 'teams','id'=>'teams-form'] ) !!}
    @include('teams.form_create',['submitButtonText' => "Agregar"])
    {!! Form::close() !!}
@stop