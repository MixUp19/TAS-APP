<?php

namespace App\Http\Controllers;

use App\Domain\ModeloDevolverReceta;
use Illuminate\Http\Request;

class ControladorDevolverReceta
{
    public function __construct()
    {
    }

    private function obtenerOInicializarModelo(Request $request): ModeloDevolverReceta
    {
        return $request->session()->get('devolver_receta', new ModeloDevolverReceta());
    }

    private function guardarModelo(Request $request, ModeloDevolverReceta $modelo): void
    {
        $request->session()->put('devolver_receta', $modelo);
    }

    public function obtenerRecetas(Request $request)
    {
        $modelo = $this->obtenerOInicializarModelo($request);

        $recetas = $modelo->obtenerRecetas();

        return view('receta.indice-recetas', [
            'recetas' => $recetas
        ]);
    }

    public function obtenerReceta(Request $request, $folio)
    {
        $modelo = $this->obtenerOInicializarModelo($request);

        $receta = $modelo->obtenerReceta($folio);

        \Log::info('Receta obtenida', [
            'folio' => $folio,
            'lineas_count' => $receta ? count($receta->getLineasRecetas()) : 0
        ]);

        return view('receta.receta', [
            'receta' => $receta
        ]);
    }

    public function cambiarEstado(Request $request)
    {
        $folio = $request->input('folio');
        $nuevoEstado = $request->input('estado');

        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->cambiarEstadoReceta($folio, $nuevoEstado);

        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente'
        ]);
    }

    public function cancelarReceta(Request $request){
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->cancelarReceta($request->input('folio'));
        $modelo->confirmarCancelacion();

        return response()->json([
            'success' => true,
            'message' => 'Receta cancelada y devolución notificada'
        ]);
    }

    public function buscarReceta(Request $request){
        $modelo = $this->obtenerOInicializarModelo($request);
        $receta = $modelo->obtenerReceta($request->input('folio'));
        return view('receta.indice-recetas', [
            'recetas' => [$receta]
        ]);
    }
}
