@extends('app')

@section('title')
    Fixture: {{$fixture->title}} @parent
@stop


@section('breadcrumb')
    <ol class="breadcrumb" style="margin-bottom: 15px;">
        <li><a href="/">Inicio</a></li>
        <li><a href="/fixtures">Fixtures</a></li>
        <li class="active">{{$fixture->title}}</li>
    </ol>
@stop
@section('content')
    <h3>Fixture: {{$fixture->title}}</h3>
    <hr>
    @if($option == "teams")
        @include('fixtures.teams')
    @elseif($option == "details-block")
            @include('fixtures.details-card')
    @else
        @include('fixtures.details')
    @endif
@stop
