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

Route::get('/cargar_evaluacion', 'CargarEvaluacionController@index');
Route::post('/cargar_evaluacion/guardar', 'CargarEvaluacionController@guardar');

Route::get('/evaluaciones', 'EvaluacionController@index');
Route::get('/evaluaciones/nuevo', 'EvaluacionController@nuevo');
Route::post('/evaluaciones/guardar', 'EvaluacionController@guardar');

Route::get('/reportes', function () { return view('aplicacion.reportes.index'); });
Route::get('/reportes/cronograma_evaluacion', 'ReporteController@cronograma_evaluacion');
Route::post('/reportes/estadistica_detallado', 'ReporteController@estadistica_detallado');
Route::post('/reportes/estadistica_preguntas', 'ReporteController@estadistica_preguntas');
Route::post('/reportes/estadistica_resumen', 'ReporteController@estadistica_resumen');