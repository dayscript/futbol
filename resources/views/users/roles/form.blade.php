<div class="form-group">
    {!! Form::label('name', 'Nombre del rol') !!}
    {!! Form::input('text','name',null,['autofocus','id' => 'name', 'maxlength' => 255,
    'placeholder'=>'Ingrese un nombre para este rol','class' =>
    'form-control maxlength']) !!}
    <small class="text-muted">Nombre completo del rol</small>
</div>
{!! Form::submit($submitButtonText,['class' => 'btn btn-primary btn-block']) !!}