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

        <div class="panel panel-default">
            <div class="panel-heading">
                Criterio de búsqueda
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label>Evaluación</label>                    
                    {!! Form::text('cod_evaluacion', $evaluacion_seleccionada, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::submit('Buscar', ['class' => 'btn btn-success']) !!}
                </div>
            </div>
            <div class="panel-heading">
                Resultado de la búsqueda
            </div>
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
                            <td>{{ $resultado['pregunta']}}</td>
                            <td>{{ $resultado['p1']}}</td>
                            <td>{{ $resultado['p2']}}</td>
                            <td>{{ $resultado['p3']}}</td>
                            <td>{{ $resultado['p4']}}</td>
                            <td>{{ $resultado['p5']}}</td>
                            <td>{{ $resultado['p6']}}</td>
                            <td>{{ $resultado['p7']}}</td>
                            <td>{{ $resultado['p8']}}</td>
                            <td>{{ $resultado['p9']}}</td>
                            <td>{{ $resultado['p10']}}</td>
                            <td>{{ $resultado['p11']}}</td>
                            <td>{{ $resultado['p12']}}</td>
                            <td>{{ $resultado['p13']}}</td>
                            <td>{{ $resultado['p14']}}</td>
                            <td>{{ $resultado['p15']}}</td>
                            <td>{{ $resultado['p16']}}</td>
                            <td>{{ $resultado['p17']}}</td>
                            <td>{{ $resultado['p18']}}</td>
                            <td>{{ $resultado['p19']}}</td>
                            <td>{{ $resultado['p20']}}</td>
                            <td>{{ $resultado['p21']}}</td>
                            <td>{{ $resultado['p22']}}</td>
                            <td>{{ $resultado['p23']}}</td>
                            <td>{{ $resultado['p24']}}</td>
                            <td>{{ $resultado['p25']}}</td>
                        </tr>
                    @endforeach                    
                </tbody>
            </table>
        </div>
    {!! Form::close() !!}
@endsection