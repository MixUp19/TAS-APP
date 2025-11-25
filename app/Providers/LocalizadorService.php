<?php

namespace App\Providers;

use App\DomainModels\Sucursal;

class LocalizadorService
{
    /**
     * Ordena un array de sucursales por cercanía a una sucursal de referencia
     * usando la fórmula de Haversine para calcular distancias geográficas.
     * 
     * @param Sucursal $sucursalReferencia
     * @param array $sucursales
     * @return array Sucursales ordenadas por distancia (más cercana primero)
     */
    public function localizarSucursal(Sucursal $sucursalReferencia, array $sucursales): array
    {
        $latRef = $sucursalReferencia->getLatitud();
        $lonRef = $sucursalReferencia->getLongitud();
        
        // Calcular distancia para cada sucursal
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
        
        // Ordenar por distancia ascendente
        usort($sucursalesConDistancia, function($a, $b) {
            return $a['distancia'] <=> $b['distancia'];
        });
        
        // Retornar solo las sucursales ordenadas
        return array_map(function($item) {
            return $item['sucursal'];
        }, $sucursalesConDistancia);
    }
    
    /**
     * Calcula la distancia entre dos puntos geográficos usando la fórmula de Haversine
     * 
     * @param float $lat1 Latitud del punto 1
     * @param float $lon1 Longitud del punto 1
     * @param float $lat2 Latitud del punto 2
     * @param float $lon2 Longitud del punto 2
     * @return float Distancia en kilómetros
     */
    private function calcularDistanciaHaversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $radioTierra = 6371; // Radio de la Tierra en kilómetros
        
        // Convertir grados a radianes
        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);
        
        // Diferencias
        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;
        
        // Fórmula de Haversine
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLon / 2) * sin($deltaLon / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $radioTierra * $c;
    }
    
    // Mantener compatibilidad con nombre antiguo
    public function ordernarPorCercanía($sucursal, $sucursales) 
    {
        return $this->localizarSucursal($sucursal, $sucursales);
    }
}
