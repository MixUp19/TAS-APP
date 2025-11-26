<?php

namespace App\Domain;

use App\DomainModels\Receta;
use App\Providers\RecetaRepository;
use App\Providers\SucursalRepository;
use App\DomainModels\Sucursal;

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

    public function cancelarPedido($folio)
    {
        $this->recetaPorCancelar = $this->recetaRepository->obtenerRecetaPorFolio($folio);
        $this->recetaPorCancelar->setEstado("Cancelada por no recoger");
    }

    public function confirmarCancelacion()
    {
        $this->recetaPorCancelar->notificarDevolucion();
        $this->recetaRepository->actualizarReceta($this->recetaPorCancelar);
    }

    public function obtenerRecetas(){
        $sucursal = $this->sucursalRepository->obtenerSucursal( "SUC001", "FAR001");
        return $this->recetaRepository->obtenerRecetasPorSucursal($sucursal);
    }
}
