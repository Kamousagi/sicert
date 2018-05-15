@extends('layout.aplicacion')
@section('content')
    <ul class="breadcrumb">
        <li class="breadcrumb-item active">Estadística del resultado de las preguntas por Evaluación</li>
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

    {!! Form::open(array('action' => array('ReporteController@resumen_preguntas'))) !!}
        <div class="card">
            <div class="card-header">
                Criterio de búsqueda
            </div>
            <div class="card-body">

                <div class="row">
                    <label class="col-sm-1">Evaluacion</label>
                    <div class="col-sm-3">
                            {!! Form::select('cod_evaluacion', $evaluaciones, $evaluacion_seleccionada, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col-sm-2">
                            {!! Form::submit('Buscar', ['class' => 'btn btn-success']) !!}
                    </div>
                </div>

            </div>
        </div>
    {!! Form::close() !!}
    <br>
    <div class="card">
        <div class="card-header">
            Resultado de la búsqueda
        </div>
        <table class="table table-striped table-bordered table-hover table-sm">
            <thead>
                <tr>
                    <th>PREGUNTA</th>
                    <th>EN BLANCO</th>
                    <th>A</th>
                    <th>B</th>
                    <th>C</th>
                    <th>D</th>                    
                </tr>                    
            </thead>
            <tbody>
                @foreach ($resultados as $resultado)
                    <tr>
                        <td>{{ $resultado->pregunta }}</td>
                        <td>{{ $resultado->p1 }}</td>
                        <td>{{ $resultado->p2 }}</td>
                        <td>{{ $resultado->p3 }}</td>
                        <td>{{ $resultado->p4 }}</td>
                        <td>{{ $resultado->p5 }}</td>
                    </tr>
                @endforeach                    
            </tbody>
        </table>   
    </div>
@endsection