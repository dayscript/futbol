    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="/tournaments/{{$tournament->id}}/teams" class="nav-link">Equipos (Opta)</a>
        </li>
        <li class="nav-item">
            <a href="/tournaments/{{$tournament->id}}/games" class="nav-link">Partidos (Opta)</a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link active">Jugadores (Opta)</a>
        </li>
    </ul>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tournament->optaplayers as $player)
            <tr>
                <td>{{$player->id}}</td>
                <td><img height="60" width="40" src="{{$player->image($player->pivot->team_id,"40x60")}}" alt="{{$player->name}}"> {{$player->name}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
