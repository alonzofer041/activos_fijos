@extends('layouts.app')

@section('estilos')
<link href="{{asset('public/template/css/pages/signin.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('content')
<div class="account-container">
    
    <div class="content clearfix">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if (Session::has('message'))
            {!! Session::get('message') !!}
        @endif
        <form action="{{ route('password.email') }}" method="post">
            {{ csrf_field() }}
            <h1>Reestablecer cpntraseña</h1>       
            
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
                
            </div> <!-- /login-fields -->
            
            <div class="login-actions">
                                    
                <button type="submit" class="button btn btn-success btn-large">Enviar link al correo electrónico</button>
                
            </div> <!-- .actions -->
            
            
            
        </form>
        
    </div> <!-- /content -->
    
</div> <!-- /account-container -->

@endsection

@section('scripts')
<script src="{{asset('public/js/signin.js')}}"></script>
@endsection
