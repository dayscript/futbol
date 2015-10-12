    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="/optagames/{{$game->id}}/details" class="nav-link">Detalles</a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link active">Eventos</a>
        </li>
    </ul>
    <table class="table table-striped table-hover table-sm">
        <thead>
        <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Detalle</th>
            <th class="text-right">Minuto</th>
            <th>Periodo</th>
        </tr>
        </thead>
        <tbody>
        @foreach($game->events as $event)
            <tr class="">
                <td>{{$event->id}}</td>
                <td>{{$event->type}}</td>
                <td>
                    @if($event->type == "comment")
                        {{$event->comment}}
                    @else
                        {{($event->player)?$event->player->first_name. " ". $event->player->last_name:""}}
                        @if($event->team)
                            - <img src="{{$event->team->image("20")}}" alt="{{$event->team->name}}">
                            {{$event->team->name}}
                        @endif
                    @endif
                </td>
                <td class="text-right">{{$event->minute}}</td>
                <td>{{$event->period}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
