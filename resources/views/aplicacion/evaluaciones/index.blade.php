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
                <th></th>
            </tr>
        <tbody>
            @foreach($evaluaciones as $evaluacion)
            <tr>
                <td>{{$evaluacion['cod_evaluacion']}}</td>
                <td>{{$evaluacion['num_grado']}}</td>
                <td>{{$evaluacion['num_anio']}}</td>
                <td>{{$evaluacion['num_correlativo']}}</td>
                <td>{{$evaluacion['num_tipo']}}</td>
                <td><button>Editar</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection