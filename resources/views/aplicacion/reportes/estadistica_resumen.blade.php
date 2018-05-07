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

    {!! Form::open(['url' => 'reporte/estadistica_resumen']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                Criterio de búsqueda
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Evaluación</label>
                    <?php /*-- {!! Form::select('cod_evaluacion', $evaluaciones, null, ['class' => 'form-control']) !!} --> */ ?>
                    {!! Form::text('cod_evaluacion', $evaluacion_seleccionada, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::submit('Buscar', ['class' => 'btn btn-success']) !!}
                </div>
            </div>
            <div class="panel-heading">
                Resultado de la búsqueda
            </div>
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
                            <td>{{ $resultado['n']}}</td>
                            <td>{{ $resultado['ugel']}}</td>
                            <td>{{ $resultado['nalumnos']}}</td>
                            <td>{{ $resultado['n1']}}</td>
                            <td>{{ $resultado['n2']}}</td>
                            <td>{{ $resultado['n3']}}</td>
                            <td>{{ $resultado['n4']}}</td>
                            <td>{{ $resultado['p1']}}</td>
                            <td>{{ $resultado['p2']}}</td>
                            <td>{{ $resultado['p3']}}</td>
                            <td>{{ $resultado['p4']}}</td>
                        </tr>
                    @endforeach                    
                </tbody>
            </table>
        </div>
    {!! Form::close() !!}
@endsection