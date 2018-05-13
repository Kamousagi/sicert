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

    @if ($ind_form == 0)
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
                </div>
            </div>
        </div>
    @else
        {!! Form::open(array('action' => array('ReporteController@estadistica_detallado'))) !!}
        <div class="card">
            <div class="card-header">
                Criterio de búsqueda
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <label>Evaluación:</label>
                        {!! Form::select('cod_evaluacion', $evaluaciones, $evaluacion_seleccionada, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-3">
                        <label>Ugel: {{$nom_ugel_seleccionada}}</label>                       
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                {!! Form::submit('Buscar', ['class' => 'btn btn-success']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    @endif
    @if (count($resultados)>0)
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
                        <th>INSTITUCION EDUCATIVA</th>
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
                            <td>{{ $resultado->institucion}}</td>
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
                            {!! Form::open(array('action' => array('ReporteController@estadistica_seccion'))) !!}
                                {{ Form::hidden('cod_evaluacion', $evaluacion_seleccionada) }}
                                {{ Form::hidden('cod_ugel', $ugel_seleccionada) }}
                                {{ Form::hidden('cod_institucion', $resultado->cod_institucion) }}
                                {!! Form::submit('Ver', ['class' => 'btn btn-success']) !!}
                            {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach                    
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="panel-heading">
        No se encontraron registros.    
    </div>
    @endif
@endsection