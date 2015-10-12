    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="/tournaments/{{$tournament->id}}/teams" class="nav-link">Equipos (Opta)</a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link active">Partidos (Opta)</a>
        </li>
    </ul>
    <table class="table table-striped table-hover table-sm">
        <thead>
        <tr>
            <th>ID</th>
            <th>Fecha</th>
            <th class="text-center">Score</th>
            <th>Estado</th>
            <th>Opciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tournament->optagames as $game)
            <tr class="">
                <td>{{$game->id}}</td>
                <td>{{$game->date}}</td>
                <td class="row">
                    <div class="col-md-5 text-right">{{($game->home)?$game->home->name:""}}</div>
                    <div class="col-md-2 text-center">{{$game->home_score}} - {{$game->away_score}}</div>
                    <div class="col-md-5 text-left">{{($game->away)?$game->away->name:""}}</div>
                </td>
                <td class="{{($game->period=="FullTime")?"table-info":""}}">{{$game->period}}</td>
                <td>
                    <a class="btn btn-info btn-sm" href="/optagames/{{$game->id}}/details"><i class="fa fa-futbol-o"></i> Detalles</a>
                    @if(count($game->events)>0)
                        <a class="btn btn-info btn-sm" href="/optagames/{{$game->id}}/eventos"><i class="fa fa-futbol-o"></i> Eventos</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
