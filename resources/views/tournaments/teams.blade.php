    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#" class="nav-link active">Equipos (Opta)</a>
        </li>
        <li class="nav-item">
            <a href="/tournaments/{{$tournament->id}}/games" class="nav-link">Partidos (Opta)</a>
        </li>
    </ul>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Código</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tournament->optateams as $team)
            <tr>
                <td>{{$team->id}}</td>
                <td><img src="{{$team->image()}}" alt="{{$team->name}}"> {{$team->name}}</td>
                <td>{{$team->code}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
