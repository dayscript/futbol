@extends('app')

@section('title')
    Crear usuario @parent
@stop

@section('content')
    <h3>Crear un usuario</h3>
    <hr>
    {!! Form::model( $user = new \Dayscore\User, ['url' => 'users','id'=>'users-form'] ) !!}
    @include('users.form',['submitButtonText' => "Agregar"])
    {!! Form::close() !!}
@stop