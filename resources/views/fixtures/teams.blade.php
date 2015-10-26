<ul class="nav nav-tabs">
    <li class="nav-item"><a href="/fixtures/{{$fixture->id}}" class="nav-link">Vista en tabla</a></li>
    <li class="nav-item"><a href="/fixtures/{{$fixture->id}}/details-block" class="nav-link">Vista en bloque</a></li>
    <li class="nav-item"><a href="#" class="nav-link active">Equipos</a></li>
</ul>
<blockquote>
    <p class="text-muted">Se muestran los equipos a continuación. Para facilidad en la asignación, se han ordenado tomando en cuenta
    los enfrentamientos de la fecha de clásicos.</p>
</blockquote>
<div class="row">
@foreach($classicsRound->matches as $match)
        <div class="col-md-4">
            <div class="card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-md-6">
                            {{$match->home->name}}
                            <form action="/fixtureteams/{{$match->home->id}}" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                {!! Form::select('team_id',$teams->lists('name','id'),$match->home->team_id,['class'=>'team_id','placeholder'=>'Escoja un equipo...']) !!}
                            </form>
                        </div>
                        <div class="col-md-6">
                            {{$match->away->name}}
                            <form action="/fixtureteams/{{$match->away->id}}" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                {!! Form::select('team_id',$teams->lists('name','id'),$match->away->team_id,['class'=>'team_id','placeholder'=>'Escoja un equipo...']) !!}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endforeach
</div>
@section('scripts')
    <script>
        $('.team_id').on('change',function(){
            console.log($(this));
            $(this).parent().submit();
        });
    </script>
@stop