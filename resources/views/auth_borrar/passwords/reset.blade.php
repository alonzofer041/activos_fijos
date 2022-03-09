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
        <form action="{{ route('password.request') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="token" value="{{ $token }}">
            <h1>Reestablecer contraseña</h1>       
            
            <div class="login-fields">
                
                <div class="form-group field{{ $errors->has('correo') ? ' has-error' : '' }}">
                    <label for="email" class="control-label">Correo electrónico</label>
                    <input type="email" id="email" name="correo" value="{{ old('correo') }}" placeholder="ejemplo@ejemplo.com" class="login username-field form-control" required autofocus />
                    @if ($errors->has('correo'))
                        <span class="help-block">
                            <strong>{{ $errors->first('correo') }}</strong>
                        </span>
                    @endif
                </div> <!-- /field -->

                <div class="form-group field{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="control-label">Contraseña</label>
                    <input type="password" id="password" name="password"  placeholder="Contraseña" class="login form-control" required />
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div> <!-- /field -->

                <div class="form-group field{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                    <label for="password-confirm" class="control-label">Confirmar contraseña</label>
                    <input type="password" id="password-confirm" name="password_confirmation"  placeholder="Confirmar contraseña" class="login form-control" required />
                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div> <!-- /field -->
                
            </div> <!-- /login-fields -->
            
            <div class="login-actions">
                                    
                <button type="submit" class="button btn btn-success btn-large">Reestablecer contraseña</button>
                
            </div> <!-- .actions -->
            
            
            
        </form>
        
    </div> <!-- /content -->
    
</div> <!-- /account-container -->
@endsection

@section('scripts')
<script src="{{asset('public/js/signin.js')}}"></script>
@endsection
