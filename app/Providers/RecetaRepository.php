<?php

namespace App\Providers;

use App\DomainModels\Receta as RecetaDomain;
use App\Models\Receta as RecetaModel;
use App\Models\LineaReceta as LineaRecetaModel;
use App\Models\DetalleLineaReceta as DetalleLineaRecetaModel;
use Illuminate\Support\Facades\DB;

class RecetaRepository
{

    public function guardarReceta(RecetaDomain $receta): int
    {
        return DB::transaction(function () use ($receta) {
            // 1. Guardar el encabezado de la receta
            $recetaModel = RecetaModel::create([
                'CedulaDoctor' => $receta->getCedulaDoctor(),
                'RecetaFecha' => $receta->getFecha()->format('Y-m-d'),
                'PacienteID' => $receta->getPaciente()->getId(),
                'CadenaID' => $receta->getSucursal()->getCadena()->getCadenaId(),
                'SucursalID' => $receta->getSucursal()->getSucursalId(),
            ]);
            
            $folio = $recetaModel->RecetaFolio;
            
            // 2. Guardar las líneas de medicamentos
            foreach ($receta->getLineasRecetas() as $lineaReceta) {
                $medicamento = $lineaReceta->getMedicamento();
                
                // Crear línea de receta
                LineaRecetaModel::create([
                    'RecetaFolio' => $folio,
                    'MedicamentoID' => $medicamento->getId(),
                    'LRCantidad' => $lineaReceta->getCantidad(),
                    'LRPrecio' => $medicamento->getPrecio(),
                ]);
                
                // 3. Guardar los detalles de la línea (distribución por sucursales)
                foreach ($lineaReceta->getDetalleLineaReceta() as $detalle) {
                    $sucursal = $detalle->getSucursal();
                    
                    DetalleLineaRecetaModel::create([
                        'RecetaFolio' => $folio,
                        'MedicamentoID' => $medicamento->getId(),
                        'SucursalID' => $sucursal->getSucursalId(),
                        'CadenaID' => $sucursal->getCadena()->getCadenaId(),
                        'DLRCantidad' => $detalle->getCantidad(),
                        'DLREstatus' => 'Pendiente', // Estado inicial
                    ]);
                }
            }
            
            return $folio;
        });
    }
    
    /**
     * Obtiene una receta por su folio (para futuras consultas)
     * 
     * @param int $folio
     * @return RecetaModel|null
     */
    public function obtenerRecetaPorFolio(int $folio): ?RecetaModel
    {
        return RecetaModel::with(['lineas.medicamento', 'paciente'])
            ->find($folio);
    }
}
