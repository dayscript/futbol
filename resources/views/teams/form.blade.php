<div class="form-group row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('id', 'ID') !!}
            {!! Form::input('number','id',null,['id' => 'id','placeholder'=>'XXXX','class' =>'form-control maxlength']) !!}
            <small class="text-muted">ID del equipo. Se debe usar el ID existente del equipo en las plataformas de Dayscript.</small>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            {!! Form::label('name', 'Nombre') !!}
            {!! Form::input('text','name',null,['autofocus','id' => 'title', 'maxlength' => 255,
            'placeholder'=>'Ingrese el nombre del equipo','class' =>
            'form-control maxlength']) !!}
            <small class="text-muted">Ingrese el nombre del equipo</small>
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
