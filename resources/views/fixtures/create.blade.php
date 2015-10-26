@extends('app')

@section('title')
    Crear fixture @parent
@stop

@section('content')
    <h3>Crear un fixture </h3>
    <hr>
    {!! Form::model( $fixture = new \Dayscore\Fixtures\Fixture, ['url' => 'fixtures','id'=>'fixtures-form'] ) !!}
    @include('fixtures.form',['submitButtonText' => "Agregar"])
    {!! Form::close() !!}
@stop