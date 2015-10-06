<div class="form-group">
    {!! Form::label('name', 'Nombre completo') !!}
    {!! Form::input('text','name',null,['id' => 'name', 'maxlength' => 255,
    'placeholder'=>'Ingrese un nombre completo','class' =>
    'form-control maxlength']) !!}
    <small class="text-muted">Nombre completo del usuario</small>
</div>
<div class="form-group">
    {!! Form::label('email', 'Correo Electrónico') !!}
    {!! Form::input('email','email',null,['id' => 'email', 'maxlength' => 255,'placeholder'=>'Ingrese el correo electrónico','class' =>'form-control maxlength']) !!}
    <small class="text-muted">Correo electrónico. Se usará para iniciar sesión</small>
</div>

<div class="form-group">
    {!! Form::label('password', 'Contraseña') !!}
    {!! Form::input('password','password',null,['id' => 'password','placeholder'=>'Contraseña','class' =>'form-control']) !!}
    <small class="text-muted">Escriba una contraseña para este usuario</small>
</div>
{!! Form::submit($submitButtonText,['class' => 'btn btn-primary btn-block']) !!}