@extends('app')

@section('title')
    Editar Fixture de prueba @parent
@stop

@section('content')
    <h3>Editar un fixture de Prueba</h3>
    <hr>
    {!! Form::model($fixture,['method' => 'PATCH', 'action' => ['FixturesController@update',$fixture->id]]) !!}
    @include('fixtures.form',['submitButtonText' => "Actualizar"])
    {!! Form::close() !!}

@stop