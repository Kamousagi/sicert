<!doctype html>
<html lang="en" ng-app="RDash">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>SICERT</title>

  <link rel="stylesheet" href="/css/bootstrap.min.css">

  <script src="/js/jquery-3.3.1.slim.min.js"></script>
  <script src="/js/popper.min.js"></script>
  <script src="/js/jquery.validate.min.js"></script>

  <script src="/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css">

  <link rel="stylesheet" href="/css/aplicacion.css"/>
</head>
  <body>

      <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
          <a class="navbar-brand" href="/">SICERT</a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
    
          <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <a class="nav-link" href="/cargar_evaluacion">Cargar evaluaciones <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/evaluaciones">Evaluaciones</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="https://example.com" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reportes</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                  <a class="dropdown-item" href="#">Action</a>
                  <a class="dropdown-item" href="#">Another action</a>
                  <a class="dropdown-item" href="#">Something else here</a>
                </div>
              </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item active">
                  <a class="nav-link" href="/logout">Cerra sesión</a>
                </li>
            </ul>

          </div>
        </nav>

        <main role="main" class="container">
            @yield('content')
          </main><!-- /.container -->

  </body>
</html>