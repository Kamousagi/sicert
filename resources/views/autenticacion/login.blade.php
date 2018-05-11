<!doctype html>
<html lang="en" ng-app="RDash">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>.:: SICERT ::.</title>
        <!-- Bootstrap core CSS-->
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom fonts for this template-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Custom styles for this template-->
        <link href="/css/sb-admin.css" rel="stylesheet">
    </head>
    <body class="bg-dark">
        <div class="container">

            @if(Session::has('mensaje_error'))
                <div class="card-login alert alert-danger mx-auto mt-5">
                    {{ Session::get('mensaje_error') }}
                </div>
            @endif

            <div class="card card-login mx-auto mt-5">

              <div class="card-header">Ingresar al SICERT</div>
              <div class="card-body">
                {{ Form::open(array('url' => '/login')) }}
                  <div class="form-group">
                    {{ Form::label('usuario', 'Nombre de usuario') }}
                    {{ Form::text('nom_usuario', '', ['class' => 'form-control']) }}
                  </div>
                  <div class="form-group">
                    {{ Form::label('contraseña', 'Contraseña') }}
                    {{ Form::password('nom_clave', ['class' => 'form-control']) }}
                  </div>
                  <div class="form-group">
                    <div class="form-check">
                      <label class="form-check-label">
                        {{ Form::checkbox('rememberme', true, null, ['class' => 'form-check-input']) }} Recuerdame
                      </label>
                    </div>
                  </div>
                  {{ Form::submit('Ingresar', ['class' => 'btn btn-primary btn-block']) }}
                {{ Form::close() }}
              </div>
            </div>
          </div>

        <!-- Bootstrap core JavaScript-->
        <script src="/js/jquery.js"></script>
        <script src="/js/bootstrap.bundle.min.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="/js/sb-admin.min.js"></script>
          
    </body>

</html>