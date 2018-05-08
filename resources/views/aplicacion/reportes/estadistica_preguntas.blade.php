@extends('layout.aplicacion')
@section('content')
<h1>Estadística del resultado de las preguntas por Evaluación</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {!! Form::open(array('action' => array('ReporteController@estadistica_preguntas'))) !!}
        <div class="card">
            <div class="card-header">
                Criterio de búsqueda
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <label>Evaluación</label>
                        {!! Form::select('cod_evaluacion', $evaluaciones, $evaluacion_seleccionada, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                {!! Form::submit('Buscar', ['class' => 'btn btn-success']) !!}
            </div>
        </div>
    {!! Form::close() !!}
    <div class="panel panel-default">
        <div class="panel-heading">
            Resultado de la búsqueda
        </div>
        <div class="panel-body">
        <table class="table">
                <thead>
                    <tr>
                        <th>PREGUNTA</th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                        <th>9</th>
                        <th>10</th>
                        <th>11</th>
                        <th>12</th>
                        <th>13</th>
                        <th>14</th>
                        <th>15</th>
                        <th>16</th>
                        <th>17</th>
                        <th>18</th>
                        <th>19</th>
                        <th>20</th>
                        <th>21</th>
                        <th>22</th>
                        <th>23</th>
                        <th>24</th>
                        <th>25</th>                        
                    </tr>                    
                </thead>
                <tbody>
                    @foreach ($resultados as $resultado)
                        <tr>
                            <td>{{ $resultado->pregunta }}</td>
                            <td>{{ $resultado->p1[0]->p1 }}</td>
                            <td>{{ $resultado->p2[0]->p2 }}</td>
                            <td>{{ $resultado->p3[0]->p3 }}</td>
                            <td>{{ $resultado->p4[0]->p4 }}</td>
                            <td>{{ $resultado->p5[0]->p5 }}</td>
                            <td>{{ $resultado->p6[0]->p6 }}</td>
                            <td>{{ $resultado->p7[0]->p7 }}</td>
                            <td>{{ $resultado->p8[0]->p8 }}</td>
                            <td>{{ $resultado->p9[0]->p9 }}</td>
                            <td>{{ $resultado->p10[0]->p10 }}</td>
                            <td>{{ $resultado->p11[0]->p11 }}</td>
                            <td>{{ $resultado->p12[0]->p12 }}</td>
                            <td>{{ $resultado->p13[0]->p13 }}</td>
                            <td>{{ $resultado->p14[0]->p14 }}</td>
                            <td>{{ $resultado->p15[0]->p15 }}</td>
                            <td>{{ $resultado->p16[0]->p16 }}</td>
                            <td>{{ $resultado->p17[0]->p17 }}</td>
                            <td>{{ $resultado->p18[0]->p18 }}</td>
                            <td>{{ $resultado->p19[0]->p19 }}</td>
                            <td>{{ $resultado->p20[0]->p20 }}</td>
                            <td>{{ $resultado->p21[0]->p21 }}</td>
                            <td>{{ $resultado->p22[0]->p22 }}</td>
                            <td>{{ $resultado->p23[0]->p23 }}</td>
                            <td>{{ $resultado->p24[0]->p24 }}</td>
                            <td>{{ $resultado->p25[0]->p25 }}</td>
                        </tr>
                    @endforeach                    
                </tbody>
            </table>   
        </div>
    </div>
@endsection