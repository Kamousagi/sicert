<!doctype html>
<html lang="en" ng-app="RDash">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>RDash AngularJS</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js" crossorigin="anonymous"></script>

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

        <link rel="stylesheet" href="/css/autenticacion.css"/>

    </head>
    <body>

        <div class="form-signin">

            @if(Session::has('mensaje_error'))
                <div class="">
                    <div class="alert alert-danger">
                        {{ Session::get('mensaje_error') }}
                    </div>
                </div>
                <br>
            @endif

            {{ Form::open(array('url' => '/login')) }}

                <div class="text-center">
                    <h5 class="">Ingresar a SICERT</h5>
                </div>
                <br>
                    
                <div class="col">
                    {{ Form::label('usuario', 'Nombre de usuario') }}
                    {{ Form::text('nom_usuario', '', ['class' => 'form-control']) }}
                </div>
                <div class="col">
                    {{ Form::label('contraseña', 'Contraseña') }}
                    {{ Form::password('nom_clave', ['class' => 'form-control']) }}
                </div>
                <div class="col">
                    {{ Form::label('lblRememberme', 'Recordar contraseña') }}
                    {{ Form::checkbox('rememberme', true) }}
                </div>
                <br>
                <div class="col">
                    {{ Form::submit('Ingresar', ['class' => 'form-control btn btn-success']) }}
                </div>

            {{ Form::close() }}
        </div>
    </body>

</html>