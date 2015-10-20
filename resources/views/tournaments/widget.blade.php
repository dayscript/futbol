<div class="round_selector">
    <select name="round" onchange="javascript:updateWidget(150946,this.options[this.selectedIndex].value);">
        <option value="7638">Fase de grupos/Fecha 1</option>
        <option value="7639">Fase de grupos/Fecha 2</option>
        <option selected value="7640">Fase de grupos/Fecha 3</option>
        <option value="7641">Fase de grupos/Fecha 4</option>
        <option value="7642">Fase de grupos/Fecha 5</option>
        <option value="7643">Fase de grupos/Fecha 6</option>
    </select>
</div>
<?php $inidate = "";?>
@foreach($tournament->optagames as $game)
    @if(in_array(substr($game->date,0,10),$dates))
        @if($inidate != substr($game->date,0,10))
            <?php
            $inidate = substr($game->date,0,10);
            ?>
            <div class="date">{{$inidate}}</div>
        @endif
    <div class="match {{($game->status=="LIVE" || $game->status=="HALF-TIME" && $game->period != "Full Time")?"started":($game->status=="FULL" || $game->period=="Full Time"?"ended":"not_started")}} match_{{$game->id}}" onclick="">
        <div class="info-match" style="height: 25px;">
            <div class="local" style="height: 25px;">
                <div class="local-image" style="display: inline-block;height: 25px">
                    <img src="{{$game->home->image()}}" alt="{{$game->home->name}}" title="{{$game->home->name}}">
                </div>
                <div class="local-name" style="display: inline-block;height: 25px; width:63px;vertical-align: middle;">{{$game->home->name}}</div>
            </div>
            {{--<div class="go-match">--}}
                {{--<img src="http://www.winsports.co/sites/all/themes/at_winsports/images/bullet_widget_result.png" alt="Ir" title="Ir" />--}}
            {{--</div>--}}
            <div class="visit" style="height: 25px;">
                <div class="away-name" style="display: inline-block;height: 25px; width:63px;vertical-align: middle;">{{$game->away->name}}</div>
                <div class="away-image" style="display: inline-block;height: 25px">
                    <img src="{{$game->away->image()}}" alt="{{$game->away->name}}" title="{{$game->away->name}}">
                </div>
            </div>
            <div class="score">
                <div class="lo">{{$game->home_score}}</div>
                <div class="vi">{{$game->away_score}}</div>
            </div>
        </div>
        <div class="info-broad">
            <div class="time">
                <a class="" href="">
                    @if($game->period == "Half Time")
                        Medio Tiempo
                    @elseif($game->period == "First Half")
                        Primer Tiempo
                    @elseif($game->period == "Second Half")
                        Segundo Tiempo
                    @elseif($game->period == "FullTime" || $game->period == "Full Time")
                        Finalizado
                    @else
                        {{$game->hour()}}
                    @endif
                </a>
            </div>
        </div>
    </div>
    @endif
@endforeach

<div class="dsf_resultados">
    <div class="buttons">
        <a href="/estadisticas/calendario/uefa-champions-league-2015-2016">ESTAD&Iacute;STICAS</a>
        <a href="/estadisticas/posiciones/uefa-champions-league-2015-2016">POSICIONES</a>
    </div>
</div>