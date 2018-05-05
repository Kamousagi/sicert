@extends('layout.aplicacion')


@section('content')
    <h1>Evaluaciones - Nuevo</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::open(['url' => 'evaluaciones/guardar']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                Mantenimiento de evaluacion
            </div>
            <div class="panel-body">
                
                    <div class="form-group col-sm-4">
                        <label>Código</label>
                        {!! Form::text('cod_evaluacion', $evaluacion->cod_evaluacion, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Grado</label>
                        {!! Form::text('num_grado', '', ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Año</label>
                        {!! Form::text('num_anio', '', ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Correlativo</label>
                        {!! Form::text('num_correlativo', '', ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group col-sm-4">
                        <label>Tipo</label>
                        {!! Form::text('num_tipo', '', ['class' => 'form-control']) !!}
                    </div>
                    
            </div>
            <div class="panel-heading">
                Detalle de evaluación
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nº</th>
                        <th>Respuesta</th>
                        <th>Mensaje</th>
                    </tr>                    
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                        </td>
                        <td>
                        </td>
                    </tr>                    
                </tbody>
            </table>
            <div class="panel-footer text-right">
                {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
                <a href="/evaluaciones" class="btn btn-danger">Cancelar</a>
            </div>
        </div>

    {!! Form::close() !!}

@endsection