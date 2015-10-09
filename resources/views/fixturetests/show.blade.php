@extends('app')

@section('title')
    Fixture: {{$fixture->title}} @parent
@stop

@section('content')
    <h3>Detalles de fixture de prueba</h3>
    <hr>
    @if($option == "teams")
        @foreach($fixture->teams as $team)
            <div class="row">
                <div class="col-md-12">{{$team->name}}</div>
            </div>
        @endforeach
    @else
        Fixture:{{$fixture}}<br>
        Opcion: {{ $option }}<br>
    @endif
@stop