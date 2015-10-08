<div class="form-group">
    {!! Form::label('title', 'Título') !!}
    {!! Form::input('text','title',null,['id' => 'title', 'maxlength' => 255,
    'placeholder'=>'Ingrese un título','class' =>
    'form-control maxlength']) !!}
    <small class="text-muted">Ingrese un texto para describir este calendario de prueba</small>
</div>
<div class="form-group row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('size', 'Número de equipos') !!}
            {!! Form::input('number','size',null,['id' => 'size','placeholder'=>'20','class' =>'form-control maxlength']) !!}
            <small class="text-muted">Número de equipos que se incluyen en este calendario</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('classicsRound', 'Opciones') !!}
            <div class="checkbox form-control">
                <label><input type="checkbox" name="classicsRound" value="1"> Incluir fecha adicional de clásicos</label>
            </div>
            <small class="text-muted">Esto creará una fecha adicional en el calendario</small>

        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 p-a">
        {!! Form::submit($submitButtonText,['class' => 'btn btn-primary btn-block']) !!}
    </div>
    <div class="col-md-6 p-a">
        <a href="/fixturetests" class="btn btn-danger btn-block">Cancelar</a>
    </div>
</div>
