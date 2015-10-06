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
                <li class="nav-item active">
                    <a class="nav-link" href="/dashboard">Dashboard <span class="sr-only">(current)</span></a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/users">Usuarios</a>
                    </li>
                <li class="nav-item">
                    <a class="nav-link" href="/help">Ayuda</a>
                </li>
                @endif
            </ul>
        </div>
        <ul class="nav navbar-nav pull-right">
            <li class="nav-item">
                @if(Auth::check())
                    <a class="nav-link" href="/auth/logout"><i class="fa fa-sign-out"></i> Salir</a>
                @else
                    <a class="nav-link" href="/auth/login"><i class="fa fa-sign-in"></i> Ingresar</a>
                @endif
            </li>
        </ul>
    </div>
</nav>