<div class="form-group row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('id', 'ID') !!}
            {!! Form::input('number','id',null,['autofocus','id' => 'id','placeholder'=>'XXXX','class' =>'form-control maxlength']) !!}
            <small class="text-muted">ID del equipo. Se debe usar el ID existente del equipo en las plataformas de
                Dayscript. La creación del equipo sólo requiere proveer el ID. El sistema buscará este
                ID en la plataforma Dayscore y traerá la información adicional.
            </small>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            {!! Form::label('name', 'Nombre') !!}
            {!! Form::input('text','name',null,['id' => 'name','readonly', 'maxlength' => 255,
            'placeholder'=>'','class' =>
            'form-control maxlength']) !!}
            <small class="text-muted">El nombre del equipo se obtiene automáticamente.</small>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 p-a">
        {!! Form::submit($submitButtonText,['class' => 'btn btn-primary btn-block']) !!}
    </div>
    <div class="col-md-6 p-a">
        <a href="/teams" class="btn btn-danger btn-block">Cancelar</a>
    </div>
</div>

@section('scripts')
    <script>
        $('#id').keyup(function () {
            var teamid = $(this).val();
            if(teamid.length > 1){
                $.getJSON( "http://futbol.dayscript.com/minamin/teams/tojson/"+teamid)
                        .done(function( data ) {
                            if(data.name){
                                $('#name').val(data.name);
                            } else {
                                $('#name').val('Equipo '+teamid);
                            }
                        })
                        .fail(function (data){
                            $('#name').val('Equipo '+teamid);
                        });
            } else {
                $('#name').val('Equipo '+teamid);
            }
        });
    </script>
@stop