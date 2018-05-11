<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers;

Route::get('login', [ 'as' => 'login', 'uses' => 'AutenticacionController@index']);
Route::post('/login', 'AutenticacionController@login');

Route::middleware(['auth'])->group(function()
{
    Route::get('/', function () { return view('aplicacion.portada.index'); });
    Route::get('/logout', 'AutenticacionController@logout');

    Route::get('/cargar_evaluacion', 'CargarEvaluacionController@index');
    Route::post('/cargar_evaluacion/guardar', 'CargarEvaluacionController@guardar');

    Route::get('/evaluaciones', 'EvaluacionController@index');
    Route::get('/evaluaciones/nuevo', 'EvaluacionController@getNuevo');
    Route::get('/evaluaciones/editar/{cod_evaluacion}', 'EvaluacionController@getEditar');
    Route::post('/evaluaciones/guardar', 'EvaluacionController@postGuardar');

    Route::get('/reportes', function () { return view('aplicacion.reportes.index'); });
    Route::get('/reportes/cronograma_evaluacion', 'ReporteController@cronograma_evaluacion');
    Route::post('/reportes/estadistica_detallado', 'ReporteController@estadistica_detallado');
    Route::post('/reportes/resumen_preguntas', 'ReporteController@resumen_preguntas');
    Route::post('/reportes/estadistica_resumen', 'ReporteController@estadistica_resumen');
    Route::post('/reportes/estadistica_seccion', 'ReporteController@estadistica_seccion');
    Route::post('/reportes/estadistica_alumno', 'ReporteController@estadistica_alumno');
    Route::post('/reportes/estadistica_pregunta', 'ReporteController@estadistica_pregunta');

    Route::get('/cambiar_clave', 'CambiarClaveController@getIndex');
    Route::post('/cambiar_clave', 'CambiarClaveController@postIndex');
});
