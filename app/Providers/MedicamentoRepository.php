<?php

namespace App\Providers;
use App\DomainModels\Medicamento;
use App\DomainModels\LineaReceta;
use App\DomainModels\Sucursal;
use App\Models\Medicamento as MedicamentoModel;
use App\Models\Inventario;

class MedicamentoRepository
{
   public function buscarMedicamentosEnSucursales(Sucursal $sucursalPrincipal, array $sucursales, array $lineas): array
    {
        foreach($lineas as $linea) {
            $cantidadRequerida = $linea->getCantidad();
            $cantidadAcumulada = 0;
            $i = 0;
            
            // Buscar en sucursales hasta completar la cantidad requerida
            while($cantidadAcumulada < $cantidadRequerida && $i < count($sucursales)) {
                $sucursalActual = $sucursales[$i];
                $cantidadFaltante = $cantidadRequerida - $cantidadAcumulada;
                
                // Obtener stock disponible en esta sucursal
                $stockDisponible = $this->obtenerExistenciaMedicamento(
                    $sucursalActual, 
                    $linea->getMedicamentoId(), 
                    $cantidadFaltante
                );
                
                if($stockDisponible > 0) {
                    // Añadir detalle de esta sucursal a la línea
                    $linea->anadirSucursal($sucursalActual, $stockDisponible);
                    $cantidadAcumulada += $stockDisponible;
                }
                
                $i++;
            }
            

        }
        return $lineas;
    }
    
    private function obtenerExistenciaMedicamento(Sucursal $sucursal, int $medicamentoId, int $cantidadSolicitada): int
    {
        $inventario = Inventario::where('SucursalID', $sucursal->getSucursalId())
            ->where('CadenaID', $sucursal->getCadena()->getCadenaId())
            ->where('MedicamentoID', $medicamentoId)
            ->first();
        
        if (!$inventario) {
            return 0;
        }
        
        $stockDisponible = $inventario->InventarioCantidad;
        
        // Retornar el menor entre lo disponible y lo solicitado
        return min($stockDisponible, $cantidadSolicitada);
    }
    public function obtenerMedicamentoPorId($id){
        $medicamentoModel = MedicamentoModel::where('MedicamentoID', '=', $id)->first();

        if ($medicamentoModel) {
            return $this->eloquentToDomain($medicamentoModel);
        }
        return null;
    }
    public function obtenerMedicamentoPorNombre($nombre){
        $medicamentoModel = MedicamentoModel::where('MedicamentoNombre', 'like', '%' . $nombre . '%')->first();
        if ($medicamentoModel) {
            return $this->eloquentToDomain($medicamentoModel);
        }
        return null;
    }
    public function obtenerMedicamentos():array{
        $models = MedicamentoModel::all();
        $domain =[];
        foreach($models as $model){
            $domain[] = $this->eloquentToDomain($model);
        }
        return $domain;
    }

    private function eloquentToDomain(MedicamentoModel $medicamento):Medicamento{
        return new Medicamento(
            $medicamento['MedicamentoID'],
            $medicamento['MedicamentoNombre'],
            $medicamento['MedicamentoCompuestoActivo'],
            $medicamento['MedicamentoPrecio'],
            $medicamento['MedicamentoUnidad'],
            $medicamento['MedicamentoContenido']
        );
    }
}
