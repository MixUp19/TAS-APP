<?php

namespace App\Providers;
use App\DomainModels\Medicamento;
use App\DomainModels\LineaReceta;
use App\DomainModels\Sucursal;
use App\Models\Medicamento as MedicamentoModel;

class MedicamentoRepository
{
    public function buscarMedicamentosEnSucursales(Sucursal $sucursal, array $sucursales, array $lineas): array{
        foreach($lineas as $linea) {
            $total = $linea->getCantidad();
            $actual = $this->obtenerExistenciaMedicamento($sucursales[$i] ,$linea->getMedicamentoId(), $total);
            $i =0;
            while($total > $actual){
                $stock = $this->obtenerExistenciaMedicamento($sucursales[$i] ,$linea->getMedicamentoId(), $total);
                $actual += $stock;
                $i++;
                if($stock > 0) {
                    $linea->anadirSucursal($sucursal, $stock);
                }
            }
        }
        return $lineas;
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
