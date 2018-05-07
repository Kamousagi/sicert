@extends('layout.aplicacion')


@section('content')
<h1>Reportes</h1>

<table class="table table-hover table-bordered table-condensed">
    <tr><td><a href="/reportes/estadistica_resumen">Estadística de la evaluación resumida</a></td></tr>
    <tr><td><a href="/reportes/estadistica_detallado">Estadística de la evaluación detallada</a></td></tr>
    <tr><td><a href="/reportes/estadistica_preguntas">Estadística del resultado de las preguntas por Evaluación</a></td></tr>
    <tr><td><a href="/reportes/cronograma_evaluacion">Cronograma de evaluaciones pendientes</a></td></tr>  
</table>
@endsection