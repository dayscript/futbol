@extends('app')

@section('title')
    Editar rol @parent
@stop

@section('content')
    <h3>Editar un rol</h3>
    <hr>
    {!! Form::model($role,['method' => 'PATCH', 'action' => ['RolesController@update',$role->id]]) !!}
    @include('users.roles.form',['submitButtonText' => "Actualizar"])
    {!! Form::close() !!}

@stop