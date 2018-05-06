@extends('layout.aplicacion')

@section('content')
    <h1>Cargar evaluacion</h1>
    {!! Form::open(array('url' => 'cargar_evaluacion/guardar', 'method' => 'post', 'files' => true)) !!}
    
        <div class="form-group">
            <label>Archivo</label>
            {!! Form::file('archivo') !!}
        </div>

        <div class="panel-footer text-right">
            {!! Form::submit('Guardar', ['class' => 'btn btn-success']) !!}
            <a href="/evaluaciones" class="btn btn-danger">Cancelar</a>
        </div>
        
    {!! Form::close() !!}
@endsection