<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorProcesarReceta;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/seleccionar-medicamentos', [ControladorProcesarReceta::class, 'obtenerMedicamentos'])->name('receta.seleccionarMedicamentos');

Route::post('/seleccionar-medicamento', [ControladorProcesarReceta::class, 'seleccionarMedicamento'])->name('medicamentos.add');


//rutas en fase de desarrollo 
Route::get('/receta/formulario', function () {
    
   
    $sucursal = new \stdClass();
    $sucursal->id = 99;
    $sucursal->nombre = "Sucursal Central";
    return view('receta.formularioReceta', compact('sucursal'));
});

Route::post('/receta/guardar-encabezado', [ControladorProcesarReceta::class, 'guardarEncabezado'])
    ->name('receta.guardarEncabezado');