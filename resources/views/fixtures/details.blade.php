<ul class="nav nav-tabs">
    <li class="nav-item"><a href="#" class="nav-link active">Vista en tabla</a></li>
    <li class="nav-item"><a href="/fixtures/{{$fixture->id}}/details-block" class="nav-link">Vista en bloque</a></li>
    <li class="nav-item"><a href="/fixtures/{{$fixture->id}}/teams" class="nav-link">Equipos</a></li>
</ul>
<hr>
<blockquote>
    <p><b>Notas:</b>
    <ul>
        <li>Puede hacer click sobre un número o escudo de equipo para resaltar los partidos de ese equipo en todas las fechas.
            <span class="label label-primary">Local</span> <span class="label label-warning">Visitante</span>
        </li>
        <li>Para las llaves correspondientes a la fecha de clásicos, el encuentro se repite cambiando la localía.
            Al pasar el mouse sobre estos encuentros, se resalta la llave complementaria.
        </li>
        <li>
            Puede invertir la localía de un encuentro haciendo click sobre la palabra "vs" de cada partido.
            Estos cambios son temporales y se pierden tan pronto se recarga la página.
        </li>
    </ul>
    </p>
</blockquote>
<div class="card resume" style="display: none;">
    <div class="card-block">
        Equipo seleccionado: <span class="label label-info selected_team"></span>
        <span class="label label-danger"><a href="#" id="deselect"><i class="fa fa-times"></i> Cancelar</a></span>
        <br>
        Partidos de local: <span class="label label-primary home_games">0</span> - <span class="home_rounds"></span><br>
        Partidos de visitante: <span class="label label-warning away_games">0</span> - <span class="away_rounds"></span>
    </div>
</div>
<table class="table table-sm">
    @foreach($fixture->rounds as $round)
        <tr class="round">
            <td>{{$round->name}}</td>
            @foreach($round->matches as $match)
                <td>
                    <div data-home="{{$match->home?$match->home->id:''}}"
                         data-away="{{$match->away?$match->away->id:''}}"
                         data-round="{{$round->name}}"
                         class="card match text-center
                         {{$match->home?'home_'.$match->home->id:''}}
                         {{$match->away?'away_'.$match->away->id:''}}"
                            id="match_{{$match->id}}">
                        <div class="card-text">
                            <div class="team round"
                                 data-name="{{$match->home?($match->home->team?$match->home->team->name:$match->home->name):''}}"
                                 data-id="{{$match->home?$match->home->id:''}}"
                                    id="match_{{$match->id}}_home">
                                <small>@include('fixtures.teams.icon',['team'=>$match->home])</small>
                            </div>
                            <a href="#" class="card-text switch_teams" data-match="{{$match->id}}">vs</a>
                            <div class="team round"
                                 data-name="{{$match->away?($match->away->team?$match->away->team->name:$match->away->name):''}}"
                                 data-id="{{$match->away?$match->away->id:''}}"
                                 id="match_{{$match->id}}_away">
                                <small>@include('fixtures.teams.icon',['team'=>$match->away])</small>
                            </div>
                        </div>
                    </div>
                </td>
            @endforeach
        </tr>
    @endforeach
</table>
@section('scripts')
    <script>
        $('#deselect').on('click',function(e){
            e.preventDefault();
            $('.resume').slideUp();
            $('.card.match').removeClass('card-primary card-warning card-inverse');
        });
        $('.team').on('click', function () {
            var teamid = $(this).data('id');
            var teamname = $(this).data('name');
            $('.resume').slideDown();
            $('.resume .selected_team')[0].innerHTML = teamname;
            $('.resume .home_games')[0].innerHTML = $('.home_' + teamid).length;
            $('.resume .away_games')[0].innerHTML = $('.away_' + teamid).length;
            $('.resume .home_rounds')[0].innerHTML = '';
            $('.resume .away_rounds')[0].innerHTML = '';
            $('.home_' + teamid).each(function (index) {
                $('.resume .home_rounds')[0].innerHTML += ' <span class="label label-pill label-primary">'+$(this).data('round')+'</span>';
            });
            $('.away_' + teamid).each(function (index) {
                $('.resume .away_rounds')[0].innerHTML += ' <span class="label label-pill label-warning">'+$(this).data('round')+'</span>';
            });
            $('.card.match').removeClass('card-primary card-warning card-inverse')
            $('.home_' + teamid).addClass('card-primary card-inverse');
            $('.away_' + teamid).addClass('card-warning card-inverse');
        });
        $('.match').mouseover(function () {
            $('.home_' + $(this).data("away") + '.away_' + $(this).data("home")).addClass('card-info');
            $(this).addClass('card-info');
        }).mouseout(function () {
            $('.home_' + $(this).data("away") + '.away_' + $(this).data("home")).removeClass('card-info');
            $(this).removeClass('card-info');
        });
        $('.switch_teams').on('click',function(e){
            e.preventDefault();
            var matchid = $(this).data("match");
            var temp = $('#match_'+matchid+'_home')[0].innerHTML;
            $('#match_'+matchid+'_home')[0].innerHTML = $('#match_'+matchid+'_away')[0].innerHTML;
            $('#match_'+matchid+'_away')[0].innerHTML = temp;

            var homeid = $('#match_'+matchid+'_home').data('id');
            var awayid = $('#match_'+matchid+'_away').data('id');

            $('#match_'+matchid+'_home').data('id',awayid);
            $('#match_'+matchid+'_away').data('id',homeid);

            temp = $('#match_'+matchid+'_home').data('name');
            $('#match_'+matchid+'_home').data('name',$('#match_'+matchid+'_away').data('name'));
            $('#match_'+matchid+'_away').data('name',temp);

            temp = $('#match_'+matchid).data('home');
            $('#match_'+matchid).data('home',$('#match_'+matchid).data('away'));
            $('#match_'+matchid).data('away',temp);

            $('#match_'+matchid).removeClass('home_'+homeid).addClass('home_'+awayid);
            $('#match_'+matchid).removeClass('away_'+awayid).addClass('away_'+homeid);
        });
    </script>
@stop
