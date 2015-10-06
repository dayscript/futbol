@extends('app')

@section('title')
    Usuarios :: @parent
@stop

@section('content')
    <h2>Usuarios</h2>
    <hr>
    <a href="/users/create" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Agregar</a>
    <p>Lista de usuarios creados en el sistema.</p>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Creado</th>
            <th>Opciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr class="user-{{$user->id}}">
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at }}</td>
                <td>
                    {!! Form::open(array('route' => array('users.destroy', $user->id),'class'=>'delete-user', 'method' => 'delete'))!!}
                    <input type="submit" class="btn btn-danger btn-sm deleteUser" value="Eliminar">
                    <a class="btn btn-primary btn-sm" href="/users/{{$user->id}}/edit"><i class="fa fa-pencil-square-o"></i> Editar</a>
                    {!! Form::close() !!}

            </tr>
        @endforeach
        </tbody>
    </table>
@stop
@section('scripts')
    <script>
        $('.deleteUser').on('click', function () {
            return confirm('¿Estás seguro que quieres eliminar este usuario?');
        });
        $('.delete-user').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                        url: $(this).prop('action'),
                        type: 'DELETE',
                        data: {
                            "_token": $(this).find('input[name=_token]').val()
                        },
                        success: function (data) {
                            if (data.fail) {

                            } else {
                                $(".user-"+data.id).slideUp(500, function() {
                                    $(".user-"+data.id).remove();
                                });
                            }
                        }
                    }
            );
            return false;
        });
    </script>
@stop