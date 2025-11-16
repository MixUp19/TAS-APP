<?php

namespace App\Providers;
use App\DomainModels\Medicamento;
use App\DomainModels\LineaReceta;
use App\DomainModels\Sucursal;

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
}
