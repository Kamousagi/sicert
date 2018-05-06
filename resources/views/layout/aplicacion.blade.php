
<!doctype html>
<html lang="en" ng-app="RDash">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

	<title>RDash AngularJS</title>
  <link rel="stylesheet" href="/css/app.css"/>
  <link rel="stylesheet" href="/css/theme.css"/>
  <script src="/js/app.js"></script>
</head>
  <body>

    <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">SICERT</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="/cargar_evaluacion">Cargar evaluación</a></li>
              <li><a href="/evaluaciones">Evaluaciones</a></li>
              <li><a href="/reportes">Reportes</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </nav>

      <div class="container theme-showcase" role="main">
        @yield('content')
      </div> <!-- /container -->
  </body>
</html>