<?php

namespace App\Providers;

use App\DomainModels\Sucursal;

class LocalizadorService
{

    public function localizarSucursal(Sucursal $sucursalReferencia, array $sucursales): array
    {
        $latRef = $sucursalReferencia->getLatitud();
        $lonRef = $sucursalReferencia->getLongitud();


        $sucursalesConDistancia = [];
        foreach ($sucursales as $sucursal) {
            $distancia = $this->calcularDistanciaHaversine(
                $latRef,
                $lonRef,
                $sucursal->getLatitud(),
                $sucursal->getLongitud()
            );
            $sucursalesConDistancia[] = [
                'sucursal' => $sucursal,
                'distancia' => $distancia
            ];
        }


        usort($sucursalesConDistancia, function($a, $b) {
            return $a['distancia'] <=> $b['distancia'];
        });


        return array_map(function($item) {
            return $item['sucursal'];
        }, $sucursalesConDistancia);
    }


    private function calcularDistanciaHaversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $radioTierra = 6371;


        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);


        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLon / 2) * sin($deltaLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $radioTierra * $c;
    }

    public function ordernarPorCercanía($sucursal, $sucursales)
    {
        return $this->localizarSucursal($sucursal, $sucursales);
    }
}
