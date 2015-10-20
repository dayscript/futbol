@extends('app')

@section('content')
    <h3>Opta Feeds</h3>
    <hr>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
            <tr>
                <th>Tipo <br>
                    <select name="type" id="type" onchange="document.location.href='/optafeeds/?tournament={{$tournament}}&type='+this.options[this.selectedIndex].value;">
                        <option {{$type==""?"selected":""}} value="">Todos</option>
                        <option {{$type=="F1"?"selected":""}} value="F1">Fixtures & results</option>
                        <option {{$type=="F26"?"selected":""}} value="F26">Live Scores</option>
                        <option {{$type=="F13"?"selected":""}} value="F13">Commentary Feed</option>
                        <option {{$type=="F40"?"selected":""}} value="F40">Squads Feed</option>
                    </select>
                </th>
                <th>Torneo <br>
                    <select name="tournament" id="tournament" onchange="document.location.href='/optafeeds/?type={{$type}}&tournament='+this.options[this.selectedIndex].value;">
                        <option {{$tournament==""?"selected":""}} value="">Todos</option>
                        @foreach($tournaments as $tour)
                            <option {{$tournament==$tour->id?"selected":""}} value="{{$tour->id}}">{{$tour->name}}</option>
                        @endforeach
                    </select>
                </th>
                <th>Recibido / Procesado</th>
                <th>Opciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($optafeeds as $feed)
                <tr class="optafeed-{{$feed->id}}">
                    <td>{{$feed->type()}}</td>
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
                            <a href="/tournaments/updatewidget/{{$feed->tournament()->id}}" class="btn btn-info btn-sm">Widget</a>
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

