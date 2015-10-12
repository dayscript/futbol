    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#" class="nav-link active">Detalles</a>
        </li>
        @if(count($game->events)>0)
        <li class="nav-item">
            <a href="/optagames/{{$game->id}}/events" class="nav-link">Eventos</a>
        </li>
        @endif
    </ul>
    <div class="row">
        <div class="col-md-6 text-right"><strong>Local</strong></div>
        <div class="col-md-6">{{$game->home->name}} ({{$game->home_score}})</div>
    </div>
    <div class="row">
        <div class="col-md-6 text-right"><strong>Visitante</strong></div>
        <div class="col-md-6">{{$game->away->name}} ({{$game->away_score}})</div>
    </div>
    <div class="row">
        <div class="col-md-6 text-right"><strong>Ciudad</strong></div>
        <div class="col-md-6">{{$game->city}}</div>
    </div>
    <div class="row">
        <div class="col-md-6 text-right"><strong>Estadio</strong></div>
        <div class="col-md-6">{{$game->venue->name}}</div>
    </div>
    <div class="row">
        <div class="col-md-6 text-right"><strong>Fecha</strong></div>
        <div class="col-md-6">{{$game->date}}</div>
    </div>
    <div class="row">
        <div class="col-md-6 text-right"><strong>Periodo</strong></div>
        <div class="col-md-6">{{$game->period}}</div>
    </div>
    <div class="row">
        <div class="col-md-6 text-right"><strong>Tipo de encuentro</strong></div>
        <div class="col-md-6">{{$game->match_type}}</div>
    </div>
    <div class="row">
        <div class="col-md-6 text-right"><strong>Torneo</strong></div>
        <div class="col-md-6">{{$game->tournament->name}}</div>
    </div>
