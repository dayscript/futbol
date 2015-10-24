@if($tournament->id == "150946")
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
@elseif($tournament->id == "150942")
    <div class="round_selector">
        <select name="round" onchange="javascript:updateWidget(150942,this.options[this.selectedIndex].value);">
            <option value="7510">Todos contra todos/Fecha 1</option>
            <option value="7511">Todos contra todos/Fecha 2</option>
            <option value="7512">Todos contra todos/Fecha 3</option>
            <option value="7513">Todos contra todos/Fecha 4</option>
            <option value="7514">Todos contra todos/Fecha 5</option>
            <option value="7515">Todos contra todos/Fecha 6</option>
            <option value="7516">Todos contra todos/Fecha 7</option>
            <option value="7517">Todos contra todos/Fecha 8</option>
            <option value="7518">Todos contra todos/Fecha 9</option>
            <option selected="" value="7519">Todos contra todos/Fecha 10</option>
            <option value="7520">Todos contra todos/Fecha 11</option>
            <option value="7521">Todos contra todos/Fecha 12</option>
            <option value="7522">Todos contra todos/Fecha 13</option>
            <option value="7525">Todos contra todos/Fecha 14</option>
            <option value="7526">Todos contra todos/Fecha 15</option>
            <option value="7527">Todos contra todos/Fecha 16</option>
            <option value="7528">Todos contra todos/Fecha 17</option>
            <option value="7529">Todos contra todos/Fecha 18</option>
            <option value="7530">Todos contra todos/Fecha 19</option>
            <option value="7531">Todos contra todos/Fecha 20</option>
            <option value="7532">Todos contra todos/Fecha 21</option>
            <option value="7533">Todos contra todos/Fecha 22</option>
            <option value="7534">Todos contra todos/Fecha 23</option>
            <option value="7535">Todos contra todos/Fecha 24</option>
            <option value="7536">Todos contra todos/Fecha 25</option>
            <option value="7537">Todos contra todos/Fecha 26</option>
            <option value="7538">Todos contra todos/Fecha 27</option>
            <option value="7539">Todos contra todos/Fecha 28</option>
            <option value="7540">Todos contra todos/Fecha 29</option>
            <option value="7541">Todos contra todos/Fecha 30</option>
            <option value="7542">Todos contra todos/Fecha 31</option>
            <option value="7543">Todos contra todos/Fecha 32</option>
            <option value="7544">Todos contra todos/Fecha 33</option>
            <option value="7545">Todos contra todos/Fecha 34</option>
            <option value="7546">Todos contra todos/Fecha 35</option>
            <option value="7547">Todos contra todos/Fecha 36</option>
            <option value="7548">Todos contra todos/Fecha 37</option>
            <option value="7549">Todos contra todos/Fecha 38</option>
        </select>
    </div>
@elseif($tournament->id == "150945")
    <div class="round_selector">
        <select name="round" onchange="javascript:updateWidget(150945,this.options[this.selectedIndex].value);">
            <option value="7632">Primera Fase/Ida</option>
            <option value="7633">Primera Fase/Vuelta</option>
            <option value="7634">Segunda fase/Ida</option>
            <option value="7635">Segunda fase/Vuelta</option>
            <option value="7662">Octavos de final/Octavos ida</option>
            <option value="7663">Octavos de final/Octavos Vuelta</option>
            <option selected="" value="7666">Cuartos de final/Cuartos ida</option>
            <option value="7667">Cuartos de final/Cuartos vuelta</option>
        </select>
    </div>
@endif

<?php $inidate = "";?>
@foreach($tournament->optagames as $game)
    @if(in_array(substr($game->date,0,10),$dates))
        @if($inidate != substr($game->date,0,10))
            <?php
            $inidate = substr($game->date, 0, 10);
            ?>
            <div class="date">{{$inidate}}</div>
        @endif
        <div class="match {{(($game->status=="LIVE" || $game->status=="HALF-TIME") && $game->period != "Full Time")?"started":($game->status=="FULL" || $game->period=="Full Time"?"ended":"not_started")}} match_{{$game->id}}"
             onclick="">
            <div class="info-match" style="height: 25px;">
                <div class="local" style="height: 25px;">
                    <div class="local-image" style="display: inline-block;height: 25px">
                        <img src="{{$game->home->image()}}" alt="{{$game->home->name}}" title="{{$game->home->name}}">
                    </div>
                    <div class="local-name"
                         style="display: inline-block;height: 25px; width:63px;vertical-align: middle;">{{$game->home->name}}</div>
                </div>
                {{--<div class="go-match">--}}
                {{--<img src="http://www.winsports.co/sites/all/themes/at_winsports/images/bullet_widget_result.png" alt="Ir" title="Ir" />--}}
                {{--</div>--}}
                <div class="visit" style="height: 25px;">
                    <div class="away-name"
                         style="display: inline-block;height: 25px; width:63px;vertical-align: middle;">{{$game->away->name}}</div>
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
@if($tournament->id == "150946")
    <div class="dsf_resultados">
        <div class="buttons">
            <a href="/estadisticas/calendario/uefa-champions-league-2015-2016">ESTAD&Iacute;STICAS</a>
            <a href="/estadisticas/posiciones/uefa-champions-league-2015-2016">POSICIONES</a>
        </div>
    </div>
@elseif($tournament->id == "150942")
    <div class="dsf_resultados">
        <div class="buttons">
            <a href="/estadisticas/calendario/premier-league-2015-2016">ESTAD&Iacute;STICAS</a>
            <a href="/estadisticas/posiciones/premier-league-2015-2016">POSICIONES</a>
        </div>
    </div>
@elseif($tournament->id == "150945")
    <div class="dsf_resultados">
        <div class="buttons">
            <a href="/estadisticas/calendario/copa-total-sudamericana-2015">ESTAD&Iacute;STICAS</a>
            <a href="/estadisticas/posiciones/copa-total-sudamericana-2015">POSICIONES</a>
        </div>
    </div>
@endif