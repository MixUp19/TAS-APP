<?php

namespace App\Providers;

use App\Models\Sucursal as SucursalModel;
use App\Models\Cadena as CadenaModel;
use App\DomainModels\Cadena;
use App\DomainModels\Sucursal;
use PHPUnit\Framework\Attributes\Group;

class SucursalRepository
{
    public function getSucursalesPorCiudad($ciudadId):array {
        $sucursales = SucursalModel::where('CiudadID', $ciudadId)->get();
        $sucursalesDomain = [];
        foreach ($sucursales as $sucursal) {
            $sucursalesDomain[] = $this->eloquentToDomain($sucursal);
        }
        return $sucursalesDomain;
    }

    public function listarSucursales():array{
        $sucursales = SucursalModel::all();
        $sucursales = $sucursales->groupBy('CadenaID');
        $sucursalesDomain = [];
        foreach ($sucursales as  $cadenaId => $sucursalesCadena) {
            foreach ($sucursalesCadena as $sucursal) {
                $sucursalesDomain[] = $this->eloquentToDomain($sucursal);
            }
        }
        return $sucursalesDomain;
    }
    public function obtenerSucursal($sucursalId, $cadenaId):?Sucursal{
        $sucursales = SucursalModel::findByKeys($sucursalId,$cadenaId);
        if ($sucursales) {
            return $this->eloquentToDomain($sucursales);
        }
        return null;
    }

    private function eloquentToDomain(SucursalModel $sucursal):Sucursal{
        $cadena = $this->getCadena($sucursal['CadenaID']);
        return new Sucursal(
            $cadena,
            $sucursal['SucursalID'],
            $sucursal['SucursalColonia'],
            $sucursal['SucursalCalle'],
            $sucursal['SucursalLatitud'],
            $sucursal['SucursalLongitud'],
            $sucursal['CiudadID']
        );
    }

    private function getCadena($cadenaId):?Cadena{
        $cadena = CadenaModel::find($cadenaId);
        if ($cadena) {
            return new Cadena($cadena['CadenaID'], $cadena['CadenaNombre']);
        }
        return null;
    }
}
