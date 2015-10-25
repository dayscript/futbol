<ul class="nav nav-tabs">
    <li class="nav-item">
        <a href="#" class="nav-link active">Detalles</a>
    </li>
    <li class="nav-item">
        <a href="/fixtures/{{$fixture->id}}/teams" class="nav-link">Equipos</a>
    </li>
</ul>
<hr>
<div class="row">
    <div class="col-md-6">
        <form class="form-inline">
            <fieldset class="form-group">
                <label for="team">Mostrar detalles para:</label>
                <select name="team" id="team" class="form-control">
                    <option value="0">Escoja un equipo</option>
                    @foreach($fixture->teams as $team)
                        <option value="{{$team->id}}">{{$team->name}}</option>
                    @endforeach
                </select>
            </fieldset>
        </form>
    </div>
</div>
<table class="table table-sm">
    @foreach($fixture->rounds as $round)
        <tr>
            <td>
                {{$round->name}}
            </td>
            @foreach($round->matches as $match)
                <td>
                    <div data-home="{{$match->home?$match->home->id:''}}"
                         data-away="{{$match->away?$match->away->id:''}}"
                         class="card match text-center {{$match->home?'home_'.$match->home->id:''}}
                    {{$match->away?'away_'.$match->away->id:''}}">
                        <div class="card-block">
                            <p class="card-text">
                                <small>{{$match->home?$match->home->order:''}}
                                    - {{$match->away?$match->away->order:''}}</small>
                            </p>

                        </div>
                    </div>
                </td>
            @endforeach
        </tr>
    @endforeach
</table>
@section('scripts')
    <script>
        $('#team').on('change',function(){
            $('.card.match').removeClass('card-primary card-warning card-inverse')
            var teamid = $('#team').val();
            $('.home_'+teamid).addClass('card-primary card-inverse');
            $('.away_'+teamid).addClass('card-warning card-inverse');
        });
        $('.match').mouseover( function () {
            $('.home_'+$( this ).data( "away" ) + '.away_'+$( this ).data( "home" )).addClass('card-info');
           $( this).addClass('card-info');
        }).mouseout(function(){
            $('.home_'+$( this ).data( "away" ) + '.away_'+$( this ).data( "home" )).removeClass('card-info');
            $( this).removeClass('card-info');
        });
    </script>
@stop
