@extends('app')

@section('content')
    <h3>Opta Feeds</h3>
    <hr>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Tipo</th>
                <th>Torneo / Temporada</th>
                <th>Partido</th>
                <th>Creado</th>
                <th>Opciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($optafeeds as $feed)
                <tr class="optafeed-{{$feed->id}}">
                    <td>{{$feed->feedType}}</td>
                    <td>{{($feed->tournament())?$feed->tournament()->name:$feed->competitionId." / ".$feed->seasonId }}</td>
                    <td>{{$feed->gameId}}</td>
                    <td>{{$feed->created_at}}</td>
                    <td>
                        <a class="btn btn-info btn-sm" href="/optafeeds/{{$feed->id}}"><i class="fa fa-futbol-o"></i> Detalle</a>
                        <a class="btn btn-info btn-sm" href="/tournaments/sync/{{$feed->competitionId}}/{{$feed->seasonId}}"><i class="fa fa-refresh"></i> Sincronizar</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@stop

