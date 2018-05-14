@extends('layout.aplicacion')
@section('content')

    <ul class="breadcrumb">
        <li class="breadcrumb-item active">Cronograma de evaluaciones pendientes</li>
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
    <table class="table table-striped table-bordered table-hover table-sm">
        <thead>
            <tr>
                <th>FECHA</th>
                <th>GRADO</th>
                <th>TIPO</th>
                <th>AÑO</th>
                <th>CORRELATIVO</th>
            </tr>                    
        </thead>
        <tbody>
        @foreach ($evaluaciones as $evaluacion)
            <tr>
                <td>{{$evaluacion->fec_fecha}}</td>
                <td>{{$evaluacion->num_grado}}</td>
                <td>{{$evaluacion->num_tipo}}</td>
                <td>{{$evaluacion->num_anio}}</td>
                <td>{{$evaluacion->num_correlativo}}</td>                
            </tr>
        @endforeach                    
        </tbody>
    </table>
@endsection