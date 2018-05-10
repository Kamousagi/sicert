@extends('layout.aplicacion')
@section('content')
<h1>Estadística de la evaluación por sección</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    
        <div class="card">
            <div class="card-header">
                Criterio de búsqueda
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <label>Evaluación: {{$nom_evaluacion_seleccionada}}</label>                        
                    </div>
                    <div class="col-3">
                        <label>Ugel: {{$nom_ugel_seleccionada}}</label>                        
                    </div>
                    <div class="col-3">
                        <label>Institución: {{$nom_institucion_seleccionada}}</label>                        
                    </div>
                </div>
            </div>    
        </div>
    
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
                        <th>VER</th>
                    </tr>                    
                </thead>
                <tbody>
                    @foreach ($resultados as $resultado)
                        <tr>
                            <td>{{ $resultado->n }}</td>
                            <td>{{ $resultado->seccion}}</td>
                            <td>{{ $resultado->nalumnos}}</td>
                            <td>{{ $resultado->n1 }}</td>
                            <td>{{ $resultado->n2 }}</td>
                            <td>{{ $resultado->n3 }}</td>
                            <td>{{ $resultado->n4 }}</td>
                            <td>{{ $resultado->p1 }}</td>
                            <td>{{ $resultado->p2 }}</td>
                            <td>{{ $resultado->p3 }}</td>
                            <td>{{ $resultado->p4 }}</td>
                            <td>
                            {!! Form::open(array('action' => array('ReporteController@estadistica_alumno'))) !!}
                                {{ Form::hidden('cod_evaluacion', $evaluacion_seleccionada) }}
                                {{ Form::hidden('cod_ugel', $ugel_seleccionada) }}
                                {{ Form::hidden('cod_institucion', $institucion_seleccionada) }}
                                {{ Form::hidden('nom_seccion', $resultado->seccion) }}
                                {!! Form::submit('Ver', ['class' => 'btn btn-success']) !!}
                            {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach                    
                </tbody>
            </table>
        </div>
    </div>
    
@endsection