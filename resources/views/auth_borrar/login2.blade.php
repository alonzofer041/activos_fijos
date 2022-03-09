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
        <form action="{{ route('login') }}" method="post">
            {{ csrf_field() }}
            <h1>Iniciar Sesión</h1>       
            
            <div class="login-fields">
                
                <div class="form-group field{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="control-label">Correo electrónico</label>
                    <input type="email" id="email" name="correo" value="{{ old('correo') }}" placeholder="ejemplo@ejemplo.com" class="login username-field form-control" required autofocus />
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div> <!-- /field -->
                

                <div class="field form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="control-label">Contraseña</label>
                    <input type="password" id="password" name="password" value="" placeholder="Password" class="login password-field form-control" required="" />
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div> <!-- /password -->
                
            </div> <!-- /login-fields -->
            
            <div class="login-actions">

                <span class="login-checkbox">
                    <input id="Field" name="remember" type="checkbox" class="field login-checkbox" value="{{ old('remember') ? 'checked' : '' }}" tabindex="4" />
                    <label class="choice" for="remember">Recordar</label>
                </span>
                                    
                <button type="submit" class="button btn btn-success btn-large">Entrar</button>
                
            </div> <!-- .actions -->
            
            
            
        </form>
        
    </div> <!-- /content -->
    
</div> <!-- /account-container -->

<div class="login-extra">
    <a class="" href="{{ route('password.request') }}">
        ¿has olvidado tu contraseña?
    </a>
</div> <!-- /login-extra -->


@endsection

@section('scripts')
<script src="{{asset('public/js/signin.js')}}"></script>
@endsection
