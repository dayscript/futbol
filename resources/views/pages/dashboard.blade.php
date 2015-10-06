@extends('app')

@section('title')
    Dahsboard :: @parent
@stop

@section('content')
    <h2><i class="fa fa-bar-chart"></i> Dashboard</h2>
    <hr>
    <div class="row">
        <div class="card col-md-3">
            <div class="card-block">
                <h4 class="card-title">Opta Feeds</h4>
                <p class="card-text">Total: {{ $optafeeds->count() }}</p>
                <a href="/optafeeds" class="btn btn-primary">Ver</a>
            </div>
        </div>
        <div class="card col-md-3">
            <div class="card-block">
                <h4 class="card-title">Usuarios</h4>
                <p class="card-text">Total: {{ $users->count() }}</p>
                <a href="/users" class="btn btn-primary">Ver</a>
            </div>
        </div>

    </div>
@stop