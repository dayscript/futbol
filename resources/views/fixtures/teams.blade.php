<ul class="nav nav-tabs">
    <li class="nav-item"><a href="/fixtures/{{$fixture->id}}" class="nav-link">Vista en tabla</a></li>
    <li class="nav-item"><a href="/fixtures/{{$fixture->id}}/details-block" class="nav-link">Vista en bloque</a></li>
    <li class="nav-item"><a href="#" class="nav-link active">Equipos</a></li>
</ul>
<blockquote>
    <p class="text-muted">Se muestran los equipos a continuación. Para facilidad en la asignación, se han ordenado tomando en cuenta
    los enfrentamientos de la fecha de clásicos.</p>
</blockquote>
@if($classicsRound)
    @foreach($classicsRound->matches->chunk(3) as $set)
        <div class="row">
            @foreach($set as $match)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-6">
                                    {{$match->home->name}}<br>
                                    @if($match->home->team)
                                        <form action="/fixtureteams/{{$match->home->id}}" method="post">
                                            <small>@include('fixtures.teams.simple',['team'=>$match->home])</small>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="team_id" value="">
                                            <button type="submit" class="btn btn-sm btn-danger-outline p-y-0"><i class="fa fa-close"></i></button>
                                        </form>
                                    @else
                                        <form action="/fixtureteams/{{$match->home->id}}" method="post">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            {!! Form::select('team_id',$teams->lists('name','id'),$match->home->team_id,['class'=>'team_id','placeholder'=>'Escoja un equipo...']) !!}
                                        </form>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    {{$match->away->name}}<br>
                                    @if($match->away->team)
                                        <form action="/fixtureteams/{{$match->away->id}}" method="post">
                                            <small>@include('fixtures.teams.simple',['team'=>$match->away])</small>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="team_id" value="">
                                            <button type="submit" class="btn btn-sm btn-danger-outline p-y-0"><i class="fa fa-close"></i></button>
                                        </form>
                                    @else
                                        <form action="/fixtureteams/{{$match->away->id}}" method="post">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            {!! Form::select('team_id',$teams->lists('name','id'),$match->away->team_id,['class'=>'team_id','placeholder'=>'Escoja un equipo...']) !!}
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach
@else

    @foreach($fixture->teams->chunk(3) as $set)
        <div class="row">
        @foreach($set as $team)
                <div class="card col-md-4">
                    {{$team->name}}<br>
                    @if($team->team)
                        <form action="/fixtureteams/{{$team->id}}" method="post">
                            <small>@include('fixtures.teams.simple',['team'=>$team])</small>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="team_id" value="">
                            <button type="submit" class="btn btn-sm btn-danger-outline p-y-0"><i class="fa fa-close"></i></button>
                        </form>
                    @else
                        <form action="/fixtureteams/{{$team->id}}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            {!! Form::select('team_id',$teams->lists('name','id'),$team->team_id,['class'=>'team_id','placeholder'=>'Escoja un equipo...']) !!}
                        </form>
                    @endif
                </div>
        @endforeach
        </div>
    @endforeach

@endif


@section('scripts')
    <script>
        $('.team_id').on('change',function(){
            console.log($(this));
            $(this).parent().submit();
        });
    </script>
@stop