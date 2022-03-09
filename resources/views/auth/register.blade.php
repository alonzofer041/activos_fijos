@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('Agentes/css/sesion.css')}}">
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Registro</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('nomusuario') ? ' has-error' : '' }}">
                            <label for="nomusuario" class="col-md-4 control-label">Nombre</label>

                            <div class="col-md-6">
                                <input id="nomusuario" type="text" class="form-control" name="nomusuario" value="{{ old('nomusuario') }}" required autofocus>

                                @if ($errors->has('nomusuario'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('nomusuario') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- CURP -->
                        <div class="form-group{{ $errors->has('apellidos') ? ' has-error' : '' }}">
                            <label for="curp" class="col-md-4 control-label">Apellidos</label>

                            <div class="col-md-6">
                                <input id="apellidos" type="text" class="form-control" name="apellidos" value="{{ old('apellidos') }}" required autofocus>

                                @if ($errors->has('apellidos'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('apellidos') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                         <!-- telefono-->
                        <div class="form-group{{ $errors->has('telefono') ? ' has-error' : '' }}">
                            <label for="telefono" class="col-md-4 control-label">Telefono</label>

                            <div class="col-md-6">
                                <input id="telefono" type="text" class="form-control" name="telefono" value="{{ old('telefono') }}" required autofocus>

                                @if ($errors->has('telefono'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('telefono') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                         <!-- contacto_facebook-->
                        <div class="form-group{{ $errors->has('gdr_academico') ? ' has-error' : '' }}">
                            <label for="gdr_academico" class="col-md-4 control-label">Grado académico</label>

                            <div class="col-md-6">
                                <input id="gdr_academico" type="text" class="form-control" name="gdr_academico" value="{{ old('gdr_academico') }}" required autofocus>

                                @if ($errors->has('gdr_academico'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gdr_academico') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- foto-->
                        <div class="form-group{{ $errors->has('area') ? ' has-error' : '' }}">
                            <label for="area" class="col-md-4 control-label">area</label>

                            <div class="col-md-6">
                                <input id="area" type="text" class="form-control" name="area" value="{{ old('area') }}" required autofocus>

                                @if ($errors->has('area'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('area') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                         <!-- matricula-->
                        <div class="form-group{{ $errors->has('matricula') ? ' has-error' : '' }}">
                            <label for="matricula" class="col-md-4 control-label">matricula</label>

                            <div class="col-md-6">
                                <input id="matricula" type="text" class="form-control" name="matricula" value="{{ old('matricula') }}" required autofocus>

                                @if ($errors->has('matricula'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('matricula') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                         <!-- Rol-->
<!--                         <div class="form-group{{ $errors->has('idrol') ? ' has-error' : '' }}">
                            <label for="rol" class="col-md-4 control-label">Rol</label>

                            <div class="col-md-6">
                                <input id="idrol" type="text" class="form-control" name="idrol" value="{{ old('idrol') }}" required autofocus>

                                @if ($errors->has('rol'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('idrol') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>  
 -->                        
                        <div class="form-group{{ $errors->has('idrol') ? ' has-error' : '' }}">
                            <label for="rol" class="col-md-4 control-label">Rol</label>

                            <div class="col-md-6">
                                <select type="text" id="text-input" name="idrol" placeholder="Rol" value=""  class="form-control">
                                    <option value="0">Selecciona Un Rol</option>
                                        @foreach($roles as $rol)
                                            <option value="{{$rol->idrol}}">{{$rol->nomrol}}</option>
                                        @endforeach
                                </select>   
                            </div>
                        </div>                   

                         <!-- Email-->
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                         <!-- Password-->
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirmar Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Registrarse
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
