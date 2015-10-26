@extends('app')

@section('title')
    Roles :: @parent
@stop

@section('content')
    <h2><i class="fa fa-users"></i> Roles </h2>
    <a href="/roles/create" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Agregar</a>
    <small>Lista de roles creados en el sistema. <br>&nbsp;</small>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Creado</th>
            <th>Opciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($roles as $role)
            <tr class="role-{{$role->id}}">
                <td>{{ $role->name }}</td>
                <td>{{ $role->created_at }}</td>
                <td>
                    {!! Form::open(array('route' => array('roles.destroy', $role->id),'class'=>'delete-role', 'method' => 'delete'))!!}
                    <input type="submit" class="btn btn-danger btn-sm deleteRole" value="Eliminar">
                    <a class="btn btn-primary btn-sm" href="/roles/{{$role->id}}/edit"><i
                                class="fa fa-pencil-square-o"></i> Editar</a>
                {!! Form::close() !!}
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
@section('scripts')
    <script>
        $('.deleteRole').on('click', function () {
            return confirm('¿Estás seguro que quieres eliminar este rol?');
        });
        $('.delete-role').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                        url: $(this).prop('action'),
                        type: 'DELETE',
                        data: {
                            "_token": $(this).find('input[name=_token]').val()
                        },
                        success: function (data) {
                            if (data.fail) {
                                toastr.error('Error al eliminar el rol!')
                            } else {
                                toastr.success('Rol eliminado correctamente!')
                                $(".role-" + data.id).slideUp(500, function () {
                                    $(".role-" + data.id).remove();
                                });
                            }
                        }
                    }
            );
            return false;
        });
    </script>
@stop