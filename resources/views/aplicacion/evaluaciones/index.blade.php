@extends('layout.aplicacion')


@section('content')
    <ul class="breadcrumb">
        <li class="breadcrumb-item active">Evaluaciones</li>
    </ul>

    <table class="table table-hover table-bordered table-condensed table-sm">
        <thead>
            <tr>
                <th style="width: 20%">Código</th>
                <th style="width: 20%">Grado</th>
                <th style="width: 20%">Año</th>
                <th style="width: *">Correlativo</th>
                <th style="width: 30%">Tipo</th>
                <th style="width: 10%"><a href="/evaluaciones/nuevo" class="btn btn-sm btn-success">Agregar <i class="fa fa-plus-circle"></i></a></th>
            </tr>
        <tbody>
            @foreach($evaluaciones as $evaluacion)
            <tr>
                <td>{{$evaluacion['cod_evaluacion']}}</td>
                <td>{{$evaluacion['num_grado']}}</td>
                <td>{{$evaluacion['num_anio']}}</td>
                <td>{{$evaluacion['num_correlativo']}}</td>
                <td>{{$evaluacion['num_tipo']}}</td>
                <td><a href="/evaluaciones/modificar/2" class="btn btn-sm btn-warning">Editar <i class="fas fa-edit"></i></a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection