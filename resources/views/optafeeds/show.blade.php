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
        <th>Local</th>
        <th>Visitante</th>
        <th>Ciudad</th>
        <th>Estadio</th>
        </thead>
        <tbody>
        {{--@foreach($content["MatchData"] as $match)--}}
            {{--<tr>--}}
                {{--<td>--}}
                    {{--{{$match["MatchInfo"]["Date"]}}--}}
                    {{--<pre>{{print_r(($match))}}</pre>--}}
                {{--</td>--}}
                {{--<td>{{$match["TeamData"][0]["@attributes"]["TeamRef"]}}</td>--}}
                {{--<td>{{$match["TeamData"][1]["@attributes"]["TeamRef"]}}</td>--}}
                {{--<td>{{$match["Stat"][1]}}</td>--}}
                {{--<td>{{$match["Stat"][0]}}</td>--}}
            {{--</tr>--}}
        {{--@endforeach--}}
        </tbody>
    </table>
@stop