@extends('app')

@section('title')
    Crear fixture de prueba @parent
@stop

@section('content')
    <h3>Crear un fixture de prueba</h3>
    <hr>
    {!! Form::model( $fixture = new \Dayscore\Fixtures\Fixture, ['url' => 'fixtures','id'=>'fixtures-form'] ) !!}
    @include('fixtures.form',['submitButtonText' => "Agregar"])
    {!! Form::close() !!}
@stop