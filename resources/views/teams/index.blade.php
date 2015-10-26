@extends('app')

@section('title')
    Equipos :: @parent
@stop
@section('content')
    <h2><i class="fa fa-users"></i> Equipos</h2>
    <a href="/teams/create" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Crear</a>
    <small>Lista de todos los equipos creados en el sistema. <br>&nbsp;</small>

    <hr>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>ID</th>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Creado</th>
            <th>Opciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($teams as $team)
            <tr class="team-{{$team->id}}">
                <td>{{$team->id}}</td>
                <td><img class="img-responsive img-thumbnail" src="{{$team->image()}}" alt="{{$team->name}}" width="40">
                </td>
                <td>{{$team->name}}</td>
                <td>{{$team->created_at}}</td>
                <td>
                    {!! Form::open(array('route' => array('teams.destroy', $team->id),'class'=>'delete-team', 'method' => 'delete'))!!}
                    <a class="btn btn-info btn-sm" href="/teams/{{$team->id}}"><i class="fa fa-users"></i> Detalle</a>
                    <a class="btn btn-primary btn-sm" href="/teams/{{$team->id}}/edit"><i
                                class="fa fa-pencil-square-o"></i> Editar</a>
                    <button type="submit" class="btn btn-danger btn-sm deleteTeam"><i
                                class="fa fa-trash"></i> Eliminar
                    </button>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="text-right">
        {!! $teams->render() !!}
    </div>
@stop

@section('scripts')
    <script>
        $('.deleteTeam').on('click', function () {
            return confirm('¿Estás seguro que quieres eliminar este equipo?');
        });
        $('.delete-team').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                        url: $(this).prop('action'),
                        type: 'DELETE',
                        data: {
                            "_token": $(this).find('input[name=_token]').val()
                        },
                        success: function (data) {
                            if (data.fail) {
                                toastr.error('Error al eliminar el equipo!')
                            } else {
                                toastr.success('Equipo eliminado correctamente!')
                                $(".team-" + data.id).slideUp(500, function () {
                                    $(".team-" + data.id).remove();
                                });
                            }
                        }
                    }
            );
            return false;
        });
    </script>
@stop
