<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControladorProcesarReceta;
use App\Http\Controllers\ControladorDevolverReceta;
use App\Http\Controllers\ControladorSesiones;
use Illuminate\Http\Request;

// ===== Rutas de Autenticación =====
Route::get('/login', [ControladorSesiones::class, 'mostrarLogin'])->name('login');
Route::post('/login', [ControladorSesiones::class, 'iniciarSesion'])->name('iniciar.sesion');
Route::post('/registro', [ControladorSesiones::class, 'registrarUsuario'])->name('registrar.usuario');
Route::post('/logout', [ControladorSesiones::class, 'cerrarSesion'])->name('cerrar.sesion');

// Rutas temporales para dashboards (puedes reemplazarlas con las vistas reales)
Route::get('/paciente/dashboard', function () {
    return view('paciente.dashboard');
})->name('paciente.dashboard');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/', [ControladorSesiones::class, 'mostrarLogin'])->name('login');

Route::get('/receta/buscar', [ControladorProcesarReceta::class, 'buscarReceta'])->name('receta.buscar');

Route::get('/receta/mis-recetas', [ControladorProcesarReceta::class, 'obtenerRecetasPaciente'])->name('receta.mis-recetas');

Route::get('/farmaceutico/buscar', [ControladorDevolverReceta::class, 'buscarReceta'])->name('farmaceutico.buscar');

Route::get('/receta/seleccionar-medicamentos', [ControladorProcesarReceta::class, 'obtenerMedicamentos'])->name('receta.seleccionarMedicamentos');

Route::post('/receta/guardarMedicamentos', [ControladorProcesarReceta::class, 'seleccionarMedicamentos'])->name('medicamentos.add');

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

Route::get('/receta/detalle/{folio}', [ControladorDevolverReceta::class, 'obtenerReceta'])
    ->name('receta.detalle');

Route::post('/receta/cambiar-estado', [ControladorDevolverReceta::class, 'cambiarEstado'])
    ->name('receta.cambiarEstado');

Route::post('/receta/cancelar', [ControladorDevolverReceta::class, 'cancelarReceta'])
    ->name('receta.cancelar');

Route::get('/receta/devolver/{folio}', [ControladorDevolverReceta::class, 'obtenerReceta'])
    ->name('receta.devolver');

