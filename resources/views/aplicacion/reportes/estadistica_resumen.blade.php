@extends('layout.aplicacion')
@section('content')
<h1>Estadística de la evaluación resumida</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::open(array('action' => array('ReporteController@estadistica_resumen'))) !!}
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
            <table class="table">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>UGEL</th>
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
                            <td>{{ $resultado->ugel}}</td>
                            <td>{{ $resultado->nalumnos}}</td>
                            <td>{{ $resultado->n1[0]->n1 }}</td>
                            <td>{{ $resultado->n2[0]->n2 }}</td>
                            <td>{{ $resultado->n3[0]->n3 }}</td>
                            <td>{{ $resultado->n4[0]->n4 }}</td>
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