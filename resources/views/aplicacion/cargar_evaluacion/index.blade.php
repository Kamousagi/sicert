@extends('layout.aplicacion')

@section('content')
    <ul class="breadcrumb">
        <li class="breadcrumb-item active">Cargar evaluación</li>
    </ul>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('exito'))
        <div class="alert alert-success">
            {{ session('exito') }}
        </div>
    @endif

    {!! Form::open(array('url' => 'cargar_evaluacion/guardar', 'method' => 'post', 'files' => true)) !!}
    
        <div class="card">
            <div class="card-header">
                Datos de carga de evaluación
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <label>Evaluación</label>
                        {!! Form::select('cod_evaluacion', $evaluaciones, null, ['placeholder' => 'Seleccione una evaluación', 'class' => 'form-control']) !!}
                    </div>
                    <div class="col-6">
                        <label>Archivo</label>
                        {!! Form::file('archivo', ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-success">Guardar <i class="fas fa-check-circle"></i></button>
                <a href="/evaluaciones" class="btn btn-danger">Cancelar <i class="fas fa-times-circle"></i></a>
            </div>

        </div>
       
    {!! Form::close() !!}
@endsection