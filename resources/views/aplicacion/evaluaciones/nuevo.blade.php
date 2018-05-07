@extends('layout.aplicacion')


@section('content')
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="/evaluaciones">Evaluaciones</a></li>
        <li class="breadcrumb-item active">Nuevo</li>
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
                        {!! Form::text('cod_evaluacion', $evaluacion->cod_evaluacion, ['class' => 'form-control', 'readonly', '']) !!}
                    </div>
                    <div class="col-2">
                        <label>Grado</label>
                        {!! Form::selectRange('num_grado', 10, 20, null, ['class' => 'form-control', '']) !!}
                    </div>
                    <div class="col-2">
                        <label>Año</label>
                        {!! Form::selectRange('num_anio', 2016, date('Y'), null, ['class' => 'form-control', '']) !!}
                    </div>
                    <div class="col-2">
                        <label>Correlativo</label>
                        {!! Form::selectRange('num_correlativo', 1, 300, null, ['class' => 'form-control', '']) !!}
                    </div>
                    <div class="col-2">
                        <label>Tipo</label>
                        {!! Form::select('num_tipo', array(
                            null => null,
                            1 => 'Matematica', 
                            2 => 'Comunicacion',
                            3 => 'Ciencias sociales'
                        ), null, ['class' => 'form-control', '']) !!}
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
                    @foreach ($evaluacion->detalle as $detalle)
                        <tr>
                            <td>{{ $detalle->num_pregunta }}</td>
                            <td>
                                {!! Form::select('num_respuesta[]', array(
                                    null => null,
                                    1 => 1, 
                                    2 => 2,
                                    3 => 3,
                                    4 => 4,
                                    5 => 5
                                ), null, ['class' => 'form-control', '']) !!}
                            </td>
                            <td>{!! Form::text('nom_mensaje[]', $detalle->nom_mensaje, ['class' => 'form-control', '']) !!}</td>
                            <td>{!! Form::selectRange('num_peso[]', 1, 5, null, ['class' => 'form-control', '']) !!}</td>
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
        // $("#formulario").validate({
        //     submitHandler: function(form) {
        //         // some other code
        //         // maybe disabling submit button
        //         // then:
        //         $(form).submit();
        //     }
        // });
    </script>

@endsection