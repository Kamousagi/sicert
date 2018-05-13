@extends('layout.aplicacion')
@section('content')
    <ul class="breadcrumb">
        <li class="breadcrumb-item active">Estadística de la evaluación resumida</li>
        <li class="breadcrumb-item active">Detallada</li>
        <li class="breadcrumb-item active">Por sección</li>
        <li class="breadcrumb-item active">Por alumno</li>
        <li class="breadcrumb-item active">Por pregunta</li>
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
                    <label class="col-sm-3">Evaluacion {{$nom_evaluacion_seleccionada}}</label>
                    <label class="col-sm-1">Ugel {{$nom_ugel_seleccionada}}</label>
                    <label class="col-sm-4">Institución {{$nom_institucion_seleccionada}}</label>
                    <label class="col-sm-1">Sección {{$seccion_seleccionada}}</label>
                    <label class="col-sm-2">Alumno: {{$alumno_seleccionado}}</label>
                </div>
            </div>
        </div>
    @if (count($resultados)>0)
    <br>
    <div class="card">
        <div class="card-header">
            Resultado de la búsqueda
        </div>
        <table class="table table-striped table-bordered table-hover table-sm">
            <thead>
                <tr>
                    <th>PREGUNTA</th>
                    <th>RESPUESTA</th>
                    <th>COMENTARIO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($resultados as $resultado)
                    <tr>
                        <td>{{ $resultado->pregunta }}</td>
                        <td>{{ $resultado->respuesta }}</td>
                        <td>{{ $resultado->comentario }}</td>
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