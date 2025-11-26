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
        return $request->session()->get('proceso_receta', new ModeloDevolverReceta());
    }

    private function guardarModelo(Request $request, ModeloDevolverReceta $modelo): void
    {
        $request->session()->put('proceso_receta', $modelo);
    }

    public function obtenerRecetas(Request $request)
    {

        $modelo = $this->obtenerOInicializarModelo($request);

        $recetas = $modelo->obtenerRecetas();

        return view('receta.indice-recetas', [
            'recetas' => $recetas
        ]);
    }
    public function cancelarReceta(Request $request){
        $modelo = $this->obtenerOInicializarModelo($request);
        $modelo->cancelarPedido($request->input('folio'));
        $modelo->confirmarCancelacion();
    }
}
