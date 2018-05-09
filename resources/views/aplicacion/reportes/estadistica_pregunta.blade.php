@extends('layout.aplicacion')
@section('content')
<h1>Estadística de la evaluación por pregunta</h1>

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
                    <div class="col-3">
                        <label>Alumno: {{$alumno_seleccionado}}</label>                        
                    </div>
                </div>
            </div>
        </div>
    
    <div class="panel panel-default">
        <div class="panel-heading">
            Resultado de la búsqueda
        </div>
        <div class="panel-body">
            <table class="table">
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
    </div>
    
@endsection