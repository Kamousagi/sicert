@extends('layout.aplicacion')
@section('content')
    <ul class="breadcrumb">
        <li class="breadcrumb-item active">Estadística de la evaluación resumida</li>
        <li class="breadcrumb-item active">Detallada</li>
        <li class="breadcrumb-item active">Por sección</li>
        <li class="breadcrumb-item active">Por alumno</li>
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
    
        <div class="card">
            <div class="card-header">
                Criterio de búsqueda
            </div>
            <div class="card-body">
                <div class="row">
                    <label class="col-sm-1">Evaluacion:</label>
                    <label class="col-sm-2">{{$nom_evaluacion_seleccionada}}</label>
                    <label class="col-sm-1 text-right">Ugel:</label>
                    <label class="col-sm-1">{{$nom_ugel_seleccionada}}</label>
                    <label class="col-sm-1 text-right">Institución:</label>
                    <label class="col-sm-3">{{$nom_institucion_seleccionada}}</label>
                    <label class="col-sm-1 text-right">Sección:</label>
                    <label class="col-sm-1">{{$seccion_seleccionada}}</label>
                </div>

            </div>
        </div>
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