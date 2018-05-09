@extends('layout.aplicacion')
@section('content')
<h1>Estadística de la evaluación detallada</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::open(array('action' => array('ReporteController@estadistica_aula'))) !!}
        <div class="card">
            <div class="card-header">
                Criterio de búsqueda
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <label>Evaluación</label>
                        {!! Form::select('cod_evaluacion', $evaluaciones, $evaluacion_seleccionada, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-3">
                        <label>Ugel</label>
                        {!! Form::select('cod_ugel', $ugeles, $ugel_seleccionada, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-6">
                        <label>Ugel</label>
                        {!! Form::select('cod_institucion', $instituciones, $institucion_seleccionada, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                {!! Form::submit('Buscar', ['class' => 'btn btn-success']) !!}
            </div>
        </div>
    {!! Form::close() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            Resultado de la búsqueda
        </div>
        <div class="panel-body">
            <div style="width:75%;">
                {!! $chartjs->render() !!}
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>SECCION</th>
                        <th>N° EST</th>
                        <th>PREVIO AL INICIO</th>
                        <th>INICIO</th>
                        <th>EN PROCESO</th>
                        <th>LOGRO PREVISTO</th>
                        <th>% PREVIO AL INICIO</th>
                        <th>% INICIO</th>
                        <th>% EN PROCESO</th>
                        <th>% LOGRO PREVISTO</th>
                    </tr>                    
                </thead>
                <tbody>
                    @foreach ($resultados as $resultado)
                        <tr>
                            <td>{{ $resultado->n }}</td>
                            <td>{{ $resultado->section}}</td>
                            <td>{{ $resultado->nalumnos}}</td>
                            <td>{{ $resultado->n1 }}</td>
                            <td>{{ $resultado->n2 }}</td>
                            <td>{{ $resultado->n3 }}</td>
                            <td>{{ $resultado->n4 }}</td>
                            <td>{{ $resultado->p1 }}</td>
                            <td>{{ $resultado->p2 }}</td>
                            <td>{{ $resultado->p3 }}</td>
                            <td>{{ $resultado->p4 }}</td>
                        </tr>
                    @endforeach                    
                </tbody>
            </table>
        </div>
    </div>
    
@endsection