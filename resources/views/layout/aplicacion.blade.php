<!doctype html>
<html lang="en" ng-app="RDash">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>SICERT</title>

  <link rel="stylesheet" href="/css/bootstrap.min.css">

  <script src="/js/jquery.js"></script>

  <script src="/js/bootstrap.min.js"></script>

  <script src="/js/Chart.js"></script>

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.12/css/all.css">

  <link rel="stylesheet" href="/css/aplicacion.css"/>

  <script src="/js/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="/css/jquery-ui.min.css"/>
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
              <li class="nav-item">
                <a class="nav-link" href="/reportes">Reportes</a>
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