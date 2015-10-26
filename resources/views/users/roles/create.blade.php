@extends('app')

@section('title')
    Crear rol @parent
@stop

@section('content')
    <h3>Crear un rol</h3>
    <hr>
    {!! Form::model( $role = new \Dayscore\Role, ['url' => 'roles','id'=>'roles-form'] ) !!}
    @include('users.roles.form',['submitButtonText' => "Agregar"])
    {!! Form::close() !!}
@stop