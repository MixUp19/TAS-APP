<?php

namespace App\Providers;

use App\Models\Sucursal as SucursalModel;
use PHPUnit\Framework\Attributes\Group;

class SucursalRepository
{
    public function getSucursalesPorCiudad($ciudadId):array {

    }

    public function listarSucursales(){
        $sucursales = SucursalModel::all();
        return $sucursales->groupBy('CadenaID');
    }
}