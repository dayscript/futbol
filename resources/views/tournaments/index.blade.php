@extends('app')

@section('content')
    <h3>Torneos</h3>
    <hr>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Creado</th>
            <th>Opciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tournaments as $tournament)
            <tr class="tournament-{{$tournament->id}}">
                <td>{{$tournament->id}}</td>
                <td>{{$tournament->name}}</td>
                <td>{{$tournament->created_at}}</td>
                <td>
                    <a class="btn btn-info btn-sm" href="/tournaments/{{$tournament->id}}"><i class="fa fa-futbol-o"></i> Detalle</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop

