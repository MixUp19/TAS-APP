<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorProcesarReceta;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/receta/seleccionar-medicamentos', [ControladorProcesarReceta::class, 'obtenerMedicamentos'])->name('receta.seleccionarMedicamentos');

Route::post('/receta/guardarMedicamentos', [ControladorProcesarReceta::class, 'guardarMedicamentos'])->name('medicamentos.add');


//rutas en fase de desarrollo
Route::get('/receta/formulario', [ControladorProcesarReceta::class, 'obtenerSucursales'])
    ->name('receta.formulario');

Route::post('/receta/guardar-encabezado', [ControladorProcesarReceta::class, 'seleccionarSucursal'])
    ->name('receta.guardarEncabezado');
