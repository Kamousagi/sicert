@extends('layout.aplicacion')
@section('content')

    <ul class="breadcrumb">
        <li class="breadcrumb-item active">Estadística de la evaluación resumida</li>
        <li class="breadcrumb-item active">Detallada</li>
        <li class="breadcrumb-item active">Por sección</li>
    </ul>

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
                    <label class="col-sm-1">Evaluacion</label>
                    <label class="col-sm-2">{{$nom_evaluacion_seleccionada}}</label>
                    <label class="col-sm-1 text-right">Ugel</label>
                    <label class="col-sm-1">{{$nom_ugel_seleccionada}}</label>
                    <label class="col-sm-1 text-right">Institución</label>
                    <label class="col-sm-4">{{$nom_institucion_seleccionada}}</label>
                </div>
            </div>    
        </div>
    @else
        {!! Form::open(array('action' => array('ReporteController@estadistica_seccion'))) !!}
        <div class="card">
            <div class="card-header">
                Criterio de búsqueda
            </div>
            <div class="card-body">
                <div class="row">
                    <label class="col-sm-1">Evaluacion</label>
                    <div class="col-sm-2">
                        {!! Form::select('cod_evaluacion', $evaluaciones, $evaluacion_seleccionada, ['class' => 'form-control']) !!}
                    </div>
                    <label class="col-sm-1">Ugel</label>
                    <label class="col-sm-1">{{$nom_ugel_seleccionada}}</label>
                    <label class="col-sm-1">Institución</label>
                    <label class="col-sm-1">{{$nom_institucion_seleccionada}}</label>
                </div>
            </div>
            <div class="card-footer text-right">
                {!! Form::submit('Buscar', ['class' => 'btn btn-success']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    @endif
    @if (count($resultados)>0)    
    <br>
    <div class="card">
        <div class="card-header">
            Resultado de la búsqueda
        </div>
        <div class="card-body">
            <div style="width:75%;">
                {!! $chartjs->render() !!}
            </div>
        </div>
        <table class="table table-striped table-bordered table-hover table-sm">
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
                            {!! Form::submit('Ver', ['class' => 'btn-sm btn btn-success']) !!}
                        {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach                    
            </tbody>
        </table>
    </div>
    @else
    <div class="card-heading">
        No se encontraron registros.    
    </div>
    @endif
@endsection