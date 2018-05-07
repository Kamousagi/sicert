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

<<<<<<< HEAD
Route::get('/reportes', function () { return view('aplicacion.reportes.index'); });
Route::get('/reportes/cronograma_evaluacion', 'ReporteController@cronograma_evaluacion');
Route::post('/reportes/estadistica_detallado', 'ReporteController@estadistica_detallado');
Route::post('/reportes/estadistica_preguntas', 'ReporteController@estadistica_preguntas');
Route::post('/reportes/estadistica_resumen', 'ReporteController@estadistica_resumen');
=======
    Route::get('/logout', 'AutenticacionController@logout');

    Route::get('/cargar_evaluacion', 'CargarEvaluacionController@index');
    Route::post('/cargar_evaluacion/guardar', 'CargarEvaluacionController@guardar');

    Route::get('/evaluaciones', 'EvaluacionController@index');
    Route::get('/evaluaciones/nuevo', 'EvaluacionController@nuevo');
    Route::post('/evaluaciones/guardar', 'EvaluacionController@guardar');

    Route::get('/reportes', function () { return view('aplicacion.reportes.index'); });
    Route::get('/reportes/cronograma_evaluacion', 'ReporteController@cronograma_evaluacion');
    Route::get('/reportes/estadistica_detallado/{cod_eval}/{cod_ugel}', 'ReporteController@estadistica_detallado');
    Route::get('/reportes/estadistica_preguntas/{cod_eval}', 'ReporteController@estadistica_preguntas');
    Route::get('/reportes/estadistica_resumen/{cod_eval}', 'ReporteController@estadistica_resumen');
    
});
>>>>>>> 9e904f4d01d23e83a4ef0e3974b26bb8a8ca8eb8
