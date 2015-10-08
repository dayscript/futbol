@extends('app')

@section('title')
    Editar Fixture de prueba @parent
@stop

@section('content')
    <h3>Editar un fixture de Prueba</h3>
    <hr>
    {!! Form::model($fixture,['method' => 'PATCH', 'action' => ['FixtureTestsController@update',$fixture->id]]) !!}
    @include('fixturetests.form',['submitButtonText' => "Actualizar"])
    {!! Form::close() !!}

@stop