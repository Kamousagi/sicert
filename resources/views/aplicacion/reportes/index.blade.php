@extends('layout.aplicacion')


@section('content')
<h1>Reportes</h1>

<table class="table table-hover table-bordered table-condensed">
<tr><td>
{!! Form::open(array('action' => array('ReporteController@estadistica_resumen'))) !!}    
    {{ Form::hidden('cod_evaluacion', '0') }}
    {!! Form::submit('Estadística de la evaluación resumida', ['class' => 'btn btn-success']) !!}
{!! Form::close() !!}
</td></tr>
<tr><td>
{!! Form::open(array('action' => array('ReporteController@estadistica_detallado'))) !!}    
    {{ Form::hidden('cod_evaluacion', '0') }}
    {{ Form::hidden('cod_ugel', '0') }}
    {!! Form::submit('Estadística de la evaluación detallada', ['class' => 'btn btn-success']) !!}
{!! Form::close() !!}
</td></tr>
<tr><td>
{!! Form::open(array('action' => array('ReporteController@estadistica_preguntas'))) !!}    
    {{ Form::hidden('cod_evaluacion', '0') }}
    {!! Form::submit('Estadística del resultado de las preguntas por Evaluación', ['class' => 'btn btn-success']) !!}
{!! Form::close() !!}
</td></tr>
<tr><td>
{!! Form::open(array('method' => 'get','action' => array('ReporteController@cronograma_evaluacion'))) !!}    
    {!! Form::submit('Cronograma de evaluaciones pendientes', ['class' => 'btn btn-success']) !!}
{!! Form::close() !!}
</td></tr>
</table>
@endsection