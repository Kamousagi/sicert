@extends('layout.aplicacion')
@section('content')
<h1>Estadística de la evaluación por alumno</h1>

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
                    <div class="col-3">
                        <label>Sección: {{$seccion_seleccionada}}</label>                        
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
                        <th>ALUMNO</th>
                        <th>NOTA</th>
                        <th>NIVEL</th>
                        <th>VER</th>
                    </tr>                    
                </thead>
                <tbody>
                    @foreach ($resultados as $resultado)
                        <tr>
                            <td>{{ $resultado->n }}</td>
                            <td>{{ $resultado->alumno }}</td>
                            <td>{{ $resultado->nota }}</td>
                            <td>{{ $resultado->nivel }}</td>
                            <td>
                            {!! Form::open(array('action' => array('ReporteController@estadistica_pregunta'))) !!}
                                {{ Form::hidden('cod_evaluacion', $evaluacion_seleccionada) }}
                                {{ Form::hidden('cod_ugel', $ugel_seleccionada) }}
                                {{ Form::hidden('cod_institucion', $institucion_seleccionada) }}
                                {{ Form::hidden('nom_seccion', $seccion_seleccionada) }}
                                {{ Form::hidden('nom_alumno', $resultado->alumno) }}
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