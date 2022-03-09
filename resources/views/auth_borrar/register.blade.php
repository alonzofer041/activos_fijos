@extends('layouts.app')
@section('estilos')
<link href="{{asset('public/template/css/pages/signin.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="account-container">
    
    <div class="content clearfix">
        @if (Session::has('message'))
            {!! Session::get('message') !!}
        @endif
        <form action="{{ route('registro') }}" method="post">
            {{ csrf_field() }}
            <h1>Registro</h1>       
            
            <div class="login-fields">
                
                <div class="form-group field{{ $errors->has('nombre') ? ' has-error' : '' }}">
                    <label for="name" class="control-label">Nombre completo</label>
                    <input type="text" id="name" name="nombre" value="{{ old('nombre') }}" placeholder="Nombre apellidos" class="login form-control" required autofocus />
                    @if ($errors->has('nombre'))
                        <span class="help-block">
                            <strong>{{ $errors->first('nombre') }}</strong>
                        </span>
                    @endif
                </div> <!-- /field -->


                <div class="form-group field{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="control-label">Correo electrónico</label>
                    <input type="email" id="email" name="correo" value="{{ old('correo') }}" placeholder="ejemplo@ejemplo.com" class="login form-control" required />
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div> <!-- /field -->
                

                <div class="field form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="control-label">Contraseña</label>
                    <input type="password" id="password" name="password" value="" placeholder="Contraseña" class="login form-control" required="" />
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div> <!-- /password -->

                <div class="field">
                    <label for="password-confirm" class="control-label">Confirmar contraseña</label>
                    <input type="password" id="password-confirm" name="password_confirmation" value="" placeholder="Confirmar contraseña" class="login form-control" required="" />
                </div> <!-- /confirmacion password -->

                
            </div> <!-- /login-fields -->
            
            <div class="login-actions">
                                    
                <button type="submit" class="button btn btn-primary btn-large">Registrarse</button>
                
            </div> <!-- .actions -->
            
            
            
        </form>
        
    </div> <!-- /content -->
    
</div> <!-- /account-container -->

<div class="login-extra">
    <a class="" href="{{ route('login') }}">
        Ya tienes una cuenta, inicia sesión
    </a>
</div> <!-- /login-extra -->


@endsection

@section('scripts')
<script src="{{asset('public/js/signin.js')}}"></script>
@endsection
