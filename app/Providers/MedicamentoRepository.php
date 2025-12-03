<?php

namespace App\Providers;
use App\DomainModels\Medicamento;
use App\DomainModels\LineaReceta;
use App\DomainModels\Sucursal;
use App\Models\Medicamento as MedicamentoModel;
use App\Models\Inventario;
use Illuminate\Support\Facades\DB;

class MedicamentoRepository
{
   public function buscarMedicamentosEnSucursales(Sucursal $sucursalPrincipal, array $sucursales, array $lineas): array
    {
        return DB::transaction(function () use ($sucursalPrincipal, $sucursales, $lineas) {
            foreach ($lineas as $linea) {
                $cantidadRequerida = $linea->getCantidad();
                $cantidadAcumulada = 0;
                $i = 0;
                while ($cantidadAcumulada < $cantidadRequerida && $i < count($sucursales)) {
                    $sucursalActual = $sucursales[$i];
                    $cantidadFaltante = $cantidadRequerida - $cantidadAcumulada;
                    $stockDisponible = $this->obtenerExistenciaMedicamento(
                        $sucursalActual,
                        $linea->getMedicamentoId(),
                        $cantidadFaltante
                    );
                    if ($stockDisponible > 0) {
                        $linea->anadirSucursal($sucursalActual, $stockDisponible);
                        $this->restarExistencias(
                            $sucursalActual,
                            $linea->getMedicamentoId(),
                            $stockDisponible
                        );
                        $cantidadAcumulada += $stockDisponible;
                    }
                    $i++;
                }
            }
            return $lineas; 
        });
    }
    private function restarExistencias(Sucursal $sucursal, int $medicamentoId, int $cantidad): void
    {
        $inventario = Inventario::where('SucursalID', $sucursal->getSucursalId())
            ->where('CadenaID', $sucursal->getCadena()->getCadenaId())
            ->where('MedicamentoID', $medicamentoId)
            ->first();
        if ($inventario) {
            $inventario->InventarioCantidad -= $cantidad;
            $inventario->save();
        }
    }
    private function obtenerExistenciaMedicamento(Sucursal $sucursal, int $medicamentoId, int $cantidadSolicitada): int
    {
        $inventario = Inventario::where('SucursalID', $sucursal->getSucursalId())
            ->where('CadenaID', $sucursal->getCadena()->getCadenaId())
            ->where('MedicamentoID', $medicamentoId)
            ->lockForUpdate()
            ->first();

        if (!$inventario) {
            return 0;
        }

        $stockDisponible = $inventario->InventarioCantidad;

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
