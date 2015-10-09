<nav class="navbar navbar-light bg-faded navbar-fixed-top navbar-dayscore">
    <div class="container">
        <button class="navbar-toggler hidden-sm-up" type="button" data-toggle="collapse"
                data-target="#exCollapsingNavbar2">
            &#9776;
        </button>
        <div class="collapse navbar-toggleable-xs" id="exCollapsingNavbar2">
            <ul class="nav navbar-nav">
                <li class="nav-item">
                    <a class="navbar-brand" href="#"><img src="{{ asset('images/logos/dayscore.png') }}" alt="Dayscore"></a>
                </li>
                @if(Auth::check())
                    <li class="nav-item {{(Request::path() == 'dashboard' || Request::path() == '/')?"active":""}}">
                        <a class="nav-link" href="/dashboard">Dashboard
                            @if(Request::path() == 'dashboard' || Request::path() == '/')
                                <span class="sr-only">(current)</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item {{(Request::path() == 'users')?"active":""}}">
                        <a class="nav-link" href="/users">Usuarios
                            @if(Request::path() == 'users')
                                <span class="sr-only">(current)</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item {{(Request::path() == 'fixturetests')?"active":""}}">
                        <a class="nav-link" href="/fixturetests">Fixtures
                            @if(Request::path() == 'fixturetests')
                                <span class="sr-only">(current)</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item {{(Request::path() == 'optafeeds')?"active":""}}">
                        <a class="nav-link" href="/optafeeds">Feeds Opta
                            @if(Request::path() == 'optafeeds')
                                <span class="sr-only">(current)</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item {{(Request::path() == 'tournaments')?"active":""}}">
                        <a class="nav-link" href="/tournaments">Torneos
                            @if(Request::path() == 'tournaments')
                                <span class="sr-only">(current)</span>
                            @endif
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <ul class="nav navbar-nav pull-right">
            @if(Auth::check())
                <li class="nav-item">
                    <a class="nav-link {{(Request::path() == 'help')?"help":""}}" href="/help"><i
                                class="fa fa-question"></i> Ayuda
                        @if(Request::path() == 'help')
                            <span class="sr-only">(current)</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/auth/logout"><i class="fa fa-sign-out"></i> Salir</a>
                </li>

            @else
                <li class="nav-item">
                    <a class="nav-link" href="/auth/login"><i class="fa fa-sign-in"></i> Ingresar</a>
                </li>
            @endif
        </ul>
    </div>
</nav>