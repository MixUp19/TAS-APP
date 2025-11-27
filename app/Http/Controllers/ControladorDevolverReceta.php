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

    public function obtenerDetalleReceta(Request $request, $folio)
    {
        try {
            \Log::info("Obteniendo detalle de receta con folio: {$folio}");

            $modelo = $this->obtenerOInicializarModelo($request);
            $receta = $modelo->obtenerRecetaPorFolio($folio);

            if (!$receta) {
                \Log::warning("Receta no encontrada con folio: {$folio}");
                return response()->json([
                    'success' => false,
                    'message' => 'Receta no encontrada'
                ], 404);
            }

            \Log::info("Receta encontrada: {$receta->getFolio()}");

            $lineas = array_map(function($linea) {
                return [
                    'medicamento' => $linea->getMedicamento()->getNombre(),
                    'cantidad' => $linea->getCantidad(),
                    'subtotal' => $linea->getSubtotal(),
                    'detalles' => array_map(function($detalle) {
                        return [
                            'sucursal' => $detalle->getSucursal()->getColonia(). ', '. $detalle->getSucursal()->getCalle(),
                            'cantidad' => $detalle->getCantidad(),
                            'estatus' => $detalle->getEstatus()
                        ];
                    }, $linea->getDetalleLineaReceta())
                ];
            }, $receta->getLineasRecetas());

            \Log::info("Líneas procesadas: " . count($lineas));

            return response()->json([
                'success' => true,
                'receta' => [
                    'folio' => $receta->getFolio(),
                    'fecha' => $receta->getFecha()->format('d/m/Y'),
                    'paciente' => $receta->getPaciente()->getNombre(),
                    'cedulaDoctor' => $receta->getCedulaDoctor(),
                    'estado' => $receta->getEstado(),
                    'total' => $receta->getTotal(),
                    'lineas' => $lineas
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error("Error al obtener detalle de receta: " . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los detalles de la receta',
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
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

    public function obtenerDetalleReceta(Request $request, $folio)
    {
        try {
            \Log::info("Obteniendo detalle de receta con folio: {$folio}");

            $modelo = $this->obtenerOInicializarModelo($request);
            $receta = $modelo->obtenerRecetaPorFolio($folio);

            if (!$receta) {
                \Log::warning("Receta no encontrada con folio: {$folio}");
                return response()->json([
                    'success' => false,
                    'message' => 'Receta no encontrada'
                ], 404);
            }

            \Log::info("Receta encontrada: {$receta->getFolio()}");

            $lineas = array_map(function($linea) {
                return [
                    'medicamento' => $linea->getMedicamento()->getNombre(),
                    'cantidad' => $linea->getCantidad(),
                    'subtotal' => $linea->getSubtotal(),
                    'detalles' => array_map(function($detalle) {
                        return [
                            'sucursal' => $detalle->getSucursal()->getColonia(). ', '. $detalle->getSucursal()->getCalle(),
                            'cantidad' => $detalle->getCantidad(),
                            'estatus' => $detalle->getEstatus()
                        ];
                    }, $linea->getDetalleLineaReceta())
                ];
            }, $receta->getLineasRecetas());

            \Log::info("Líneas procesadas: " . count($lineas));

            return response()->json([
                'success' => true,
                'receta' => [
                    'folio' => $receta->getFolio(),
                    'fecha' => $receta->getFecha()->format('d/m/Y'),
                    'paciente' => $receta->getPaciente()->getNombre(),
                    'cedulaDoctor' => $receta->getCedulaDoctor(),
                    'estado' => $receta->getEstado(),
                    'total' => $receta->getTotal(),
                    'lineas' => $lineas
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error("Error al obtener detalle de receta: " . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los detalles de la receta',
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
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
}
