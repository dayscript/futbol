@extends('app')

@section('title')
    Dahsboard :: @parent
@stop

@section('content')
    <h2><i class="fa fa-bar-chart"></i> Dashboard</h2>
    <hr>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    Opta Feeds
                </div>
                <div class="card-block">
                    <p class="card-text">Total de feeds recibidos a la fecha: <strong>{{ $optafeeds->count() }}</strong></p>
                    <p class="card-text"><strong>Último Feed:</strong> <br>
                        {{ $optafeeds->first()->type()}}, <br>
                        <small>{{($optafeeds->first()->tournament())?$optafeeds->first()->tournament()->name:$optafeeds->first()->competitionId." / ".$optafeeds->first()->seasonId }}</small>
                        <br>
                        Recibido: {{$optafeeds->first()->created_at->diffForHumans()}}</p>
                    <a href="/optafeeds" class="btn btn-primary">Ver Feeds</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-block">
                <h4 class="card-title">Usuarios</h4>
                <p class="card-text">Cantidad de usuarios creados en el sistema: <strong>{{ $users->count() }}</strong></p>
                <a href="/users" class="btn btn-primary">Ver</a>
            </div>
        </div>
    </div>
@stop