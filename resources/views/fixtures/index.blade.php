@extends('app')

@section('title')
    Fixtures :: @parent
@stop

@section('content')
    <h2><i class="fa fa-calendar"></i> Fixtures</h2>
    <a href="/fixtures/create" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus"></i> Crear</a>
    <small>Lista de fixtures de prueba creado por este usuario. <br>&nbsp;</small>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Título</th>
            <th>Equipos</th>
            <th>Fecha de Clásicos</th>
            <th>Creado</th>
            <th>Opciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($fixtures as $fixture)
            <tr class="fixture-{{$fixture->id}}">
                <td><a href="/fixtures/{{$fixture->id}}">{{ $fixture->title }}</a></td>
                <td>{{ $fixture->size }}</td>
                <td>{{ ($fixture->classicsRound)?"Si":"No" }}</td>
                <td>{{ $fixture->created_at }}</td>
                <td>

                    {!! Form::open(array('route' => array('fixtures.destroy', $fixture->id),'class'=>'delete-fixture', 'method' => 'delete'))!!}
                    <a class="btn btn-info btn-sm" href="/fixtures/{{$fixture->id}}/details-block"><i class="fa fa-th"></i> Ver en bloques</a>
                    <a class="btn btn-info btn-sm" href="/fixtures/{{$fixture->id}}"><i class="fa fa-table"></i> Ver en tabla</a>
                    <a class="btn btn-info btn-sm" href="/fixtures/{{$fixture->id}}/teams"><i class="fa fa-users"></i> Equipos</a>
                    <a class="btn btn-primary btn-sm" href="/fixtures/{{$fixture->id}}/edit"><i
                                class="fa fa-pencil-square-o"></i> Editar</a>
                    <button type="submit" class="btn btn-danger btn-sm deleteFixture"><i
                                class="fa fa-trash"></i> Eliminar</button>

                {!! Form::close() !!}
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
@section('scripts')
    <script>
        $('.deleteFixture').on('click', function () {
            return confirm('¿Estás seguro que quieres eliminar este calendario?');
        });
        $('.delete-fixture').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                        url: $(this).prop('action'),
                        type: 'DELETE',
                        data: {
                            "_token": $(this).find('input[name=_token]').val()
                        },
                        success: function (data) {
                            if (data.fail) {
                                toastr.error('Error al eliminar el Fixture!')
                            } else {
                                toastr.success('Fixture eliminado correctamente!')
                                $(".fixture-" + data.id).slideUp(500, function () {
                                    $(".fixture-" + data.id).remove();
                                });
                            }
                        }
                    }
            );
            return false;
        });
    </script>
@stop