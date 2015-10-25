

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a href="/fixtures/{{$fixture->id}}" class="nav-link">Detalles</a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link active">Equipos</a>
    </li>
</ul>
@foreach($fixture->teams as $team)
    <div class="row">
        <div class="col-md-12">{{$team->name}}</div>
    </div>
@endforeach
