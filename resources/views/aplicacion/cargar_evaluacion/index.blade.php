@extends('layout.aplicacion')

@section('content')
    <ul class="breadcrumb">
        <li class="breadcrumb-item active">Cargar evaluación</li>
    </ul>

    {!! Form::open(array('url' => 'cargar_evaluacion/guardar', 'method' => 'post', 'files' => true)) !!}
    
        <div class="card">
            <div class="card-header">
                Datos de carga de evaluación
            </div>
            <div class="card-body">
                <div>
                    {{ $modelo->cod_evaluacion }}
                    {!! Form::select('size', $evaluaciones) !!}
                </div>
                <div class="row">
                    <div class="col-6">
                        <label>Archivo</label>
                        {!! Form::file('archivo', ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
                <a href="/evaluaciones" class="btn btn-danger">Cancelar</a>
            </div>

        </div>
       
    {!! Form::close() !!}
@endsection