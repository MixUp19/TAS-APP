<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorProcesarReceta;
use App\Http\Controllers\ControladorDevolverReceta;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/receta/seleccionar-medicamentos', [ControladorProcesarReceta::class, 'obtenerMedicamentos'])->name('receta.seleccionarMedicamentos');

Route::post('/receta/guardarMedicamentos', [ControladorProcesarReceta::class, 'guardarMedicamentos'])->name('medicamentos.add');

Route::get('/receta/revisar', [ControladorProcesarReceta::class, 'revisarReceta'])->name('receta.revisar');

Route::post('/receta/confirmar', [ControladorProcesarReceta::class, 'confirmarReceta'])->name('receta.confirmar');

Route::get('/receta/confirmacion/{folio}', [ControladorProcesarReceta::class, 'mostrarConfirmacion'])->name('receta.confirmacion');

Route::get('/receta/formulario', [ControladorProcesarReceta::class, 'obtenerSucursales'])
    ->name('receta.formulario');

Route::post('/receta/guardar-encabezado', [ControladorProcesarReceta::class, 'seleccionarSucursal'])
    ->name('receta.guardarEncabezado');
Route::get('/recipes/upload', function () {
    return view('subir_receta');
})->name('recipes.upload');

Route::post('/receta/escanear', [ControladorProcesarReceta::class, 'escanearReceta'])->name('receta.escanear');

Route::get('/receta/indice-recetas', [ControladorDevolverReceta::class, 'obtenerRecetas'])
    ->name('receta.indiceRecetas');

Route::get('/receta/detalle/{folio}', [ControladorDevolverReceta::class, 'obtenerDetalleReceta'])
    ->name('receta.detalle');

Route::post('/receta/cambiar-estado', [ControladorDevolverReceta::class, 'cambiarEstado'])
    ->name('receta.cambiarEstado');

Route::post('/receta/cancelar', [ControladorDevolverReceta::class, 'cancelarReceta'])
    ->name('receta.cancelar');

