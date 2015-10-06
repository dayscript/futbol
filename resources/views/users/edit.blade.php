@extends('app')

@section('title')
    Editar usuario @parent
@stop

@section('content')
    <h3>Editar un usuario</h3>
    <hr>
    {!! Form::model($user,['method' => 'PATCH', 'action' => ['UsersController@update',$user->id]]) !!}
    @include('users.form',['submitButtonText' => "Actualizar"])
    {!! Form::close() !!}

@stop