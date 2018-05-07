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

Route::get('/', function () { return view('aplicacion.portada.index'); });
Route::get('/cargar_evaluacion', function () { return view('aplicacion.cargar_evaluacion.index'); });

Route::get('/evaluaciones', 'EvaluacionController@index');
Route::get('/evaluaciones/nuevo', 'EvaluacionController@nuevo');
Route::post('/evaluaciones/guardar', 'EvaluacionController@guardar');

Route::get('/reportes', function () { return view('aplicacion.reportes.index'); });
Route::get('/reportes/cronograma_evaluacion', 'ReporteController@cronograma_evaluacion');
Route::get('/reportes/estadistica_detallado/{cod_eval}/{cod_ugel}', 'ReporteController@estadistica_detallado');
Route::get('/reportes/estadistica_preguntas/{cod_eval}', 'ReporteController@estadistica_preguntas');
Route::get('/reportes/estadistica_resumen/{cod_eval}', 'ReporteController@estadistica_resumen');