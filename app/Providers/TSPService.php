<?php

namespace App\Providers;

use App\DomainModels\Sucursal;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TSPService
{
    private string $baseUrl = 'http://localhost:8081';

    /**
     * Optimiza la ruta de sucursales usando el servicio TSP externo.
     * 
     * @param Sucursal $sucursalInicio La sucursal desde donde se inicia la ruta.
     * @param array $lineas Lista de LineaReceta que contienen los detalles con las sucursales.
     * @return array Lista de LineaReceta ordenadas según la ruta óptima de sus sucursales.
     */
    public function optimizarRuta(Sucursal $sucursalInicio, array $lineas): array
    {
        $sucursalesUnicas = [];
        $mapaSucursales = [];

        foreach ($lineas as $linea) {
            foreach ($linea->getDetalleLineaReceta() as $detalle) {
              Log::info($detalle->getSucursal()->getSucursalId());
                $sucursal = $detalle->getSucursal();
                $key = $sucursal->getSucursalId() . '-' . $sucursal->getCadena()->getCadenaId();
                
                if (!isset($mapaSucursales[$key])) {
                    if ($sucursal->getSucursalId() !== $sucursalInicio->getSucursalId() || 
                        $sucursal->getCadena()->getCadenaId() !== $sucursalInicio->getCadena()->getCadenaId()) {
                        
                        $mapaSucursales[$key] = $sucursal;
                        $sucursalesUnicas[] = $sucursal;
                    }
                }
            }
        }

        if (empty($sucursalesUnicas)) {
            return $lineas;
        }

        $nodos = [];
        $nodos[] = $sucursalInicio;
        foreach ($sucursalesUnicas as $s) {
            $nodos[] = $s;
        }

        $payload = [];
        $mapaIndices = []; 

        foreach ($nodos as $index => $sucursal) {
            $payload[] = [
                'id' => $index,
                'nombre' => $sucursal->getSucursalId(),
                'latitud' => $sucursal->getLatitud(),
                'longitud' => $sucursal->getLongitud()
            ];
            $mapaIndices[$index] = $sucursal;
        }

        try {
            $response = Http::post("{$this->baseUrl}/optimizar-ruta", $payload);

            if ($response->successful()) {
                $data = $response->json();
                $rutaOptima = $data['ruta_optima']; 
                
                $ordenSucursales = [];
                foreach ($rutaOptima as $nodo) {
                    $originalIndex = $nodo['id'];
                    if (isset($mapaIndices[$originalIndex])) {
                        $sucursal = $mapaIndices[$originalIndex];
                        $key = $sucursal->getSucursalId() . '-' . $sucursal->getCadena()->getCadenaId();
                        $ordenSucursales[] = $key;
                    }
                }

              
                $prioridadSucursal = array_flip($ordenSucursales); 

                usort($lineas, function ($lineaA, $lineaB) use ($prioridadSucursal) {
                    $minPosA = PHP_INT_MAX;
                    foreach ($lineaA->getDetalleLineaReceta() as $detalle) {
                        $s = $detalle->getSucursal();
                        $key = $s->getSucursalId() . '-' . $s->getCadena()->getCadenaId();
                        if (isset($prioridadSucursal[$key])) {
                            $minPosA = min($minPosA, $prioridadSucursal[$key]);
                        }
                    }

                    $minPosB = PHP_INT_MAX;
                    foreach ($lineaB->getDetalleLineaReceta() as $detalle) {
                        $s = $detalle->getSucursal();
                        $key = $s->getSucursalId() . '-' . $s->getCadena()->getCadenaId();
                        if (isset($prioridadSucursal[$key])) {
                            $minPosB = min($minPosB, $prioridadSucursal[$key]);
                        }
                    }

                    return $minPosA <=> $minPosB;
                });
                foreach ($lineas as $linea) {
            foreach ($linea->getDetalleLineaReceta() as $detalle) {
              Log::info($detalle->getSucursal()->getSucursalId());
            }
          }
                return $lineas;

            } else {
                Log::error("Error en TSP Service: " . $response->body());
                return $lineas;
            }
        } catch (\Exception $e) {
            Log::error("Excepción en TSP Service: " . $e->getMessage());
            return $lineas;
        }
    }
}
