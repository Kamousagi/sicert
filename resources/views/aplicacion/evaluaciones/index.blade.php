@extends('layout.aplicacion')


@section('content')
    <h1>Evaluaciones</h1>

    <table class="table table-hover table-bordered table-condensed">
        <thead>
            <tr>
                <th>asdfas</th>
                <th>asdfas</th>
                <th>asdfas</th>
                <th>asdfas</th>
                <th>asdfas</th>
                <th><a href="/evaluaciones/nuevo" class="btn btn-success">Agregar</a></th>
            </tr>
        <tbody>
            @foreach($evaluaciones as $evaluacion)
            <tr>
                <td>{{$evaluacion['cod_evaluacion']}}</td>
                <td>{{$evaluacion['num_grado']}}</td>
                <td>{{$evaluacion['num_anio']}}</td>
                <td>{{$evaluacion['num_correlativo']}}</td>
                <td>{{$evaluacion['num_tipo']}}</td>
                <td><a href="/evaluaciones/modificar/2" class="btn btn-warning">Editar</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection