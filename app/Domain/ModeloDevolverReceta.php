<?php

namespace App\Domain;

use App\DomainModels\Receta;
use App\Providers\RecetaRepository;
use App\Providers\SucursalRepository;
use App\DomainModels\Sucursal;
use Illuminate\Support\Facades\Session;

class ModeloDevolverReceta
{
    private RecetaRepository $recetaRepository;
    private SucursalRepository $sucursalRepository;
    private ?Receta $recetaPorCancelar;

    public function __construct()
    {
        $this->recetaRepository = app(RecetaRepository::class);
        $this->sucursalRepository = app(SucursalRepository::class);
    }

    public function cancelarReceta($folio): void
    {
        $this->recetaPorCancelar = $this->recetaRepository->obtenerRecetaPorFolio($folio);
        $this->recetaPorCancelar->setEstado(estado: "Cancelada");
    }

    public function confirmarCancelacion() : Receta
    {
        $this->recetaPorCancelar->notificarDevolucion();
        $this->recetaRepository->actualizarReceta($this->recetaPorCancelar);

        return $this->recetaPorCancelar;
    }

    public function obtenerRecetas(){
        if (!Session::has('usuario')) {
            return [];
        }
         
        $cadenaId = Session::get('usuario')->getSucursal()->getCadena()->getCadenaId();
        $sucursalId = Session::get('usuario')->getSucursal()->getSucursalId();

        $sucursal = $this->sucursalRepository->obtenerSucursal($sucursalId, $cadenaId); 
        return $this->recetaRepository->obtenerRecetasPendientesPorSucursal($sucursal);
    }

    public function obtenerReceta($folio){
        return $this->recetaRepository->obtenerRecetaPorFolio($folio);
    }

    public function cambiarEstadoReceta($folio, $nuevoEstado){
        $receta = $this->recetaRepository->obtenerRecetaPorFolio($folio);
        $receta->setEstado($nuevoEstado);
        $this->recetaRepository->actualizarReceta($receta);
    }
}
