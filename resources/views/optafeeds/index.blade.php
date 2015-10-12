@extends('app')

@section('content')
    <h3>Opta Feeds</h3>
    <hr>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Tipo</th>
                <th>Torneo</th>
                <th>Recibido / Procesado</th>
                <th>Opciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($optafeeds as $feed)
                <tr class="optafeed-{{$feed->id}}">
                    <td>{{$feed->feedType}}</td>
                    <td>{{($feed->tournament())?$feed->tournament()->name:$feed->competitionId." / ".$feed->seasonId }}
                        <br>
                        <small><strong>ID:</strong> {{$feed->tournament()->id}} <strong>OPTA:</strong> {{$feed->competitionId}} / {{$feed->seasonId}}</small>
                    </td>
                    <td>
                        {{$feed->created_at}}<br>
                        <small>{{$feed->processed}}</small>
                    </td>
                    <td>
                        <a class="btn btn-info btn-sm" href="/optafeeds/{{$feed->id}}"><i class="fa fa-futbol-o"></i>
                            Detalle</a>
                        <a class="btn btn-info btn-sm"
                           href="/tournaments/sync/{{$feed->competitionId}}/{{$feed->seasonId}}"><i
                                    class="fa fa-refresh"></i> Sincronizar</a>
                        @if($feed->tournament())
                            <a class="btn btn-info btn-sm" href="/optafeeds/{{$feed->id}}/process"><i
                                        class="fa fa-floppy-o"></i> Procesar</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr class="table-active">
                <td colspan="5" class="text-right">{!! $optafeeds->render() !!}</td>
            </tr>
            </tbody>

        </table>
    </div>
@stop

