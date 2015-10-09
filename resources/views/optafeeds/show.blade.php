@extends('app')

@section('title')
    Opta Feed: {{$optafeed->feedType}} @parent
@stop

@section('content')
    <h3>Detalles de feed de opta</h3>
    <hr>
    <table class="table table-striped table-hover table-responsive">
        <thead>
            <th>Fecha</th>
        <th>Ciudad</th>
        <th>Estadio</th>
        </thead>
        <tbody>
            @foreach($content["SoccerDocument"]["MatchData"] as $match)
                <tr>
                    <td>{{print_r(($match))}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop