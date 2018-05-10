@extends('layout.aplicacion')


@section('content')
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="/evaluaciones">Evaluaciones</a></li>
        <li class="breadcrumb-item active">Mantenimiento</li>
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

    {!! Form::open(['url' => 'evaluaciones/guardar', 'id'=>"formulario"]) !!}

        <div class="card">
            <div class="card-header">
                Mantenimiento de evaluacion
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-2">
                        <label>Código</label>
                        {!! Form::text('cod_evaluacion', $evaluacion['cod_evaluacion'], ['class' => 'form-control', 'readonly']) !!}
                    </div>
                    <div class="col-2">
                        <label>Grado</label>                        
                        {!! Form::select('num_grado', array(
                            1 => '2° DE PRIMARIA', 
                            2 => '4° DE PRIMARIA',
                            3 => '2° DE SECUNDARIA'
                        ), $evaluacion['num_grado'], ['class' => 'form-control', 'placeholder' => 'Seleccione un grado']) !!}
                    </div>
                    <div class="col-2">
                        <label>Año</label>
                        {!! Form::selectRange('num_anio', 2016, date('Y'), $evaluacion['num_anio'], ['class' => 'form-control', 'placeholder' => 'Seleccione']) !!}
                    </div>
                    <div class="col-2">
                        <label>Correlativo</label>
                        {!! Form::text('num_correlativo', $evaluacion['num_correlativo'], ['class' => 'form-control', 'readonly']) !!}
                    </div>
                    <div class="col-2">
                        <label>Tipo</label>
                        {!! Form::select('num_tipo', array(
                            1 => 'MATEMATICA', 
                            2 => 'COMUNICACION',
                            3 => 'CTA'
                        ), $evaluacion['num_tipo'], ['class' => 'form-control', 'placeholder' => 'Seleccione un tipo']) !!}
                    </div>
                    <div class="col-2">
                        <label>Fecha</label>
                        {!! Form::text('fec_fecha', $evaluacion['fec_fecha'], ['class' => 'form-control datepicker']) !!}
                    </div>
                    <div class="col-2">
                        <label>Procesado</label>
                        {!! Form::text('ind_procesado', $evaluacion['ind_procesado'], ['class' => 'form-control', 'readonly']) !!}
                    </div>
                </div>
                    
            </div>
        </div>
        <br>
        <div class="card">
            <div class="card-header">
                Detalle de evaluación
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>Respuesta</th>
                        <th>Mensaje</th>
                        <th>Peso</th>
                    </tr>                    
                </thead>
                <tbody>
                    @foreach($evaluacion["detalle"] as $key=>$detalle)
                        <tr>
                            <td>{{ ($key + 1) }}</td>
                            <td>{!! Form::selectRange("num_respuesta[$key]", 1, 5, $detalle['num_respuesta'], ['class' => 'form-control', 'placeholder' => 'Seleccione una respuesta']) !!}</td>
                            <td>{!! Form::text("nom_mensaje[$key]", $detalle['nom_mensaje'], ['class' => 'form-control']) !!}</td>
                            <td>{!! Form::selectRange("num_peso[$key]", 1, 5, $detalle['num_peso'], ['class' => 'form-control', 'placeholder' => 'Seleccione un peso']) !!}</td>
                        </tr>                    
                    @endforeach
                </tbody>
            </table>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-success">Guardar <i class="fas fa-check-circle"></i></button>
                <a href="/evaluaciones" class="btn btn-danger">Cancelar <i class="fas fa-times-circle"></i></a>
            </div>
        </div>

    {!! Form::close() !!}

    <script type="text/javascript">
        $(document).ready(function(){
            $(".datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
        });
    </script>

@endsection