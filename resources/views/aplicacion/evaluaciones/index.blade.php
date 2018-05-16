@extends('layout.aplicacion')


@section('content')
    <ul class="breadcrumb">
        <li class="breadcrumb-item active">Evaluaciones</li>
    </ul>

    <div class="card">
        <div class="card-header">
            Resultado de evaluaciones <span class="badge badge-secondary">{{ count($evaluaciones) }}</span>
        </div>
        <table class="table table-striped table-hover table-hover table-sm">
            <thead>
                <tr>                    
                    <th style="width: 20%">Grado</th>
                    <th style="width: 20%">Año</th>
                    <th style="width: *">Correlativo</th>
                    <th style="width: 30%">Tipo</th>
                    <th style="width: 10%">
                        {{-- <a href="/evaluaciones/nuevo" class="btn btn-sm btn-success">Agregar <i class="fa fa-plus-circle"></i></a> --}}
                        <button class="btn btn-sm btn-success" onclick="$('#nuevo').modal('show');">Agregar</button>
                    </th>
                </tr>
            <tbody>
                @foreach($evaluaciones as $evaluacion)
                <tr>
                    <td>{{$evaluacion['num_grado']}}</td>
                    <td>{{$evaluacion['num_anio']}}</td>
                    <td>{{$evaluacion['num_correlativo']}}</td>
                    <td>{{$evaluacion['num_tipo']}}</td>
                    <td><a href="/evaluaciones/editar/{{$evaluacion['cod_evaluacion']}}" class="btn btn-sm btn-warning">Editar <i class="fas fa-edit"></i></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal" id="nuevo">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Cantidad de preguntas</label>
                        <input type="text" class="form-control input-sm" id="cantidad-preguntas">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-success" onclick="crearEvaluacion()">
                        Agregar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function crearEvaluacion() {
            cantidadPreguntas = document.getElementById("cantidad-preguntas").value;
            window.location.href = "/evaluaciones/nuevo/" + cantidadPreguntas;
        }
    </script>

@endsection