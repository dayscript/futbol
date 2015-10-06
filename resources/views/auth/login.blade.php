@extends('app')

@section('title')
    Ingreso :: @parent
@stop


@section('content')
    <h2><i class="fa fa-user"></i> Iniciar Sesión</h2>
    <hr>
    <form method="POST" action="/auth/login">
        {!! csrf_field() !!}
        <fieldset class="form-group row">
            <label for="email" class="col-sm-3 form-control-label">Correo electrónico</label>
            <div class="col-sm-9">
                <input type="email" class="form-control" name="email" id="email" placeholder="Ingrese su email" value="{{ old('email') }}">
                <small class="text-muted">Ingrese la dirección de correo electrónico con la que se creó su cuenta.</small>
            </div>
        </fieldset>
        <fieldset class="form-group row">
            <label for="password" class="col-sm-3 form-control-label">Contraseña</label>
            <div class="col-sm-9">
                <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña">
                <small class="text-muted">Ingrese la contraseña asignada.</small>
            </div>
        </fieldset>
        <div class="form-group row">
            <div class="col-sm-offset-3 col-sm-9">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> Recordarme
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="submit" class="btn btn-primary btn-dayscore btn-block">Entrar</button>
            </div>
        </div>
    </form>
@stop