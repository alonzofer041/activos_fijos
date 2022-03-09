<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- <link rel="shortcut icon" href="assets/ico/favicon.png"> -->

  <title>Figarosy</title>

  <!-- Icons -->
<link href="{{ asset('public/t2/css/font-awesome.min.css')}}" rel="stylesheet">
  <link href="{{ asset('public/t2/css/simple-line-icons.min.css')}}" rel="stylesheet">

  <!-- Main styles for this application -->
  <link href="{{ asset('public/t2/css/style.css')}}" rel="stylesheet">

  <!-- Styles required by this views -->

</head>

<body class="app flex-row align-items-center">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card-group">
          <div class="card p-4">
            <div class="card-body">
                <form action="{{ route('login') }}" method="post">
                 {{ csrf_field() }}
              <h1>Login</h1>
              <p class="text-muted">Ingresar al sistema</p>
              <div class="input-group mb-3">
                <span class="input-group-addon"><i class="icon-user"></i></span>
                <input type="text" name="correo" class="form-control" placeholder="Nombre de usuario">
              </div>
              <div class="input-group mb-4">
                <span class="input-group-addon"><i class="icon-lock"></i></span>
                <input type="password" name="password"  class="form-control" placeholder="Password">
              </div>
              <div class="row">
                <div class="col-6">
                  <button type="submit" class="btn btn-primary px-4">Login</button>
                </div>
                
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap and necessary plugins -->
  
  
  
  
  <script src="{{ asset('public/t2/js/jquery.min.js')}}"></script>
  <script src="{{ asset('public/t2/js/popper.min.js')}}"></script>
  <script src="{{ asset('public/t2/js/bootstrap.min.js')}}"></script>
  
  
</body>
</html>